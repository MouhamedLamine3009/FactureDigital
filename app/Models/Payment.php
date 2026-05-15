<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'reference',
        'amount',
        'payment_date',
        'method',
        'transaction_id',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->reference)) {
                $payment->reference = 'PAY-' . strtoupper(Str::random(8));
            }
        });

        static::created(function ($payment) {
            if ($payment->document) {
                $payment->updateDocumentBalance();
            }
        });

        static::updated(function ($payment) {
            if ($payment->document) {
                $payment->updateDocumentBalance();
            }
        });

        static::deleted(function ($payment) {
            if ($payment->document) {
                $payment->updateDocumentBalance();
            }
        });
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function updateDocumentBalance()
    {
        $document = $this->document;

        if (!$document) {
            return;
        }

        // Ensure numeric values are floats to avoid casting issues
        $totalPayments = floatval($document->payments()->sum('amount'));
        $total = floatval($document->total ?? 0);

        $document->paid_amount = $totalPayments;
        $document->balance = $total - $totalPayments;

        if ($document->balance <= 0) {
            $document->status = 'paid';
            $document->paid_at = now();
        } elseif ($totalPayments > 0) {
            $document->status = 'partial_paid';
        } else {
            $document->status = $document->status === 'paid' ? 'paid' : 'sent';
        }

        $document->saveQuietly();
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, '.', ',') . ' XOF';
    }

    public function getMethodLabelAttribute()
    {
        $methods = [
            'bank_transfer' => 'Virement Bancaire',
            'check' => 'Chèque',
            'cash' => 'Espèces',
            'credit_card' => 'Carte de Crédit',
            'online' => 'Paiement en Ligne',
        ];

        return $methods[$this->method] ?? ucfirst($this->method);
    }
}
