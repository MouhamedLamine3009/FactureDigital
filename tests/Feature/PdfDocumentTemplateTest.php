<?php

namespace Tests\Feature;

use App\Actions\GenerateDocumentPDF;
use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PdfDocumentTemplateTest extends TestCase
{
    use RefreshDatabase;

    private function makeCompany(array $overrides = []): Company
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        return Company::create(array_merge([
            'user_id' => $user->id,
            'name' => 'MOUHAMED LAMINE SENE',
            'legal_name' => 'MOUHAMED LAMINE SENE - ENTREPRISE',
            'ninea' => '123456789',
            'rccm' => 'RC/DKR/2024/123456',
            'address' => '123 Rue de la Paix',
            'postal_code' => 'BP 12345',
            'city' => 'Dakar',
            'country' => 'Sénégal',
            'phone' => '+221 77 123 45 67',
            'email' => 'contact@mlsene.sn',
            'vat_number' => 'SN12345678901',
            'currency' => 'XOF',
            'currency_symbol' => 'FCFA',
            'is_current' => true,
        ], $overrides));
    }

    private function makeClient(Company $company, array $overrides = []): Client
    {
        return Client::create(array_merge([
            'company_id' => $company->id,
            'type' => 'company',
            'name' => 'Client Test',
            'email' => 'client@example.com',
            'phone' => '+221 70 222 33 44',
            'address' => '456 Avenue de l’Indépendance',
            'postal_code' => 'BP 54321',
            'city' => 'Dakar',
            'country' => 'Sénégal',
        ], $overrides));
    }

    private function makeDocument(Company $company, Client $client, array $overrides = []): Document
    {
        return Document::create(array_merge([
            'company_id' => $company->id,
            'client_id' => $client->id,
            'type' => 'invoice',
            'status' => 'sent',
            'number' => 'FACT-000004',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(15)->toDateString(),
            'subtotal' => 1000,
            'discount_amount' => 0,
            'tax_amount' => 200,
            'total' => 1200,
            'paid_amount' => 0,
            'balance' => 1200,
        ], $overrides));
    }

    private function renderHtml(Document $document): string
    {
        $document->load(['company', 'client', 'items']);

        return view('pdfs.document', (new GenerateDocumentPDF)->viewData($document))->render();
    }

    /**
     * Visible markup only: strip <head> so PDF metadata (the <title> echo of
     * the document number) is not counted as a rendered duplicate.
     */
    private function visibleHtml(Document $document): string
    {
        $html = $this->renderHtml($document);

        return preg_replace('#<head>.*?</head>#si', '', $html) ?? $html;
    }

    public function test_invoice_pdf_has_visible_header_and_company_contact_details(): void
    {
        $company = $this->makeCompany();
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        DocumentItem::create([
            'document_id' => $document->id,
            'description' => 'Forfait de développement',
            'quantity' => 1,
            'unit_price' => 1000,
            'discount' => 0,
            'sort_order' => 1,
        ]);

        $html = $this->renderHtml($document);

        $this->assertStringContainsString('FACTURE', $html);
        $this->assertStringContainsString('FACT-000004', $html);
        $this->assertStringContainsString('MOUHAMED LAMINE SENE', $html);
        $this->assertStringContainsString('NINEA', $html);
        $this->assertStringContainsString('RC', $html);
        $this->assertStringContainsString('contact@mlsene.sn', $html);
        $this->assertStringNotContainsString('bon pour accord', strtolower($html));
        $this->assertStringNotContainsString('signature suivie de la', strtolower($html));
    }

    public function test_quote_pdf_shows_quote_signature_and_marks_title_as_devis(): void
    {
        $company = $this->makeCompany(['name' => 'Entreprise Demo', 'legal_name' => 'Entreprise Demo SARL', 'email' => 'info@demo.sn']);
        $client = $this->makeClient($company, ['name' => 'Client devis', 'email' => 'devis@example.com', 'city' => 'Thiès']);
        $document = $this->makeDocument($company, $client, [
            'type' => 'quote',
            'number' => 'DEV-000101',
            'notes' => 'Ce devis inclut le développement complet du projet.',
            'terms_conditions' => 'Ce devis est valable 1 mois à compter de sa date d’émission.',
            'subtotal' => 800,
            'tax_amount' => 160,
            'total' => 960,
            'balance' => 960,
        ]);

        DocumentItem::create([
            'document_id' => $document->id,
            'description' => 'Audit et conception',
            'quantity' => 1,
            'unit_price' => 800,
            'discount' => 0,
            'sort_order' => 1,
        ]);

        $html = $this->renderHtml($document);

        $this->assertStringContainsString('DEVIS', $html);
        $this->assertStringContainsString('DEV-000101', $html);
        $this->assertStringContainsString('Ce devis est valable', $html);
        $this->assertStringContainsString('bon pour accord', strtolower($html));
        $this->assertStringContainsString('signature suivie de la', strtolower($html));
    }

    public function test_company_name_and_document_number_appear_only_once(): void
    {
        $company = $this->makeCompany(['footer_notes' => null]);
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);
        DocumentItem::create([
            'document_id' => $document->id,
            'description' => 'Forfait',
            'quantity' => 1,
            'unit_price' => 1000,
            'discount' => 0,
            'sort_order' => 1,
        ]);

        $html = $this->visibleHtml($document);

        $this->assertSame(1, substr_count($html, 'MOUHAMED LAMINE SENE - ENTREPRISE'),
            'The company name must be printed exactly once.');
        $this->assertSame(1, substr_count($html, 'FACT-000004'),
            'The document number must be printed exactly once.');
    }

    public function test_header_uses_no_flexbox_grid_or_fixed_height(): void
    {
        $company = $this->makeCompany();
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $html = $this->renderHtml($document);

        $this->assertStringNotContainsString('display: flex', $html);
        $this->assertStringNotContainsString('display:flex', $html);
        $this->assertStringNotContainsString('display: grid', $html);
        $this->assertStringNotContainsString('display:grid', $html);
        // The banner must size itself from its content, never a fixed height
        // combined with clipping.
        $this->assertStringNotContainsString('overflow: hidden', $html);
        $this->assertStringNotContainsString('overflow:hidden', $html);
        $this->assertDoesNotMatchRegularExpression('/\.band-cell\s*\{[^}]*height:\s*\d/', $html);
        $this->assertDoesNotMatchRegularExpression('/\.header\s*\{[^}]*height:\s*\d/', $html);
        $this->assertStringContainsString('class="band"', $html);
    }

    public function test_title_never_uses_a_light_font_weight_that_falls_back_to_serif(): void
    {
        $company = $this->makeCompany();
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $html = $this->renderHtml($document);

        // DomPDF has no light face: any weight < 400 makes it fall back to
        // `default_font = serif` (Times), which is the bug being fixed.
        $this->assertDoesNotMatchRegularExpression('/font-weight:\s*(1|2|300)\b/', $html);
        $this->assertStringContainsString('font-family: "DejaVu Sans";', $html);
    }

    public function test_footer_notes_from_company_settings_are_rendered_in_a_fixed_footer(): void
    {
        $company = $this->makeCompany(['footer_notes' => 'Merci pour votre confiance !']);
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $html = $this->renderHtml($document);

        $this->assertStringContainsString('Merci pour votre confiance !', $html);
        $this->assertStringContainsString('class="footer"', $html);
        $this->assertMatchesRegularExpression('/\.footer\s*\{[^}]*position:\s*fixed/', $html);
        $this->assertMatchesRegularExpression('/\.footer\s*\{[^}]*bottom:\s*0/', $html);
        // A bottom page margin must be reserved so the footer never overlaps.
        $this->assertMatchesRegularExpression('/@page\s*\{[^}]*margin-bottom|@page\s*\{[^}]*margin:\s*0\s+20mm\s+\d+mm\s+20mm/', $html);
    }

    public function test_no_footer_block_is_rendered_when_footer_notes_are_empty(): void
    {
        $company = $this->makeCompany(['footer_notes' => null]);
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $html = $this->renderHtml($document);

        $this->assertStringNotContainsString('class="footer"', $html);
        $this->assertStringNotContainsString('class="footer-text"', $html);
    }

    public function test_blank_company_fields_are_masked_instead_of_leaving_empty_lines(): void
    {
        $company = $this->makeCompany([
            'address' => null,
            'postal_code' => null,
            'city' => null,
            'country' => 'Sénégal',
            'phone' => null,
            'email' => null,
            'ninea' => null,
            'rccm' => null,
            'footer_notes' => null,
        ]);
        $client = $this->makeClient($company, [
            'email' => null, 'phone' => null, 'address' => null, 'postal_code' => null,
        ]);
        $document = $this->makeDocument($company, $client);

        $html = $this->renderHtml($document);

        $this->assertStringNotContainsString('NINEA', $html);
        $this->assertStringNotContainsString('RC :', $html);
        $this->assertStringNotContainsString('Tél.', $html);
        // No dangling separators from the old NINEA | RC concatenation
        $this->assertStringNotContainsString('&nbsp;|&nbsp;', $html);
    }

    public function test_logo_is_embedded_from_an_absolute_disk_path_not_the_storage_symlink(): void
    {
        Storage::disk('public')->put('logos/marque.png', 'fake-png-bytes');

        $company = $this->makeCompany(['logo_path' => 'logos/marque.png']);
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $data = (new GenerateDocumentPDF)->viewData($document);
        $html = view('pdfs.document', $data)->render();

        // Absolute path under storage/app/public, not public/storage/... which
        // does not exist when `php artisan storage:link` has not been run.
        $expected = Storage::disk('public')->path('logos/marque.png');
        $this->assertTrue($data['logoFile'] !== null);
        $this->assertSame(realpath($expected), realpath((string) $data['logoFile']));
        $this->assertStringContainsString($data['logoFile'], $html);
        $this->assertStringNotContainsString("public/storage/logos/marque.png", $html);
    }

    public function test_company_without_a_logo_falls_back_to_initials_in_the_band(): void
    {
        $company = $this->makeCompany(['logo_path' => null, 'name' => 'Sene Entreprise']);
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $data = (new GenerateDocumentPDF)->viewData($document);

        $this->assertNull($data['logoFile']);
        $this->assertStringContainsString('SE', $this->renderHtml($document));
    }

    public function test_missing_logo_file_falls_back_to_initials_instead_of_a_broken_image(): void
    {
        $company = $this->makeCompany(['logo_path' => 'logos/does-not-exist.png']);
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client);

        $data = (new GenerateDocumentPDF)->viewData($document);
        $html = view('pdfs.document', $data)->render();

        $this->assertNull($data['logoFile']);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_paid_invoice_shows_settled_banner_and_remaining_balance(): void
    {
        $company = $this->makeCompany();
        $client = $this->makeClient($company);
        $document = $this->makeDocument($company, $client, [
            'paid_amount' => 500,
            'balance' => 700,
        ]);

        $html = $this->renderHtml($document);

        $this->assertStringContainsString('Soldé', $html);
        $this->assertStringContainsString('Reste à payer', $html);
    }
}
