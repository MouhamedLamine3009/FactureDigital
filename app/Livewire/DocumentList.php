<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Document;

#[Layout('layouts.app')]
class DocumentList extends Component
{
    use WithPagination;

    public $type = 'all';
    public $status = 'all';
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filters = [
        'client_id' => '',
        'date_from' => '',
        'date_to' => '',
    ];

    // Delete confirmation modal
    public $showDeleteModal = false;
    public $documentToDelete = null;
    public $documentNumberToDelete = '';

    protected $queryString = [
        'type' => ['except' => 'all'],
        'status' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getDocumentsProperty()
    {
        $company = auth()->user()->currentCompany ?? auth()->user()->companies()->first();

        if (!$company) {
            return Document::where('id', null)->paginate(15);
        }

        $query = Document::with('client')
            ->where('company_id', $company->id);

        if ($this->type !== 'all') {
            $query->where('type', $this->type);
        }

        if ($this->status !== 'all') {
            // Handle special status mapping
            if ($this->status === 'invoice') {
                $query->where('type', 'invoice');
            } elseif ($this->status === 'quote') {
                $query->where('type', 'quote');
            } else {
                $query->where('status', $this->status);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', function ($clientQuery) {
                        $clientQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->filters['client_id']) {
            $query->where('client_id', $this->filters['client_id']);
        }

        if ($this->filters['date_from']) {
            $query->whereDate('issue_date', '>=', $this->filters['date_from']);
        }

        if ($this->filters['date_to']) {
            $query->whereDate('issue_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.document-list', [
            'documents' => $this->documents,
        ]);
    }

    /**
     * Open delete confirmation modal
     */
    public function confirmDelete($documentId)
    {
        $this->documentToDelete = Document::findOrFail($documentId);
        $this->documentNumberToDelete = $this->documentToDelete->number;
        $this->showDeleteModal = true;
    }

    /**
     * Cancel delete
     */
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->documentToDelete = null;
        $this->documentNumberToDelete = '';
    }

    /**
     * Delete a document
     */
    public function delete()
    {
        if (!$this->documentToDelete) {
            return;
        }

        // Store the document number for the flash message
        $documentNumber = $this->documentToDelete->number;

        // Delete the document (items and payments will be cascade deleted)
        $this->documentToDelete->delete();

        // Close modal and reset
        $this->showDeleteModal = false;
        $this->documentToDelete = null;

        // Flash success message
        session()->flash('message', 'Document ' . $documentNumber . ' supprimé avec succès.');
    }

    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->search = '';
        $this->type = 'all';
        $this->status = 'all';
        $this->filters = [
            'client_id' => '',
            'date_from' => '',
            'date_to' => '',
        ];
        $this->resetPage();
    }
}

