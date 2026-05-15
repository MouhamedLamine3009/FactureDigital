<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'details',
        'instructions',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'details' => 'json',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($method) {
            // Si c'est la première méthode ou si is_default, s'assurer qu'il n'y a qu'un seul default
            if ($method->is_default) {
                static::where('company_id', $method->company_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($method) {
            if ($method->is_default) {
                static::where('company_id', $method->company_id)
                    ->where('id', '!=', $method->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getFormattedDetailsAttribute()
    {
        if (!$this->details) {
            return null;
        }

        $details = $this->details;

        if ($this->type === 'bank_transfer' && isset($details['iban'])) {
            return 'IBAN: ' . $details['iban'] .
                (isset($details['bic']) ? ' | BIC: ' . $details['bic'] : '');
        }

        return json_encode($details, JSON_PRETTY_PRINT);
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'bank_transfer' => 'Virement Bancaire',
            'check' => 'Chèque',
            'cash' => 'Espèces',
            'credit_card' => 'Carte de Crédit',
            'paypal' => 'PayPal',
            'other' => 'Autre',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function getBankDetails()
    {
        return $this->details ?? [];
    }
}

