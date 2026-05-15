<?php

namespace App\Actions;

use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CreateDocument
{
    /**
     * Create a new document (quote or invoice)
     *
     * @param array $input
     * @return Document
     */
    public function create($user, array $input)
    {
        $company = Company::find($input['company_id']);

        // Increment document number BEFORE creating
        $company->incrementDocumentNumber($input['type']);

        // Generate the number after incrementation
        $number = $company->generateDocumentNumber($input['type']);

        // Create the document
        $document = Document::create([
            'company_id' => $input['company_id'],
            'client_id' => $input['client_id'],
            'type' => $input['type'],
            'number' => $number,
            'status' => $input['status'] ?? 'draft',
            'issue_date' => $input['issue_date'],
            'due_date' => $input['due_date'],
            'payment_terms' => $input['payment_terms'] ?? 30,
            'notes' => $input['notes'] ?? null,
            'terms_conditions' => $input['terms_conditions'] ?? null,
            'discount_type' => $input['discount_type'] ?? null,
            'discount_value' => $input['discount_value'] ?? 0,
            'subtotal' => 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total' => 0,
            'paid_amount' => 0,
            'balance' => 0,
            'use_manual_total' => $input['use_manual_total'] ?? false,
            'manual_total' => $input['manual_total'] ?? null,
        ]);

        // Create document items
        $subtotal = 0;
        $taxAmount = 0;

        foreach ($input['items'] as $index => $itemData) {
            $quantity = floatval($itemData['quantity']);
            $unitPrice = floatval($itemData['unit_price']);
            $taxRate = floatval($itemData['tax_rate'] ?? 18);
            $discount = floatval($itemData['discount'] ?? 0);

            // Calculate item totals
            $itemSubtotal = $quantity * $unitPrice;

            // Apply discount
            if ($discount > 0) {
                $discountAmount = $itemSubtotal * ($discount / 100);
                $itemSubtotal = $itemSubtotal - $discountAmount;
            }

            $itemTax = $itemSubtotal * ($taxRate / 100);
            $itemTotal = $itemSubtotal + $itemTax;

            $subtotal += $itemSubtotal;
            $taxAmount += $itemTax;

            DocumentItem::create([
                'document_id' => $document->id,
                'description' => $itemData['description'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'tax_rate' => $taxRate,
                'subtotal' => round($itemSubtotal, 2),
                'tax_amount' => round($itemTax, 2),
                'total' => round($itemTotal, 2),
                'sort_order' => $index,
            ]);
        }

        // Calculate document totals
        $discountAmount = 0;
        if ($document->discount_type && $document->discount_value > 0) {
            if ($document->discount_type === 'percentage') {
                $discountAmount = $subtotal * ($document->discount_value / 100);
            } else {
                $discountAmount = $document->discount_value;
            }
        }

        $afterDiscount = $subtotal - $discountAmount;
        $total = $afterDiscount + $taxAmount;

        $document->update([
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($total, 2),
            'balance' => round($total, 2),
        ]);

        return $document;
    }

    /**
     * Update an existing document
     *
     * @param Document $document
     * @param array $input
     * @return Document
     */
    public function update(Document $document, array $input)
    {
        // Update document fields
        $document->update([
            'client_id' => $input['client_id'],
            'issue_date' => $input['issue_date'],
            'due_date' => $input['due_date'],
            'payment_terms' => $input['payment_terms'] ?? 30,
            'notes' => $input['notes'] ?? null,
            'terms_conditions' => $input['terms_conditions'] ?? null,
            'discount_type' => $input['discount_type'] ?? null,
            'discount_value' => $input['discount_value'] ?? 0,
            'use_manual_total' => $input['use_manual_total'] ?? false,
            'manual_total' => $input['manual_total'] ?? null,
        ]);

        // Delete existing items and recreate
        $document->items()->delete();

        // Recreate items
        $subtotal = 0;
        $taxAmount = 0;

        foreach ($input['items'] as $index => $itemData) {
            $quantity = floatval($itemData['quantity']);
            $unitPrice = floatval($itemData['unit_price']);
            $taxRate = floatval($itemData['tax_rate'] ?? 18);
            $discount = floatval($itemData['discount'] ?? 0);

            // Calculate item totals
            $itemSubtotal = $quantity * $unitPrice;

            // Apply discount
            if ($discount > 0) {
                $discountAmount = $itemSubtotal * ($discount / 100);
                $itemSubtotal = $itemSubtotal - $discountAmount;
            }

            $itemTax = $itemSubtotal * ($taxRate / 100);
            $itemTotal = $itemSubtotal + $itemTax;

            $subtotal += $itemSubtotal;
            $taxAmount += $itemTax;

            DocumentItem::create([
                'document_id' => $document->id,
                'description' => $itemData['description'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'tax_rate' => $taxRate,
                'subtotal' => round($itemSubtotal, 2),
                'tax_amount' => round($itemTax, 2),
                'total' => round($itemTotal, 2),
                'sort_order' => $index,
            ]);
        }

        // Recalculate document totals
        $document->updateTotals();

        return $document->fresh();
    }

    /**
     * Duplicate a document
     *
     * @param Document $document
     * @return Document
     */
    public function duplicate(Document $document)
    {
        $company = $document->company;

        // Increment document number
        $company->incrementDocumentNumber($document->type);
        $number = $company->generateDocumentNumber($document->type);

        // Create new document
        $newDocument = Document::create([
            'company_id' => $document->company_id,
            'client_id' => $document->client_id,
            'type' => $document->type,
            'number' => $number,
            'status' => 'draft',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays($document->payment_terms ?? 30)->toDateString(),
            'payment_terms' => $document->payment_terms,
            'notes' => $document->notes,
            'terms_conditions' => $document->terms_conditions,
            'discount_type' => $document->discount_type,
            'discount_value' => $document->discount_value,
            'subtotal' => 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total' => 0,
            'paid_amount' => 0,
            'balance' => 0,
        ]);

        // Duplicate items
        foreach ($document->items as $item) {
            DocumentItem::create([
                'document_id' => $newDocument->id,
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

        // Update totals
        $newDocument->updateTotals();

        return $newDocument;
    }
}

