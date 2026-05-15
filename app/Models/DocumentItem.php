<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentItem extends Model
{
    use HasFactory;

    protected $table = 'document_items';

    protected $fillable = [
        'document_id',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'tax_rate',
        'subtotal',
        'tax_amount',
        'total',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Get the document that owns this item
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Calculate item totals
     */
    public function calculateTotals()
    {
        $quantity = floatval($this->quantity);
        $unitPrice = floatval($this->unit_price);
        $taxRate = floatval($this->tax_rate);
        $discount = floatval($this->discount ?? 0);

        // Calculate subtotal before tax and discount
        $subtotal = $quantity * $unitPrice;

        // Apply discount
        if ($discount > 0) {
            $discountAmount = $subtotal * ($discount / 100);
            $subtotal = $subtotal - $discountAmount;
        }

        // Calculate tax
        $taxAmount = $subtotal * ($taxRate / 100);

        // Calculate total
        $total = $subtotal + $taxAmount;

        $this->subtotal = round($subtotal, 2);
        $this->tax_amount = round($taxAmount, 2);
        $this->total = round($total, 2);

        return $this;
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute()
    {
        $currency = 'FCFA';
        if ($this->document && $this->document->company) {
            $currency = $this->document->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->unit_price, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        $currency = 'FCFA';
        if ($this->document && $this->document->company) {
            $currency = $this->document->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->total, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        $currency = 'FCFA';
        if ($this->document && $this->document->company) {
            $currency = $this->document->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->subtotal, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted tax amount
     */
    public function getFormattedTaxAmountAttribute()
    {
        $currency = 'FCFA';
        if ($this->document && $this->document->company) {
            $currency = $this->document->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->tax_amount, 2, ',', ' ') . ' ' . $currency;
    }
}

