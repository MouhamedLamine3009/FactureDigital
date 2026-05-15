<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Actions\ConvertQuoteToInvoice;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'company_id',
        'client_id',
        'parent_id',
        'type',
        'status',
        'number',
        'issue_date',
        'due_date',
        'payment_terms',
        'discount_type',
        'discount_value',
        'notes',
        'terms_conditions',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total',
        'paid_amount',
        'balance',
        'use_manual_total',
        'manual_total',
        'pdf_path',
        'sent_at',
        'viewed_at',
        'paid_at',
        'metadata',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'paid_at' => 'datetime',
        'discount_value' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'manual_total' => 'decimal:2',
        'use_manual_total' => 'boolean',
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Document types
    const TYPE_QUOTE = 'quote';
    const TYPE_INVOICE = 'invoice';

    // Statuses
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_VIEWED = 'viewed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REFUSED = 'refused';
    const STATUS_PAID = 'paid';
    const STATUS_PARTIAL_PAID = 'partial_paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->number)) {
                $company = Company::find($document->company_id);
                if ($company) {
                    $document->number = $company->generateDocumentNumber($document->type);
                }
            }
        });
    }

    /**
     * Get the company that owns the document
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the client for the document
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the parent document (for converted quotes)
     */
    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    /**
     * Get child documents (converted invoices from quotes)
     */
    public function children()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    /**
     * Get document items
     */
    public function items()
    {
        return $this->hasMany(DocumentItem::class)->orderBy('sort_order');
    }

    /**
     * Get payments for this document
     */
    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('payment_date', 'desc');
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        $currency = 'XOF';
        if ($this->company) {
            $currency = $this->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->total, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        $currency = 'XOF';
        if ($this->company) {
            $currency = $this->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->subtotal, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted tax amount
     */
    public function getFormattedTaxAttribute()
    {
        $currency = 'XOF';
        if ($this->company) {
            $currency = $this->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->tax_amount, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute()
    {
        $currency = 'XOF';
        if ($this->company) {
            $currency = $this->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->balance, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get formatted paid amount
     */
    public function getFormattedPaidAmountAttribute()
    {
        $currency = 'XOF';
        if ($this->company) {
            $currency = $this->company->currency_symbol ?? 'FCFA';
        }
        return number_format($this->paid_amount, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Get status badge with dark mode support
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            self::STATUS_DRAFT => [
                'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
                'Brouillon'
            ],
            self::STATUS_SENT => [
                'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
                'Envoyé'
            ],
            self::STATUS_VIEWED => [
                'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300',
                'Vu'
            ],
            self::STATUS_ACCEPTED => [
                'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300',
                'Accepté'
            ],
            self::STATUS_REFUSED => [
                'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                'Refusé'
            ],
            self::STATUS_PAID => [
                'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300',
                'Payé'
            ],
            self::STATUS_PARTIAL_PAID => [
                'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
                'Partiellement payé'
            ],
            self::STATUS_OVERDUE => [
                'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                'En retard'
            ],
            self::STATUS_CANCELLED => [
                'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
                'Annulé'
            ],
        ];

        if (!isset($statuses[$this->status])) {
            return '<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">' . ucfirst($this->status) . '</span>';
        }

        $status = $statuses[$this->status];
        return '<span class="px-2.5 py-1 text-xs font-medium rounded-full ' . $status[0] . '">' . $status[1] . '</span>';
    }

    /**
     * Get document type label
     */
    public function getTypeLabelAttribute()
    {
        return $this->type === self::TYPE_QUOTE ? 'Devis' : 'Facture';
    }

    /**
     * Update document totals
     */
    public function updateTotals()
    {
        $subtotal = floatval($this->items->sum('subtotal'));
        $taxAmount = floatval($this->items->sum('tax_amount'));

        $discountValue = isset($this->attributes['discount_value'])
            ? floatval($this->attributes['discount_value'])
            : 0;
        $discountType = $this->discount_type;

        $paidAmount = isset($this->attributes['paid_amount'])
            ? floatval($this->attributes['paid_amount'])
            : 0;

        $this->subtotal = $subtotal;
        $this->tax_amount = $taxAmount;

        // Apply global discount
        if ($discountType && $discountValue > 0) {
            if ($discountType === 'percentage') {
                $this->discount_amount = ($subtotal * $discountValue / 100);
            } else {
                $this->discount_amount = $discountValue;
            }
        } else {
            $this->discount_amount = 0;
        }

        $discountAmount = isset($this->attributes['discount_amount'])
            ? floatval($this->attributes['discount_amount'])
            : 0;

        $afterDiscount = $subtotal - $discountAmount;
        $this->total = $afterDiscount + $taxAmount;
        $this->balance = $this->total - $paidAmount;

        $this->saveQuietly();
    }

    /**
     * Check if document is paid
     */
    public function isPaid()
    {
        return $this->balance <= 0;
    }

    /**
     * Check if document is overdue
     */
    public function isOverdue()
    {
        return $this->type === self::TYPE_INVOICE
            && $this->status !== self::STATUS_PAID
            && $this->due_date < now()->toDateString();
    }

    /**
     * Mark document as sent
     */
    public function markAsSent()
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
        ]);
    }

    /**
     * Convert quote to invoice
     */
    public function convertToInvoice(array $overrides = [])
    {
        $action = new ConvertQuoteToInvoice();
        return $action->convert($this, $overrides);
    }

    /**
     * Check if document has been converted
     */
    public function hasBeenConverted()
    {
        return $this->children()->exists();
    }

    /**
     * Get converted document
     */
    public function getConvertedDocument()
    {
        return $this->children()->first();
    }

    /**
     * Mark quote as accepted
     */
    public function markAsAccepted()
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
        ]);
    }

    /**
     * Mark quote as refused
     */
    public function markAsRefused()
    {
        $this->update([
            'status' => self::STATUS_REFUSED,
        ]);
    }

    /**
     * Mark document as cancelled
     */
    public function markAsCancelled()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Mark document as overdue
     */
    public function markAsOverdue()
    {
        $this->update([
            'status' => self::STATUS_OVERDUE,
        ]);
    }

    /**
     * Check if document is accepted
     */
    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * Check if document is refused
     */
    public function isRefused()
    {
        return $this->status === self::STATUS_REFUSED;
    }

    /**
     * Check if document is cancelled
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Update status based on payment
     */
    public function updateStatusOnPayment()
    {
        if ($this->balance <= 0) {
            $this->update([
                'status' => self::STATUS_PAID,
                'paid_at' => now(),
            ]);
        } elseif ($this->paid_amount > 0) {
            $this->update([
                'status' => self::STATUS_PARTIAL_PAID,
            ]);
        }
    }

    /**
     * Add payment to document
     */
    public function addPayment($amount, $method = 'bank_transfer', $paymentDate = null, $notes = null)
    {
        $payment = Payment::create([
            'document_id' => $this->id,
            'amount' => $amount,
            'payment_date' => $paymentDate ?? now()->toDateString(),
            'method' => $method,
            'notes' => $notes,
        ]);

        // Update paid amount
        $this->paid_amount = floatval($this->paid_amount) + $amount;
        $this->updateStatusOnPayment();
        $this->saveQuietly();

        return $payment;
    }

    /**
     * Get due date status
     */
    public function getDueDateStatusAttribute()
    {
        if ($this->status === self::STATUS_PAID || $this->status === self::STATUS_CANCELLED) {
            return 'ok';
        }

        $dueDate = $this->due_date;

        if ($dueDate < now()->toDateString()) {
            return 'overdue';
        } elseif ($dueDate <= now()->addDays(7)->toDateString()) {
            return 'warning';
        }

        return 'ok';
    }

    /**
     * Get days until due
     */
    public function getDaysUntilDueAttribute()
    {
        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Scope for quotes
     */
    public function scopeQuotes($query)
    {
        return $query->where('type', self::TYPE_QUOTE);
    }

    /**
     * Scope for invoices
     */
    public function scopeInvoices($query)
    {
        return $query->where('type', self::TYPE_INVOICE);
    }

    /**
     * Scope for overdue documents
     */
    public function scopeOverdue($query)
    {
        return $query->where('type', self::TYPE_INVOICE)
            ->whereNotIn('status', [self::STATUS_PAID, self::STATUS_CANCELLED, self::STATUS_OVERDUE, self::STATUS_DRAFT])
            ->where('due_date', '<', now()->toDateString());
    }

    /**
     * Scope for paid documents
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for draft documents
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
}

