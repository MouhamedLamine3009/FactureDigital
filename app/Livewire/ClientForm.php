<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Client;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class ClientForm extends Component
{
    public $client;
    public $companyId;

    // Client fields
    public $type = 'individual';
    public $name;
    public $contact_name;
    public $email;
    public $phone;
    public $address;
    public $postal_code;
    public $city;
    public $country = 'Sénégal';
    public $ninea;
    public $vat_number;
    public $notes;
    public $is_active = true;
    public $payment_method;
    public $payment_terms = 30;

    protected function rules()
    {
        return [
            'type' => 'required|in:individual,company',
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')
                    ->where('company_id', $this->companyId)
                    ->ignore($this->client?->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('clients', 'phone')
                    ->where('company_id', $this->companyId)
                    ->ignore($this->client?->id),
            ],
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'ninea' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('clients', 'ninea')
                    ->where('company_id', $this->companyId)
                    ->ignore($this->client?->id),
            ],
            'vat_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('clients', 'vat_number')
                    ->where('company_id', $this->companyId)
                    ->ignore($this->client?->id),
            ],
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'payment_method' => 'nullable|string',
            'payment_terms' => 'nullable|integer|min:0',
        ];
    }

    public function mount($client = null)
    {
        $this->initializeCompany();

        if ($client) {
            // $client is the client ID from the route, need to fetch the model
            $this->client = Client::find($client);

            if ($this->client) {
                $this->loadClient();
            }
        }
    }

    public function initializeCompany()
    {
        $user = Auth::user();

        if (!$user) {
            $this->companyId = null;
            return;
        }

        // Try to get current company first
        $company = $user->currentCompany;

        // If no current company, try to get any company
        if (!$company) {
            $company = $user->companies()->first();
        }

        // If still no company, create a default one for the user
        if (!$company) {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $user->name . ' - Entreprise',
                'is_current' => true,
                'currency' => 'XOF',
                'currency_symbol' => 'FCFA',
            ]);
        }

        $this->companyId = $company->id;
    }

    public function loadClient()
    {
        $this->type = $this->client->type;
        $this->name = $this->client->name;
        $this->contact_name = $this->client->contact_name;
        $this->email = $this->client->email;
        $this->phone = $this->client->phone;
        $this->address = $this->client->address;
        $this->postal_code = $this->client->postal_code;
        $this->city = $this->client->city;
        $this->country = $this->client->country;
        $this->ninea = $this->client->ninea;
        $this->vat_number = $this->client->vat_number;
        $this->notes = $this->client->notes;
        $this->is_active = $this->client->is_active;
        $this->payment_method = $this->client->payment_method;
        $this->payment_terms = $this->client->payment_terms;
    }

    public function save()
    {
        // Validate company exists
        if (!$this->companyId) {
            session()->flash('error', 'Impossible de créer le client. companyId est vide (aucune entreprise trouvée pour l\'utilisateur).');
            return;
        }

        // Debug helpers (enabled in dev): show validation errors clearly
        $this->normalizeOptionalFields();

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Force session message so user can see what failed even if Livewire UI doesn't show it
            session()->flash('error', $e->getMessage());
            throw $e;
        }

        $data = [
            'company_id' => $this->companyId,
            'type' => $this->type,
            'name' => $this->name,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'country' => $this->country,
            'ninea' => $this->ninea,
            'vat_number' => $this->vat_number,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'payment_method' => $this->payment_method,
            'payment_terms' => $this->payment_terms,
        ];

        if ($this->client) {
            $this->client->update($data);
            session()->flash('message', 'Client mis à jour avec succès');
        } else {
            Client::create($data);
            session()->flash('message', 'Client créé avec succès');
        }

        return redirect()->route('clients.index');
    }

    private function normalizeOptionalFields(): void
    {
        foreach ([
            'contact_name',
            'email',
            'phone',
            'address',
            'postal_code',
            'city',
            'country',
            'ninea',
            'vat_number',
            'notes',
            'payment_method',
        ] as $field) {
            $this->{$field} = blank($this->{$field}) ? null : $this->{$field};
        }
    }

    public function render()
    {
        return view('livewire.client-form');
    }
}
