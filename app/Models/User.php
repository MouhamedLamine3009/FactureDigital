<?php

namespace App\Models;

use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasTeams;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all companies for this user
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Get the current active company for this user
     * This is an attribute that gets the company with is_current = true
     * or falls back to the first company
     */
    public function getCurrentCompanyAttribute()
    {
        // Try to find the current company
        $currentCompany = $this->companies()->where('is_current', true)->first();

        if ($currentCompany) {
            return $currentCompany;
        }

        // If no current company, return the first company
        return $this->companies()->first();
    }
}
