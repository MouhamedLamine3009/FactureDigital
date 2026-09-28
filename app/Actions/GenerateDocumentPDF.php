<?php

namespace App\Actions;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateDocumentPDF
{
    /**
     * Generate PDF for a document
     *
     * @param Document $document
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generate(Document $document)
    {
        $document->load(['company', 'client', 'items']);

        $pdf = PDF::loadView('pdfs.document', $this->viewData($document));

        // Configure PDF
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Build every value the Blade layout needs, so the template stays purely
     * presentational (DomPDF only understands tables, not PHP logic).
     *
     * @return array<string, mixed>
     */
    public function viewData(Document $document): array
    {
        $company = $document->company;
        $client = $document->client;
        $isQuote = $document->type === Document::TYPE_QUOTE;

        $companyLabel = $company->legal_name ?: $company->name ?: config('app.name');
        $clientLabel = $client->name ?: 'Client';

        return [
            'document' => $document,
            'company' => $company,
            'client' => $client,
            'items' => $document->items,
            'palette' => $this->palette(),

            'isQuote' => $isQuote,
            'title' => $isQuote ? 'DEVIS' : 'FACTURE',
            'issueDate' => $document->issue_date?->format('d/m/Y') ?? '',
            'dueDate' => $document->due_date?->format('d/m/Y'),

            'companyLabel' => $companyLabel,
            'companyInitials' => $this->initials($companyLabel),
            'logoFile' => $this->logoFile($company),
            'signatureFile' => $this->signatureFile($company),
            'issuerLines' => $this->issuerLines($company, $clientLabel),
            'clientLines' => $this->clientLines($client),
            'paymentLines' => $this->paymentLines($company),

            'itemFontSize' => $this->itemFontSize($document->items->count()),
            'termsText' => $this->termsText($document, $isQuote),

            'footerText' => trim((string) ($company->footer_notes ?? '')),
        ];
    }

    /**
     * Palette du document. Aucune police "exotique" n'est utilisée : DejaVu
     * Sans (normal + gras) est la seule police livrée avec DomPDF, et
     * `font-weight: 300` ferait retomber DomPDF sur `default_font = serif`
     * (Times), ce qui est exactement le bug corrigé ici.
     */
    protected function palette(): array
    {
        return [
            'primary' => '#1d4ed8',
            'accent' => '#2563eb',
            'ink' => '#171923',
            'muted' => '#5b6472',
            'line' => '#d8dde5',
        ];
    }

    /**
     * Initiales du logo : on réutilise le helper global `initials()`
     * (App\Support\Initials) déjà utilisé par User, client-list et
     * document-form, plutôt que de réimplémenter une autre variante ici.
     */
    protected function initials(string $name): string
    {
        return initials($name) ?: mb_strtoupper(mb_substr(trim($name) ?: 'D', 0, 1));
    }

    /**
     * Chemin absolu d'un fichier du disque `public`, ou null si absent.
     *
     * DomPDF a besoin d'un vrai chemin disque : on interroge donc le disque
     * `public` directement plutôt que `public_path('storage/...')`, qui casse
     * quand `php artisan storage:link` n'a pas été exécuté (cas courant sous
     * Windows/XAMPP où `public/storage` est un simple dossier vide).
     * Si le fichier manque, le template retombe sur une alternative propre.
     */
    protected function publicFile(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $file = Storage::disk('public')->path($path);

        return is_file($file) && is_readable($file) ? $file : null;
    }

    protected function logoFile($company): ?string
    {
        return $this->publicFile($company->logo_path);
    }

    protected function signatureFile($company): ?string
    {
        return $this->publicFile($company->signature_path);
    }

    /**
     * Coordonnées de l'émetteur, champs vides simplement omis.
     *
     * @return array<int, string>
     */
    protected function issuerLines($company, string $clientName): array
    {
        $lines = [];

        $address = $this->addressLines($company);
        if ($address !== []) {
            $lines = array_merge($lines, $address);
        }
        if ($company->phone) {
            $lines[] = 'Tél. ' . $company->phone;
        }
        if ($company->email) {
            $lines[] = $company->email;
        }
        if ($company->website) {
            $lines[] = $company->website;
        }

        $legal = [];
        if ($company->ninea) {
            $legal[] = 'NINEA : ' . $company->ninea;
        }
        if ($company->rccm) {
            $legal[] = 'RC : ' . $company->rccm;
        }
        if ($legal !== []) {
            $lines[] = implode('  |  ', $legal);
        }

        if ($lines === [] && $clientName !== '') {
            $lines[] = 'Aucune coordonnée renseignée';
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    protected function clientLines($client): array
    {
        $lines = [];

        if ($client->contact_name) {
            $lines[] = 'À l\'attention de : ' . $client->contact_name;
        }
        $lines = array_merge($lines, $this->addressLines($client));
        if ($client->phone) {
            $lines[] = $client->phone;
        }
        if ($client->email) {
            $lines[] = $client->email;
        }

        $legal = [];
        if ($client->ninea) {
            $legal[] = 'NINEA : ' . $client->ninea;
        }
        if ($client->vat_number) {
            $legal[] = 'TVA : ' . $client->vat_number;
        }
        if ($legal !== []) {
            $lines[] = implode('  |  ', $legal);
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    protected function paymentLines($company): array
    {
        $lines = [];

        if ($company->bank_name) {
            $lines[] = $company->bank_name;
        }
        if ($company->bank_account_name) {
            $lines[] = 'Titulaire : ' . $company->bank_account_name;
        }
        if ($company->bank_account_number) {
            $lines[] = 'Compte : ' . $company->bank_account_number;
        }
        if ($company->bank_iban) {
            $lines[] = 'IBAN : ' . $company->bank_iban;
        }
        if ($company->bank_bic) {
            $lines[] = 'BIC : ' . $company->bank_bic;
        }

        if ($lines === [] && $company->phone) {
            $lines[] = 'Règlement par téléphone : ' . $company->phone;
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    protected function addressLines($party): array
    {
        $lines = [];

        if ($party->address) {
            $lines[] = $this->oneLine($party->address);
        }
        $locality = trim(($party->postal_code ?? '') . ' ' . ($party->city ?? ''));
        if ($locality !== '') {
            $lines[] = $locality;
        }
        if ($party->country) {
            $lines[] = $party->country;
        }

        return $lines;
    }

    protected function oneLine(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    protected function itemFontSize(int $count): float
    {
        return match (true) {
            $count > 18 => 7.0,
            $count > 12 => 7.5,
            $count > 7 => 8.0,
            default => 8.5,
        };
    }

    protected function termsText(Document $document, bool $isQuote): string
    {
        if ($document->terms_conditions) {
            return $this->oneLine($document->terms_conditions);
        }

        $companyTerms = $this->oneLine((string) ($document->company->terms_conditions ?? ''));
        if ($companyTerms !== '') {
            return $companyTerms;
        }

        return $isQuote
            ? 'Ce devis est valable 1 mois à compter de sa date d’émission.'
            : 'Merci de procéder au règlement à la date d’échéance indiquée.';
    }

    /**
     * Generate and save PDF to storage
     *
     * @param Document $document
     * @return string Path to saved PDF
     */
    public function generateAndSave(Document $document)
    {
        $pdf = $this->generate($document);

        // Create directory if not exists
        $directory = 'documents/' . $document->company_id;
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Generate filename
        $filename = $document->number . '.pdf';
        $path = $directory . '/' . $filename;

        // Save PDF
        Storage::put($path, $pdf->output());

        // Update document with PDF path
        $document->update(['pdf_path' => $path]);

        return $path;
    }

    /**
     * Download PDF
     *
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Document $document)
    {
        $pdf = $this->generate($document);

        $filename = $document->type === 'quote' ? 'Devis-' : 'Facture-';
        $filename .= $document->number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Stream PDF to browser
     *
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream(Document $document)
    {
        $pdf = $this->generate($document);

        $filename = $document->type === 'quote' ? 'Devis-' : 'Facture-';
        $filename .= $document->number . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Get PDF content as string
     *
     * @param Document $document
     * @return string
     */
    public function getContent(Document $document)
    {
        $pdf = $this->generate($document);
        return $pdf->output();
    }
}

