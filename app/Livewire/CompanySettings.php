<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Company;

#[Layout('layouts.app')]
class CompanySettings extends Component
{
    use WithFileUploads;

    public Company $company;

    // Company fields
    public $name;
    public $legal_name;
    public $ninea;
    public $vat_number;
    public $rccm;
    public $address;
    public $postal_code;
    public $city;
    public $country = 'Sénégal';
    public $phone;
    public $email;
    public $website;
    public $logo;
    public $signature;
    public $bank_iban;
    public $bank_bic;
    public $bank_name;
    public $bank_account_name;
    public $bank_account_number;
    public $vat_applicable = true;
    public $invoice_prefix = 'FAC-';
    public $quote_prefix = 'DEV-';
    public $invoice_next_number;
    public $quote_next_number;
    public $terms_conditions;
    public $footer_notes;
    public $currency = 'XOF';
    public $currency_symbol = 'FCFA';

    // Confirmation modal
    public $showConfirmModal = false;
    public $pendingLogo;
    public $pendingSignature;

    protected $rules = [
        'name' => 'required|string|max:255',
        'legal_name' => 'nullable|string|max:255',
        'ninea' => 'nullable|string|max:50',
        'vat_number' => 'nullable|string|max:50',
        'rccm' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'postal_code' => 'nullable|string|max:20',
        'city' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'website' => 'nullable|url|max:255',
        'logo' => 'nullable|image|max:1024',
        'signature' => 'nullable|image|max:1024',
        'bank_iban' => 'nullable|string|max:50',
        'bank_bic' => 'nullable|string|max:20',
        'bank_name' => 'nullable|string|max:100',
        'bank_account_name' => 'nullable|string|max:255',
        'bank_account_number' => 'nullable|string|max:50',
        'vat_applicable' => 'boolean',
        'invoice_prefix' => 'nullable|string|max:10',
        'quote_prefix' => 'nullable|string|max:10',
        'invoice_next_number' => 'nullable|integer|min:1',
        'quote_next_number' => 'nullable|integer|min:1',
        'terms_conditions' => 'nullable|string',
        'footer_notes' => 'nullable|string',
        'currency' => 'nullable|string|max:3',
        'currency_symbol' => 'nullable|string|max:10',
    ];

    public function mount()
    {
        $this->loadCompany();
    }

    public function loadCompany()
    {
        $user = auth()->user();
        $company = $user->currentCompany ?? $user->companies()->first();

        if (!$company) {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $user->name . ' - Entreprise',
                'is_current' => true,
                'currency' => 'XOF',
                'currency_symbol' => 'FCFA',
            ]);
        }

        $this->company = $company;
        $this->fillCompanyFields();
    }

    public function fillCompanyFields()
    {
        $this->name = $this->company->name;
        $this->legal_name = $this->company->legal_name;
        $this->ninea = $this->company->ninea;
        $this->vat_number = $this->company->vat_number;
        $this->rccm = $this->company->rccm;
        $this->address = $this->company->address;
        $this->postal_code = $this->company->postal_code;
        $this->city = $this->company->city;
        $this->country = $this->company->country;
        $this->phone = $this->company->phone;
        $this->email = $this->company->email;
        $this->website = $this->company->website;
        $this->bank_iban = $this->company->bank_iban;
        $this->bank_bic = $this->company->bank_bic;
        $this->bank_name = $this->company->bank_name;
        $this->bank_account_name = $this->company->bank_account_name;
        $this->bank_account_number = $this->company->bank_account_number;
        $this->vat_applicable = $this->company->vat_applicable;
        $this->invoice_prefix = $this->company->invoice_prefix;
        $this->quote_prefix = $this->company->quote_prefix;
        $this->invoice_next_number = $this->company->invoice_next_number;
        $this->quote_next_number = $this->company->quote_next_number;
        $this->terms_conditions = $this->company->terms_conditions;
        $this->footer_notes = $this->company->footer_notes;
        $this->currency = $this->company->currency ?? 'XOF';
        $this->currency_symbol = $this->company->currency_symbol ?? 'FCFA';
    }

    public function save()
    {
        // Simply show confirmation modal without validation
        $this->showConfirmModal = true;
    }

    public function test()
    {
        $this->dispatch('show-success', message: 'Livewire fonctionne!');
    }

    public function confirmSave()
    {
        // Validate only when actually saving
        $this->validate();

        $data = [
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'ninea' => $this->ninea,
            'vat_number' => $this->vat_number,
            'rccm' => $this->rccm,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'bank_iban' => $this->bank_iban,
            'bank_bic' => $this->bank_bic,
            'bank_name' => $this->bank_name,
            'bank_account_name' => $this->bank_account_name,
            'bank_account_number' => $this->bank_account_number,
            'vat_applicable' => $this->vat_applicable,
            'invoice_prefix' => $this->invoice_prefix,
            'quote_prefix' => $this->quote_prefix,
            'invoice_next_number' => $this->invoice_next_number ?? 1,
            'quote_next_number' => $this->quote_next_number ?? 1,
            'terms_conditions' => $this->terms_conditions,
            'footer_notes' => $this->footer_notes,
            'currency' => $this->currency ?? 'XOF',
            'currency_symbol' => $this->currency_symbol ?? 'FCFA',
        ];

        // Handle logo upload
        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        // Handle signature upload
        if ($this->signature) {
            $path = $this->signature->store('signatures', 'public');
            $data['signature_path'] = $path;
        }

        $this->company->update($data);

        // Close confirmation modal and emit success event
        $this->showConfirmModal = false;
        
        // Use session flash for compatibility with app layout
        session()->flash('message', 'Paramètres enregistrés avec succès !');
        
        // Also dispatch for components listening
        $this->dispatch('show-success', message: 'Paramètres enregistrés avec succès !');
    }

    public function render()
    {
        return view('livewire.company-settings');
    }
}

