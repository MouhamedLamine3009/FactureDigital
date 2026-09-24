<?php

namespace App\Livewire;

use App\Models\Document;
use App\Services\WhatsApp\WhatsAppLinkService;
use App\Services\WhatsApp\MetaWhatsAppCloud;
use Livewire\Component;
use RuntimeException;

class DocumentWhatsapp extends Component
{
    protected $listeners = [
        'open-whatsapp-modal' => 'openModal',
    ];

    public bool $showModal = false;
    public Document $document;

    public string $toPhone = '';
    public string $body = '';

    public bool $sending = false;
    public bool $sent = false;
    public string $error = '';

    public bool $metaConfigured = false;

    public function mount(Document $document): void
    {
        $this->document = $document->loadMissing(['company', 'client']);
        $this->toPhone = $this->document->client?->phone ?? '';
        $this->body = $this->getDefaultBody();
        $this->metaConfigured = $this->isMetaConfigured();
    }

    public function openModal(): void
    {
        $this->showModal = true;
        $this->reset(['sending', 'sent', 'error']);

        if (!$this->toPhone) {
            $this->toPhone = $this->document->client?->phone ?? '';
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['sending', 'sent', 'error']);
    }

    // ============================================================
    //  MÉTHODE 1 : Lien d'action rapide wa.me (Recommandée)
    // ============================================================

    public function getWaMeLink(): string
    {
        $service = new WhatsAppLinkService();
        return $service->generateLink($this->document);
    }

    public function openWhatsApp(): void
    {
        $this->validate([
            'toPhone' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        $phone = app(WhatsAppLinkService::class)->formatPhoneNumber($this->toPhone);
        $message = $this->buildFullMessage();

        $url = "https://wa.me/{$phone}?text=" . urlencode($message);

        $this->dispatch('open-whatsapp-link', url: $url);

        $this->closeModal();

        $this->markAsSentIfDraft();
    }

    // ============================================================
    //  METHODE 2 : Envoi via API Meta WhatsApp Cloud (Automatique)
    // ============================================================

    public function sendViaApi(): void
    {
        $this->validate([
            'toPhone' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        $this->sending = true;
        $this->sent = false;
        $this->error = '';

        try {
            if (!$this->metaConfigured) {
                throw new RuntimeException(
                    'Meta WhatsApp Cloud API non configurée. '
                    . 'Ajoutez META_WHATSAPP_ACCESS_TOKEN et META_WHATSAPP_PHONE_NUMBER_ID dans le fichier .env '
                    . 'pour utiliser l\'envoi automatique.'
                );
            }

            $message = $this->buildFullMessage();

            $meta = new MetaWhatsAppCloud();
            $meta->sendTextMessage($this->toPhone, $message);

            $this->sent = true;
            $this->sending = false;

            $this->markAsSentIfDraft();

            $this->dispatch('notify', type: 'success', message: 'Message WhatsApp envoyé avec succès.');
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->sending = false;
        }
    }

    // ============================================================
    //  Méthodes privées / helpers
    // ============================================================

    protected function buildFullMessage(): string
    {
        $pdfLink = route('documents.view-pdf', $this->document);
        return $this->body . "\n\n" . $pdfLink;
    }

    protected function getDefaultBody(): string
    {
        $companyName = $this->document->company?->name ?? 'Votre entreprise';
        $clientName  = $this->document->client?->name ?? '';
        $docType     = $this->document->type === 'quote' ? 'Devis' : 'Facture';
        $total       = $this->formatFCFA($this->document->total);

        $greeting = $clientName ? "Bonjour {$clientName}," : "Bonjour,";

        return "{$greeting}\n\n"
            . "Veuillez trouver votre {$docType} {$this->document->number} d'un montant de {$total}.\n\n"
            . "Cordialement,\n{$companyName}";
    }

    protected function formatFCFA(float $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }

    protected function isMetaConfigured(): bool
    {
        return !empty(config('services.meta_whatsapp.access_token'))
            && !empty(config('services.meta_whatsapp.phone_number_id'));
    }

    protected function markAsSentIfDraft(): void
    {
        if ($this->document->status === 'draft') {
            $this->document->markAsSent();
            $this->document->refresh();
        }
    }

    public function render()
    {
        return view('livewire.document-whatsapp', [
            'document' => $this->document,
        ]);
    }
}

