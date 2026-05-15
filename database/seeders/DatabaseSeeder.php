<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create company
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Mon Entreprise',
            'legal_name' => 'Mon Entreprise SARL',
            'ninea' => '123456789',
            'vat_number' => 'SN12345678901',
            'rccm' => 'SN/DKR/2024/123456',
            'address' => '123 Rue de la Paix, Plateau',
            'postal_code' => 'BP 12345',
            'city' => 'Dakar',
            'country' => 'Sénégal',
            'phone' => '+221 70 123 45 67',
            'email' => 'contact@monentreprise.sn',
            'website' => 'https://monentreprise.sn',
            'bank_name' => 'Banque Atlantique Sénégal',
            'bank_iban' => 'SN06 SN06 12345 67890 12345 67890 03',
            'bank_bic' => 'ATLASNXXX',
            'vat_applicable' => true,
            'invoice_prefix' => 'FAC-',
            'quote_prefix' => 'DEV-',
            'invoice_next_number' => 1,
            'quote_next_number' => 1,
            'terms_conditions' => "Conditions de paiement : Paiement sous 30 jours à compter de la date de facturation.\n\nEn cas de retard de paiement, des pénalités de retard seront appliquées au taux annuel de 10%.",
            'footer_notes' => 'Merci pour votre confiance !',
            'currency' => 'XOF',
            'currency_symbol' => 'FCFA',
            'is_current' => true,
        ]);

        // Create sample clients
        $clients = [
            [
                'company_id' => $company->id,
                'type' => 'company',
                'name' => 'Société ABC',
                'contact_name' => 'Jean Dupont',
                'email' => 'jean.dupont@societe-abc.sn',
                'phone' => '+221 70 111 22 33',
                'address' => '456 Avenue de la République',
                'postal_code' => 'BP 54321',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'ninea' => '987654321',
                'vat_number' => 'SN98765432101',
                'is_active' => true,
                'payment_terms' => 30,
            ],
            [
                'company_id' => $company->id,
                'type' => 'company',
                'name' => 'Entreprise XYZ',
                'contact_name' => 'Marie Martin',
                'email' => 'marie.martin@entreprise-xyz.sn',
                'phone' => '+221 76 555 66 77',
                'address' => '789 Rue de la Médina',
                'postal_code' => 'BP 11000',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'ninea' => '456789123',
                'vat_number' => 'SN45678912302',
                'is_active' => true,
                'payment_terms' => 45,
            ],
            [
                'company_id' => $company->id,
                'type' => 'individual',
                'name' => 'Pierre Durant',
                'contact_name' => '',
                'email' => 'pierre.durant@email.sn',
                'phone' => '+221 77 123 45 67',
                'address' => '10 Rue du Commerce',
                'postal_code' => 'BP 25000',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'is_active' => true,
                'payment_terms' => 30,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        // Get created clients
        $client1 = Client::where('name', 'Société ABC')->first();
        $client2 = Client::where('name', 'Entreprise XYZ')->first();
        $client3 = Client::where('name', 'Pierre Durant')->first();

        // Create sample invoices
        $invoices = [
            [
                'company_id' => $company->id,
                'client_id' => $client1->id,
                'type' => 'invoice',
                'status' => 'paid',
                'number' => 'FAC-000001',
                'issue_date' => now()->subDays(30),
                'due_date' => now()->addDays(30),
                'payment_terms' => 30,
                'notes' => 'Facture pour prestations de conseil',
                'subtotal' => 1500.00,
                'discount_amount' => 0,
                'tax_amount' => 300.00,
                'total' => 1800.00,
                'paid_amount' => 1800.00,
                'balance' => 0,
                'paid_at' => now()->subDays(15),
            ],
            [
                'company_id' => $company->id,
                'client_id' => $client2->id,
                'type' => 'invoice',
                'status' => 'sent',
                'number' => 'FAC-000002',
                'issue_date' => now()->subDays(15),
                'due_date' => now()->addDays(15),
                'payment_terms' => 30,
                'notes' => 'Facture pour développement web',
                'subtotal' => 2500.00,
                'discount_amount' => 250.00,
                'tax_amount' => 450.00,
                'total' => 2700.00,
                'paid_amount' => 0,
                'balance' => 2700.00,
            ],
            [
                'company_id' => $company->id,
                'client_id' => $client3->id,
                'type' => 'invoice',
                'status' => 'partial_paid',
                'number' => 'FAC-000003',
                'issue_date' => now()->subDays(10),
                'due_date' => now()->addDays(20),
                'payment_terms' => 30,
                'notes' => 'Facture pour maintenance',
                'subtotal' => 500.00,
                'discount_amount' => 0,
                'tax_amount' => 100.00,
                'total' => 600.00,
                'paid_amount' => 300.00,
                'balance' => 300.00,
            ],
        ];

        foreach ($invoices as $invoiceData) {
            $invoice = Document::create($invoiceData);

            // Create invoice items
            if ($invoice->number === 'FAC-000001') {
                DocumentItem::create([
                    'document_id' => $invoice->id,
                    'description' => 'Prestation de conseil',
                    'quantity' => 2,
                    'unit_price' => 750.00,
                    'discount' => 0,
                    'tax_rate' => 20,
                    'subtotal' => 1500.00,
                    'tax_amount' => 300.00,
                    'total' => 1800.00,
                    'sort_order' => 1,
                ]);
            } elseif ($invoice->number === 'FAC-000002') {
                DocumentItem::create([
                    'document_id' => $invoice->id,
                    'description' => 'Développement site web',
                    'quantity' => 1,
                    'unit_price' => 2000.00,
                    'discount' => 10,
                    'tax_rate' => 20,
                    'subtotal' => 1800.00,
                    'tax_amount' => 360.00,
                    'total' => 2160.00,
                    'sort_order' => 1,
                ]);
                DocumentItem::create([
                    'document_id' => $invoice->id,
                    'description' => 'Hébergement annuel',
                    'quantity' => 1,
                    'unit_price' => 500.00,
                    'discount' => 0,
                    'tax_rate' => 20,
                    'subtotal' => 500.00,
                    'tax_amount' => 100.00,
                    'total' => 600.00,
                    'sort_order' => 2,
                ]);
            } elseif ($invoice->number === 'FAC-000003') {
                DocumentItem::create([
                    'document_id' => $invoice->id,
                    'description' => 'Maintenance mensuelle',
                    'quantity' => 1,
                    'unit_price' => 500.00,
                    'discount' => 0,
                    'tax_rate' => 20,
                    'subtotal' => 500.00,
                    'tax_amount' => 100.00,
                    'total' => 600.00,
                    'sort_order' => 1,
                ]);
            }

            // Create payment for partial paid invoice
            if ($invoice->number === 'FAC-000003') {
                Payment::create([
                    'document_id' => $invoice->id,
                    'amount' => 300.00,
                    'payment_date' => now()->subDays(5),
                    'method' => 'bank_transfer',
                    'reference' => 'PAY-001',
                ]);
            }
        }

        // Create sample quotes
        $quotes = [
            [
                'company_id' => $company->id,
                'client_id' => $client1->id,
                'type' => 'quote',
                'status' => 'sent',
                'number' => 'DEV-000001',
                'issue_date' => now()->subDays(5),
                'due_date' => now()->addDays(30),
                'payment_terms' => 30,
                'notes' => 'Devis pour refonte du site web',
                'subtotal' => 3000.00,
                'discount_amount' => 0,
                'tax_amount' => 600.00,
                'total' => 3600.00,
                'paid_amount' => 0,
                'balance' => 3600.00,
            ],
            [
                'company_id' => $company->id,
                'client_id' => $client2->id,
                'type' => 'quote',
                'status' => 'viewed',
                'number' => 'DEV-000002',
                'issue_date' => now()->subDays(3),
                'due_date' => now()->addDays(27),
                'payment_terms' => 30,
                'notes' => 'Devis pour application mobile',
                'subtotal' => 5000.00,
                'discount_amount' => 500.00,
                'tax_amount' => 900.00,
                'total' => 5400.00,
                'paid_amount' => 0,
                'balance' => 5400.00,
            ],
        ];

        foreach ($quotes as $quoteData) {
            $quote = Document::create($quoteData);

            DocumentItem::create([
                'document_id' => $quote->id,
                'description' => 'Prestation de développement',
                'quantity' => 1,
                'unit_price' => $quoteData['subtotal'],
                'discount' => $quoteData['discount_amount'] > 0 ? 10 : 0,
                'tax_rate' => 20,
                'subtotal' => $quoteData['subtotal'] - $quoteData['discount_amount'],
                'tax_amount' => $quoteData['tax_amount'],
                'total' => $quoteData['total'],
                'sort_order' => 1,
            ]);
        }

        // Update company next numbers
        $company->update([
            'invoice_next_number' => 4,
            'quote_next_number' => 3,
        ]);
    }
}
