<?php

namespace Tests\Feature;

use App\Livewire\ClientForm;
use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ClientFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_multiple_clients_with_empty_optional_unique_fields(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Test Company',
            'is_current' => true,
            'currency' => 'XOF',
            'currency_symbol' => 'FCFA',
        ]);

        $this->actingAs($user);

        Livewire::test(ClientForm::class)
            ->set('name', 'Client One')
            ->set('email', 'one@example.com')
            ->set('phone', '')
            ->set('ninea', '')
            ->set('vat_number', '')
            ->call('save')
            ->assertRedirect(route('clients.index'));

        Livewire::test(ClientForm::class)
            ->set('name', 'Client Two')
            ->set('email', 'two@example.com')
            ->set('phone', '')
            ->set('ninea', '')
            ->set('vat_number', '')
            ->call('save')
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseHas('clients', [
            'company_id' => $company->id,
            'name' => 'Client One',
            'phone' => null,
            'ninea' => null,
            'vat_number' => null,
        ]);

        $this->assertDatabaseHas('clients', [
            'company_id' => $company->id,
            'name' => 'Client Two',
            'phone' => null,
            'ninea' => null,
            'vat_number' => null,
        ]);

        $this->assertSame(2, Client::where('company_id', $company->id)->count());
    }

    public function test_it_creates_a_client_without_email(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user-no-email-client@example.com',
            'password' => Hash::make('password'),
        ]);

        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Test Company',
            'is_current' => true,
            'currency' => 'XOF',
            'currency_symbol' => 'FCFA',
        ]);

        $this->actingAs($user);

        Livewire::test(ClientForm::class)
            ->set('name', 'Client Sans Email')
            ->set('email', '')
            ->set('phone', '+221 77 123 45 67')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseHas('clients', [
            'company_id' => $company->id,
            'name' => 'Client Sans Email',
            'email' => null,
            'phone' => '+221 77 123 45 67',
        ]);
    }
}
