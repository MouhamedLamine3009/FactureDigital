<?php

namespace Tests\Feature;

use App\Livewire\CompanySettings;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class CompanySettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_company_settings_directly(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Old Company',
            'is_current' => true,
            'currency' => 'XOF',
            'currency_symbol' => 'FCFA',
        ]);

        $this->actingAs($user);

        Livewire::test(CompanySettings::class)
            ->set('name', 'New Company')
            ->set('email', 'contact@example.com')
            ->set('website', 'example.com')
            ->set('phone', '')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'New Company',
            'email' => 'contact@example.com',
            'website' => 'https://example.com',
            'phone' => null,
        ]);
    }
}
