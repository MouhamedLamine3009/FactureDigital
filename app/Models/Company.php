<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'legal_name',
        'ninea',
        'vat_number',
        'rccm',
        'address',
        'postal_code',
        'city',
        'country',
        'phone',
        'email',
        'website',
        'logo_path',
        'signature_path',
        'bank_iban',
        'bank_bic',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'vat_applicable',
        'invoice_prefix',
        'quote_prefix',
        'invoice_next_number',
        'quote_next_number',
        'terms_conditions',
        'footer_notes',
        'currency',
        'currency_symbol',
        'is_current',
    ];
    protected $casts = [
        'vat_applicable' => 'boolean',
        'invoice_next_number' => 'integer',
        'quote_next_number' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_current' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function getNextDocumentNumber($type)
    {
        if ($type === 'invoice') {
            $number = $this->invoice_prefix . str_pad($this->invoice_next_number, 6, '0', STR_PAD_LEFT);
        } elseif ($type === 'quote') {
            $number = $this->quote_prefix . str_pad($this->quote_next_number, 6, '0', STR_PAD_LEFT);
        } else {
            throw new \InvalidArgumentException("Invalid document type: $type");
        }
        return $number;
    }

    public function generateDocumentNumber($type)
    {
        return $this->getNextDocumentNumber($type);
    }
    public function incrementDocumentNumber($type)
    {
        if ($type === 'invoice') {
            $this->increment('invoice_next_number');
        } elseif ($type === 'quote') {
            $this->increment('quote_next_number');
        } else {
            throw new \InvalidArgumentException("Invalid document type: $type");
        }
    }
}
