<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Client;
use App\Models\Document;
use App\Actions\CreateDocument;

#[Layout('layouts.app')]
class DocumentForm extends Component
{
    use WithFileUploads;

    public $document;
    public $type;
    public $companyId;
    public $saving = false;

    // Wizard steps
    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Client
    public $client_id;

    // Step 2: Items
    public $items = [];
    public $clients = [];

    // Step 3: Settings
    public $issue_date;
    public $due_date;
    public $payment_terms = 30;
    public $notes;
    public $terms_conditions;

    // Devise
    public $currency = 'XOF';
    public $currency_symbol = 'FCFA';

    // Computed properties
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    public $discount = 0;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'issue_date' => 'required|date',
        'due_date' => 'required|date|after_or_equal:issue_date',
        'items' => 'required|array|min:1',
        'items.*.description' => 'required|string|min:1',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.tax_rate' => 'required|numeric|in:0,18',
    ];

    public function messages()
    {
        return [
            'client_id.required' => 'Veuillez sélectionner un client',
            'items.*.description.required' => 'La description est requise pour chaque article',
            'items.*.quantity.min' => 'La quantité doit être supérieure à 0',
            'items.*.unit_price.required' => 'Le prix est requis',
        ];
    }

    public function mount($documentOrType = null, $type = null)
    {
        // Livewire page components map route params to mount() in order.
        // - /documents/form/{type} passes only the document type
        // - /documents/{document}/edit passes only the document instance/id
        if ($type === null && in_array($documentOrType, ['invoice', 'quote'])) {
            $this->type = $documentOrType;
            $document = null;
        } else {
            $document = $documentOrType;
            $this->type = $type ?? 'invoice';
        }

        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $company = $user->currentCompany ?? $user->companies()->first();

        if (!$company) {
            $this->companyId = null;
            $this->clients = [];
            return;
        }

        $this->companyId = $company->id;
        $this->clients = Client::where('company_id', $this->companyId)
            ->active()
            ->orderBy('name')
            ->get();

        $this->currency = $company->currency ?? 'XOF';
        $this->currency_symbol = $company->currency_symbol ?? 'FCFA';

        if ($document) {
            if (is_string($document) || is_int($document)) {
                $this->document = Document::find($document);
            } else {
                $this->document = $document;
            }

            if ($this->document) {
                $this->type = $this->document->type;
                $this->loadDocument();
            }
        } else {
            $this->type = $type ?: 'invoice';
            $this->issue_date = now()->format('Y-m-d');
            $this->due_date = now()->addDays(30)->format('Y-m-d');
            $this->addItem();
            $this->terms_conditions = $company->terms_conditions;
        }

        $this->calculateTotals();
    }

    public function loadDocument()
    {
        $this->client_id = $this->document->client_id;
        $this->issue_date = $this->document->issue_date->format('Y-m-d');
        $this->due_date = $this->document->due_date->format('Y-m-d');
        $this->payment_terms = $this->document->payment_terms;
        $this->notes = $this->document->notes;
        $this->terms_conditions = $this->document->terms_conditions;

        foreach ($this->document->items as $item) {
            $this->items[] = [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount ?? 0,
                'tax_rate' => $item->tax_rate,
            ];
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validateOnly('client_id');
        } elseif ($this->currentStep === 2) {
            $this->validateOnly('items');
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'discount' => 0,
            'tax_rate' => 18,
        ];
        $this->calculateTotals();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updated($property)
    {
        if (str_starts_with($property, 'items.')) {
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $subtotal = 0;
        $tax = 0;
        $discount = 0;

        foreach ($this->items as $item) {
            $quantity = floatval($item['quantity'] ?? 1);
            $unitPrice = floatval($item['unit_price'] ?? 0);
            $taxRate = floatval($item['tax_rate'] ?? 18);
            $itemDiscount = floatval($item['discount'] ?? 0);

            $itemSubtotal = $quantity * $unitPrice;
            if ($itemDiscount > 0) {
                $itemDiscountAmount = $itemSubtotal * ($itemDiscount / 100);
                $discount += $itemDiscountAmount;
                $itemSubtotal = $itemSubtotal - $itemDiscountAmount;
            }

            $subtotal += $itemSubtotal;
            $tax += $itemSubtotal * ($taxRate / 100);
        }

        $this->subtotal = round($subtotal, 2);
        $this->discount = round($discount, 2);
        $this->tax = round($tax, 2);
        $this->total = round($subtotal + $tax, 2);
    }

    public function getItemTotal($item)
    {
        $quantity = floatval($item['quantity'] ?? 1);
        $unitPrice = floatval($item['unit_price'] ?? 0);
        $taxRate = floatval($item['tax_rate'] ?? 18);

        return round($quantity * $unitPrice * (1 + $taxRate / 100), 2);
    }

    public function getPreviewData()
    {
        $client = Client::find($this->client_id);

        return [
            'client' => $client,
            'items' => $this->items,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'notes' => $this->notes,
            'terms_conditions' => $this->terms_conditions,
        ];
    }

    public function save()
    {
        $this->validate();

        $action = new CreateDocument();

        $input = [
            'company_id' => $this->companyId,
            'client_id' => $this->client_id,
            'type' => $this->type,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'payment_terms' => $this->payment_terms,
            'notes' => $this->notes,
            'terms_conditions' => $this->terms_conditions,
            'items' => $this->items,
        ];

        if ($this->document) {
            $this->document->update($input);
            $this->document->items()->delete();

            foreach ($this->items as $index => $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $taxRate = floatval($itemData['tax_rate']);
                $itemDiscount = floatval($itemData['discount'] ?? 0);

                // Calculate item totals (same as calculateTotals() + discount)
                $itemSubtotal = $quantity * $unitPrice;
                if ($itemDiscount > 0) {
                    $discountAmount = $itemSubtotal * ($itemDiscount / 100);
                    $itemSubtotal -= $discountAmount;
                }
                $itemTax = $itemSubtotal * ($taxRate / 100);
                $itemTotal = $itemSubtotal + $itemTax;

                $this->document->items()->create([
                    'description' => $itemData['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $itemDiscount,
                    'tax_rate' => $taxRate,
                    'subtotal' => round($itemSubtotal, 2),
                    'tax_amount' => round($itemTax, 2),
                    'total' => round($itemTotal, 2),
                    'sort_order' => $index,
                ]);
            }

            $this->document->updateTotals();

            $documentType = $this->type === 'quote' ? 'Devis' : 'Facture';

            return redirect()->route('documents.show', $this->document)->with('success', $documentType . ' mis à jour avec succès');
        } else {
            $document = $action->create(auth()->user(), $input);

            $documentType = $this->type === 'quote' ? 'Devis' : 'Facture';

            return redirect()->route('documents.show', $document)->with('success', $documentType . ' créé avec succès');
        }
    }
}

