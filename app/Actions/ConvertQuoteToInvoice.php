<?php

namespace App\Actions;

use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Company;

class ConvertQuoteToInvoice
{
    /**
     * Convert a quote to an invoice
     *
     * @param Document $quote The quote to convert
     * @param array $overrides Optional data to override (like dates)
     * @return Document The newly created invoice
     */
    public function convert(Document $quote, array $overrides = [])
    {
        $company = $quote->company;

        // Generate new invoice number
        $company->incrementDocumentNumber('invoice');
        $invoiceNumber = $company->generateDocumentNumber('invoice');

        // Determine dates
        $issueDate = $overrides['issue_date'] ?? now()->toDateString();
        $dueDate = $overrides['due_date'] ?? now()->addDays($quote->payment_terms ?? 30)->toDateString();

        // Create the invoice based on the quote
        $invoice = Document::create([
            'company_id' => $quote->company_id,
            'client_id' => $quote->client_id,
            'parent_id' => $quote->id,
            'type' => 'invoice',
            'number' => $invoiceNumber,
            'status' => $overrides['status'] ?? 'draft',
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'payment_terms' => $quote->payment_terms,
            'notes' => $quote->notes,
            'terms_conditions' => $quote->terms_conditions,
            'discount_type' => $quote->discount_type,
            'discount_value' => $quote->discount_value,
            'subtotal' => $quote->subtotal,
            'discount_amount' => $quote->discount_amount,
            'tax_amount' => $quote->tax_amount,
            'total' => $quote->total,
            'paid_amount' => 0,
            'balance' => $quote->total,
        ]);

        // Copy items from quote to invoice
        foreach ($quote->items as $item) {
            DocumentItem::create([
                'document_id' => $invoice->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'tax_rate' => $item->tax_rate,
                'subtotal' => $item->subtotal,
                'tax_amount' => $item->tax_amount,
                'total' => $item->total,
                'sort_order' => $item->sort_order,
            ]);
        }

        // Mark the quote as accepted
        if ($quote->status !== 'accepted') {
            $quote->markAsAccepted();
        }

        return $invoice;
    }

    /**
     * Duplicate an invoice from a quote (keeping quote intact)
     *
     * @param Document $quote The quote to duplicate as invoice
     * @param array $overrides Optional data to override
     * @return Document The newly created invoice
     */
    public function duplicateAsInvoice(Document $quote, array $overrides = [])
    {
        $company = $quote->company;

        // Generate new invoice number
        $company->incrementDocumentNumber('invoice');
        $invoiceNumber = $company->generateDocumentNumber('invoice');

        // Determine dates
        $issueDate = $overrides['issue_date'] ?? now()->toDateString();
        $dueDate = $overrides['due_date'] ?? now()->addDays($quote->payment_terms ?? 30)->toDateString();

        // Create the invoice
        $invoice = Document::create([
            'company_id' => $quote->company_id,
            'client_id' => $quote->client_id,
            'type' => 'invoice',
            'number' => $invoiceNumber,
            'status' => $overrides['status'] ?? 'draft',
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'payment_terms' => $quote->payment_terms,
            'notes' => $quote->notes,
            'terms_conditions' => $quote->terms_conditions,
            'discount_type' => $quote->discount_type,
            'discount_value' => $quote->discount_value,
            'subtotal' => $quote->subtotal,
            'discount_amount' => $quote->discount_amount,
            'tax_amount' => $quote->tax_amount,
            'total' => $quote->total,
            'paid_amount' => 0,
            'balance' => $quote->total,
        ]);

        // Copy items
        foreach ($quote->items as $item) {
            DocumentItem::create([
                'document_id' => $invoice->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'tax_rate' => $item->tax_rate,
                'subtotal' => $item->subtotal,
                'tax_amount' => $item->tax_amount,
                'total' => $item->total,
                'sort_order' => $item->sort_order,
            ]);
        }

        return $invoice;
    }
}

