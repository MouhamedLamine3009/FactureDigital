<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'postal_code',
        'city',
        'country',
        'ninea',
        'vat_number',
        'notes',
        'is_active',
        'payment_method',
        'payment_terms',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_terms' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->postal_code,
            $this->city,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDocumentsCountAttribute()
    {
        return $this->documents()->count();
    }
}
