<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Document;
use App\Models\Payment;

#[Layout('layouts.app')]
class DocumentShow extends Component
{
    public Document $document;
    public $company;
    public $client;
    public $items;
    public $payments;
    public $showEmailModal = false;

    // Payment modal properties
    public $showPaymentModal = false;
    public $paymentAmount = '';
    public $paymentMethod = 'bank_transfer';
    public $paymentDate = '';
    public $paymentNotes = '';

    protected $rules = [
        'paymentAmount' => 'required|numeric|min:0.01',
        'paymentMethod' => 'required|string',
        'paymentDate' => 'required|date',
        'paymentNotes' => 'nullable|string',
    ];

    public function mount(Document $document)
    {
        $this->document = $document->load(['company', 'client', 'items', 'payments']);
        $this->company = $document->company;
        $this->client = $document->client;
        $this->items = $document->items()->orderBy('sort_order')->get();
        $this->payments = $document->payments()->orderBy('payment_date', 'desc')->get();

        // Set default payment date to today
        $this->paymentDate = now()->format('Y-m-d');

        // Marquer le document comme vu
        if (!$document->viewed_at && $document->status !== 'draft') {
            $document->update(['viewed_at' => now()]);
        }
    }

    public function viewPdf()
    {
        return redirect()->route('documents.view-pdf', $this->document);
    }

    public function downloadPdf()
    {
        return redirect()->route('documents.pdf', $this->document);
    }

    public function markAsSent()
    {
        $this->document->markAsSent();
        $this->document->refresh();
        session()->flash('message', 'Document marqué comme envoyé.');
    }

    public function markAsPaid()
    {
        $this->document->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        $this->document->refresh();
        session()->flash('message', 'Document marqué comme payé.');
    }

    public function openEmailModal()
    {
        $this->showEmailModal = true;
    }

    public function closeEmailModal()
    {
        $this->showEmailModal = false;
    }

    public function openPaymentModal()
    {
        // Pre-fill with remaining balance or full amount
        $this->paymentAmount = $this->document->balance > 0 ? number_format($this->document->balance, 2, '.', '') : number_format($this->document->total, 2, '.', '');
        $this->paymentMethod = 'bank_transfer';
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentNotes = '';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetValidation();
    }

    public function recordPayment()
    {
        $this->validate();

        // Create payment record
        $payment = Payment::create([
            'document_id' => $this->document->id,
            'amount' => (float) $this->paymentAmount,
            'payment_date' => $this->paymentDate,
            'method' => $this->paymentMethod,
            'notes' => $this->paymentNotes,
        ]);

        // Refresh document and payments
        $this->document->refresh();
        $this->payments = $this->document->payments()->orderBy('payment_date', 'desc')->get();

        // Update document status based on payment
        $this->document->updateStatusOnPayment();
        $this->document->refresh();

        $this->showPaymentModal = false;
        $this->paymentAmount = '';
        $this->paymentNotes = '';

        session()->flash('message', 'Paiement enregistré avec succès.');
    }

    /**
     * Mark the quote as accepted
     */
    public function markAsAccepted()
    {
        $this->document->markAsAccepted();
        $this->document->refresh();
        session()->flash('message', 'Devis marqué comme accepté.');
    }

    /**
     * Mark the quote as refused
     */
    public function markAsRefused()
    {
        $this->document->markAsRefused();
        $this->document->refresh();
        session()->flash('message', 'Devis marqué comme refusé.');
    }

    /**
     * Mark the document as cancelled
     */
    public function markAsCancelled()
    {
        $this->document->markAsCancelled();
        $this->document->refresh();
        session()->flash('message', 'Document marqué comme annulé.');
    }

    public function render()
    {
        return view('livewire.document-show', [
            'document' => $this->document,
            'company' => $this->company,
            'client' => $this->client,
            'items' => $this->items,
            'payments' => $this->payments,
        ]);
    }
}

