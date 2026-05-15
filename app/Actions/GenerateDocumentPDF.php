<?php

namespace App\Actions;

use App\Models\Document;
use Illuminate\Support\Facades\View;
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

        $pdf = PDF::loadView('pdfs.document', [
            'document' => $document,
            'company' => $document->company,
            'client' => $document->client,
            'items' => $document->items,
        ]);

        // Configure PDF
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
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

