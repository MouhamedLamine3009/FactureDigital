<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a setting value for a user
     */
    public static function getForUser($userId, $key, $default = null)
    {
        $setting = static::where('user_id', $userId)
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value for a user
     */
    public static function setForUser($userId, $key, $value)
    {
        return static::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Delete a setting for a user
     */
    public static function deleteForUser($userId, $key)
    {
        return static::where('user_id', $userId)
            ->where('key', $key)
            ->delete();
    }

    /**
     * Get all settings for a user as key-value array
     */
    public static function allForUser($userId)
    {
        $settings = static::where('user_id', $userId)->get();

        return $settings->pluck('value', 'key')->toArray();
    }

    /**
     * Common setting keys
     */
    const KEY_CURRENCY = 'currency';
    const KEY_CURRENCY_SYMBOL = 'currency_symbol';
    const KEY_INVOICE_PREFIX = 'invoice_prefix';
    const KEY_QUOTE_PREFIX = 'quote_prefix';
    const KEY_DEFAULT_PAYMENT_TERMS = 'default_payment_terms';
    const KEY_EMAIL_NOTIFICATIONS = 'email_notifications';
    const KEY_DEFAULT_VAT_RATE = 'default_vat_rate';
    const KEY_INVOICE_TEMPLATE = 'invoice_template';
    const KEY_QUOTE_TEMPLATE = 'quote_template';
    const KEY_TERMS_CONDITIONS = 'terms_conditions';
    const KEY_FOOTER_NOTES = 'footer_notes';
}

