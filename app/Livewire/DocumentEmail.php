<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Document;
use App\Mail\SendDocument;
use Illuminate\Support\Facades\Mail;

class DocumentEmail extends Component
{
    public $showModal = false;
    public $document;

    // Form fields
    public $fromEmail = '';
    public $fromName = '';
    public $toEmail = '';
    public $ccEmail = '';
    public $bccEmail = '';
    public $subject = '';
    public $body = '';
    public $attachPdf = true;

    // States
    public $sending = false;
    public $sent = false;
    public $error = '';

    protected $rules = [
        'fromEmail' => 'required|email',
        'toEmail' => 'required|email',
        'ccEmail' => 'nullable|string',
        'bccEmail' => 'nullable|string',
        'subject' => 'required|string',
        'body' => 'required|string',
    ];

    protected $messages = [
        'fromEmail.required' => 'L’adresse de l’expéditeur est requise.',
        'fromEmail.email' => 'L’adresse de l’expéditeur doit être un email valide.',
        'toEmail.required' => 'L’adresse du destinataire est requise.',
        'toEmail.email' => 'L’adresse du destinataire doit être un email valide.',
        'subject.required' => 'L’objet du message est requis.',
        'body.required' => 'Le message est requis.',
    ];

    public function mount(Document $document)
    {
        $this->document = $document;
        $this->document = $document->loadMissing('company', 'client');
        $this->toEmail = $this->document->client?->email ?? '';

        // S'assurer que les valeurs de contexte existent (évite certains null offsets)
        $this->fromName = $this->document->company?->name ?? config('mail.from.name');

        // Default sender (from)
        // Pour dépendre du mail de l’entreprise : on utilise l’email de la company (si présent).
        // NOTE: Avec Gmail/Google SMTP, il faut que cette adresse soit autorisée (alias/Workspace) sinon ça échoue.
        $this->fromEmail = $this->document->company->email ?? config('mail.from.address');
        $this->fromName = $document->company->name ?? config('mail.from.name');



        $this->subject = $this->getDefaultSubject();
        $this->body = $this->getDefaultBody();
    }

    public function getDefaultSubject()
    {
        $companyName = $this->document->company->name ?? 'Votre entreprise';

        if ($this->document->type === 'quote') {
            return "Devis {$this->document->number} de {$companyName}";
        }

        return "Facture {$this->document->number} de {$companyName}";
    }

    public function getDefaultBody()
    {
        $companyName = $this->document->company->name ?? 'votre entreprise';

        if ($this->document->type === 'quote') {
            return "Bonjour,\n\nVeuillez trouver ci-joint le devis {$this->document->number} d'un montant de {$this->document->formatted_total}.\n\nCe devis est valable pendant 30 jours.\n\nN'hésitez pas à nous contacter pour toute question.\n\nCordialement,\n" . $companyName;
        }

        return "Bonjour,\n\nVeuillez trouver ci-joint la facture {$this->document->number} d'un montant de {$this->document->formatted_total}.\n\nMerci de procéder au paiement avant le {$this->document->due_date->format('d/m/Y')}.\n\nCordialement,\n" . $companyName;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->reset(['sending', 'sent', 'error']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['sending', 'sent', 'error']);
    }

    public function send()
    {
        $this->validate();

        $this->sending = true;
        $this->error = '';

        try {
            $fromEmail = filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL)
                ? $this->fromEmail
                : config('mail.from.address');
            $fromName = trim($this->fromName) ?: config('mail.from.name');

            $mailable = new SendDocument(
                $this->document,
                $this->subject,
                $this->body,
                $this->attachPdf,
                $fromEmail,
                $fromName
            );

            $ccEmails = $this->parseEmailList($this->ccEmail);
            $bccEmails = $this->parseEmailList($this->bccEmail);

            $mail = Mail::to($this->toEmail);
            if (!empty($ccEmails)) {
                $mail = $mail->cc($ccEmails);
            }
            if (!empty($bccEmails)) {
                $mail = $mail->bcc($bccEmails);
            }

            // Force a known-good address for From (gmail). User-entered "fromEmail" is used as Reply-To.
            $mail->send($mailable);


            $this->sent = true;
            $this->sending = false;

            // Keep the modal open until the user closes it manually,
            // but reset the error/sending state.
            $this->error = '';

        } catch (\Exception $e) {
            $this->error = 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage();
            $this->sending = false;
        }
    }

    protected function parseEmailList(?string $emails): array
    {
        if (!$emails) {
            return [];
        }

        $list = array_map('trim', explode(',', $emails));

        return array_filter($list, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    }

    protected function getListeners()
    {
        return [
            'open-email-modal' => 'openModal',
            'openModal' => 'openModal',
        ];
    }
}

