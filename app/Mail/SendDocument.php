<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendDocument extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The document instance
     */
    public $document;

    /**
     * Custom subject
     */
    public $subject;

    /**
     * Custom body message
     */
    public $body;

    /**
     * Whether to attach PDF
     */
    public $attachPdf;

    /**
     * From address (sender)
     */
    public $fromEmail;

    /**
     * From name (sender)
     */
    public $fromName;

    /**
     * Create a new message instance.
     */
    public function __construct(Document $document, $subject = null, $body = null, $attachPdf = true, $fromEmail = null, $fromName = null)
    {
        $this->document = $document;
        $this->subject = $subject ?? $this->getDefaultSubject();
        $this->body = $body ?? $this->getDefaultBody();
        $this->attachPdf = $attachPdf;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $defaultEmail = config('mail.from.address');
        $defaultName = config('mail.from.name');

        $fromEmail = $this->fromEmail && filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL)
            ? $this->fromEmail
            : $defaultEmail;

        $fromName = trim($this->fromName) ?: $defaultName;

        $replyTo = null;

        // On respecte désormais l'email de l'entreprise (si valide) comme From.
        // On conserve toutefois un Reply-To quand l'email de l'entreprise diffère de l'email global.
        if ($fromEmail !== $defaultEmail) {
            $replyTo = [new Address($fromEmail, $fromName)];
        }

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: $this->subject,
            replyTo: $replyTo,
        );


    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.document',
            with: [
                'document' => $this->document,
                'company' => $this->document->company,
                'client' => $this->document->client,
                'subject' => $this->subject,
                'body' => $this->body,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->attachPdf) {
            // Generate PDF on the fly
            $pdfAction = new \App\Actions\GenerateDocumentPDF();
            $pdf = $pdfAction->generate($this->document);

            $filename = $this->document->type === 'quote' ? 'Devis-' : 'Facture-';
            $filename .= $this->document->number . '.pdf';

            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromData(
                fn() => $pdf->output(),
                $filename
            )->withMime('application/pdf');
        }

        return $attachments;
    }

    /**
     * Get default email subject based on document type
     */
    protected function getDefaultSubject()
    {
        $companyName = $this->document->company->name ?? 'Votre entreprise';

        if ($this->document->type === 'quote') {
            return "Devis {$this->document->number} de {$companyName}";
        }

        return "Facture {$this->document->number} de {$companyName}";
    }

    /**
     * Get default email body
     */
    protected function getDefaultBody()
    {
        $companyName = $this->document->company->name ?? 'Votre entreprise';

        if ($this->document->type === 'quote') {
            return "Bonjour,\n\n" .
                "Veuillez trouver ci-joint le devis {$this->document->number} d'un montant de {$this->document->formatted_total}.\n\n" .
                "Ce devis est valable pendant 30 jours.\n\n" .
                "N'hésitez pas à nous contacter pour toute question.\n\n" .
                "Cordialement,\n" . $companyName;
        }

        return "Bonjour,\n\n" .
            "Veuillez trouver ci-joint la facture {$this->document->number} d'un montant de {$this->document->formatted_total}.\n\n" .
            "Merci de procéder au paiement avant le {$this->document->due_date->format('d/m/Y')}.\n\n" .
            "Cordialement,\n" . $companyName;
    }
}

