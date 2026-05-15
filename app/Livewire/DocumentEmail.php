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
        'subject' => 'required|string',
        'body' => 'required|string',
    ];

    public function mount(Document $document)
    {
        $this->document = $document;
        $this->toEmail = $document->client->email ?? '';

        // Default sender (from) - prefer company email, fallback to authenticated user, then config
        $this->fromEmail = $document->company->email ?? auth()->user()->email ?? config('mail.from.address');
        $this->fromName = $document->company->name ?? auth()->user()->name ?? config('mail.from.name');

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
            $mailable = new SendDocument(
                $this->document,
                $this->subject,
                $this->body,
                $this->attachPdf,
                $this->fromEmail,
                $this->fromName
            );

            // Parse CC and BCC emails
            $ccEmails = [];
            $bccEmails = [];

            if ($this->ccEmail) {
                $ccEmails = array_map('trim', explode(',', $this->ccEmail));
                $ccEmails = array_filter($ccEmails, function ($email) {
                    return filter_var($email, FILTER_VALIDATE_EMAIL);
                });
            }

            if ($this->bccEmail) {
                $bccEmails = array_map('trim', explode(',', $this->bccEmail));
                $bccEmails = array_filter($bccEmails, function ($email) {
                    return filter_var($email, FILTER_VALIDATE_EMAIL);
                });
            }

            Mail::to($this->toEmail)
                ->cc($ccEmails)
                ->bcc($bccEmails)
                ->send($mailable);

            $this->sent = true;
            $this->sending = false;

            // Close modal after a delay
            $this->dispatch('close-email-modal');

        } catch (\Exception $e) {
            $this->error = 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage();
            $this->sending = false;
        }
    }

    protected function getListeners()
    {
        return [
            'open-email-modal' => 'openModal',
        ];
    }
}

