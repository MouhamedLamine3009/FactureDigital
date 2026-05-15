<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ClientList extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $type = 'all';
    public $is_active = 'all';
    public $showImportModal = false;

    // Import/Export
    public $importFile;
    public $importing = false;
    public $importProgress = 0;
    public $importErrors = [];

    // Delete confirmation modal
    public $showDeleteModal = false;
    public $clientToDelete = null;
    public $clientNameToDelete = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => 'all'],
        'is_active' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingIsActive()
    {
        $this->resetPage();
    }

    public function getClientsProperty()
    {
        $company = auth()->user()->currentCompany ?? auth()->user()->companies()->first();

        if (!$company) {
            return Client::where('id', null)->paginate(15);
        }

        $companyId = $company->id;

        $query = Client::where('company_id', $companyId);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type !== 'all') {
            $query->where('type', $this->type);
        }

        if ($this->is_active !== 'all') {
            $query->where('is_active', $this->is_active === 'active');
        }

        return $query->orderBy('name')->paginate(5);
    }

    /**
     * Open delete confirmation modal
     */
    public function confirmDelete($clientId)
    {
        $this->clientToDelete = Client::findOrFail($clientId);
        $this->clientNameToDelete = $this->clientToDelete->name;
        $this->showDeleteModal = true;
    }

    /**
     * Cancel delete
     */
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->clientToDelete = null;
        $this->clientNameToDelete = '';
    }

    /**
     * Delete a client
     */
    public function delete()
    {
        if (!$this->clientToDelete) {
            return;
        }

        // Store the client name for the flash message
        $clientName = $this->clientToDelete->name;

        // Delete the client
        $this->clientToDelete->delete();

        // Close modal and reset
        $this->showDeleteModal = false;
        $this->clientToDelete = null;

        // Flash success message
        session()->flash('message', 'Client ' . $clientName . ' supprimé avec succès.');
    }

    /**
     * Export clients to CSV
     */
    public function exportCsv()
    {
        $company = auth()->user()->currentCompany ?? auth()->user()->companies()->first();

        if (!$company) {
            session()->flash('error', 'Aucune entreprise trouvée.');
            return;
        }

        $clients = Client::where('company_id', $company->id)
            ->orderBy('name')
            ->get();

        $csvData = [];

        // Header
        $csvData[] = [
            'name',
            'contact_name',
            'email',
            'phone',
            'address',
            'postal_code',
            'city',
            'country',
            'ninea',
            'vat_number',
            'type',
            'is_active',
            'payment_terms',
            'notes'
        ];

        // Data
        foreach ($clients as $client) {
            $csvData[] = [
                $client->name,
                $client->contact_name,
                $client->email,
                $client->phone,
                $client->address,
                $client->postal_code,
                $client->city,
                $client->country,
                $client->ninea,
                $client->vat_number,
                $client->type,
                $client->is_active ? '1' : '0',
                $client->payment_terms,
                $client->notes,
            ];
        }

        // Create CSV content
        $filename = 'clients_' . $company->slug . '_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://memory', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row, ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Convert to UTF-8 BOM for Excel compatibility
        $content = "\xEF\xBB\xBF" . $content;

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }

    /**
     * Import clients from CSV
     */
    public function importCsv()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt',
        ]);

        $company = auth()->user()->currentCompany ?? auth()->user()->companies()->first();

        if (!$company) {
            session()->flash('error', 'Aucune entreprise trouvée.');
            return;
        }

        $this->importing = true;
        $this->importProgress = 0;
        $this->importErrors = [];

        try {
            $file = $this->importFile;
            $path = $file->storeAs('imports', 'clients_' . time() . '.csv');

            $fullPath = Storage::disk('local')->path($path);
            $handle = fopen($fullPath, 'r');

            $headers = fgetcsv($handle, 0, ';');

            // Normalize headers
            $headers = array_map(function ($h) {
                return strtolower(trim($h));
            }, $headers);

            $rowCount = 0;
            $successCount = 0;
            $errors = [];

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $rowCount++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                $data = array_combine($headers, $row);

                if ($data === false) {
                    $errors[] = "Ligne $rowCount: Format invalide";
                    continue;
                }

                // Validate required fields
                if (empty($data['name'])) {
                    $errors[] = "Ligne $rowCount: Le nom est requis";
                    continue;
                }

                try {
                    Client::create([
                        'company_id' => $company->id,
                        'name' => $data['name'],
                        'contact_name' => $data['contact_name'] ?? null,
                        'email' => $data['email'] ?? null,
                        'phone' => $data['phone'] ?? null,
                        'address' => $data['address'] ?? null,
                        'postal_code' => $data['postal_code'] ?? null,
                        'city' => $data['city'] ?? null,
                        'country' => $data['country'] ?? 'Sénégal',
                        'ninea' => $data['ninea'] ?? null,
                        'vat_number' => $data['vat_number'] ?? null,
                        'type' => $data['type'] ?? 'company',
                        'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
                        'payment_terms' => $data['payment_terms'] ?? 30,
                        'notes' => $data['notes'] ?? null,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Ligne $rowCount: " . $e->getMessage();
                }

                $this->importProgress = min(100, round(($rowCount / max(1, filesize($fullPath) / 100)) * 50));
            }

            fclose($handle);

            // Clean up
            Storage::disk('local')->delete($path);

            $this->importing = false;
            $this->importProgress = 100;

            if (count($errors) > 0) {
                $this->importErrors = $errors;
                session()->flash('warning', "$successCount clients importés. " . count($errors) . " erreurs.");
            } else {
                session()->flash('message', "$successCount clients importés avec succès.");
            }

            $this->reset('importFile');

        } catch (\Exception $e) {
            $this->importing = false;
            session()->flash('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $csvData = [
            ['name', 'contact_name', 'email', 'phone', 'address', 'postal_code', 'city', 'country', 'ninea', 'vat_number', 'type', 'is_active', 'payment_terms', 'notes'],
            ['Entreprise Example', 'Jean Dupont', 'jean@example.com', '+221 70 123 45 67', '123 Rue de la Paix', 'BP 12345', 'Dakar', 'Sénégal', '123456789', 'SN12345678901', 'company', '1', '30', 'Notes optionnelles'],
        ];

        $handle = fopen('php://memory', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row, ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        $content = "\xEF\xBB\xBF" . $content;

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'modele_clients.csv', [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }

    public function render()
    {
        return view('livewire.client-list', [
            'clients' => $this->clients,
        ]);
    }
}
