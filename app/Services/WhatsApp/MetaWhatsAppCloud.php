<?php

namespace App\Services\WhatsApp;

use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Service WhatsApp - Méthode 2 (API Meta WhatsApp Cloud)
 * 
 * Envoie un message WhatsApp via l'API officielle Meta WhatsApp Cloud.
 * Nécessite un Access Token et un Phone Number ID configurés dans le .env.
 * 
 * Documentation : https://developers.facebook.com/docs/whatsapp/cloud-api
 */
class MetaWhatsAppCloud
{
    /**
     * URL de base de l'API Meta WhatsApp Cloud.
     */
    protected string $baseUrl;

    /**
     * Version de l'API Meta.
     */
    protected string $apiVersion;

    /**
     * Token d'acces pour l'authentification.
     */
    protected ?string $accessToken;

    /**
     * Identifiant du numéro de téléphone expéditeur.
     */
    protected ?string $phoneNumberId;

    public function __construct()
    {
        $this->baseUrl      = rtrim(config('services.meta_whatsapp.base_url', 'https://graph.facebook.com'), '/');
        $this->apiVersion   = config('services.meta_whatsapp.api_version', 'v22.0');
        $this->accessToken  = config('services.meta_whatsapp.access_token');
        $this->phoneNumberId = config('services.meta_whatsapp.phone_number_id');
    }

    /**
     * Envoie un message texte simple via l'API Meta WhatsApp Cloud.
     *
     * @param string $toPhone Numéro du destinataire au format international (ex: 221771234567)
     * @param string $message Contenu du message
     * @return array Réponse de l'API
     * @throws RuntimeException
     */
    public function sendTextMessage(string $toPhone, string $message): array
    {
        $this->validateConfiguration();

        $toPhone = $this->formatPhoneNumber($toPhone);

        $url = sprintf(
            '%s/%s/%s/messages',
            $this->baseUrl,
            $this->apiVersion,
            $this->phoneNumberId
        );

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toPhone,
            'type'              => 'text',
            'text'              => [
                'preview_url' => true,
                'body'        => $message,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type'  => 'application/json',
        ])->post($url, $payload);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Meta WhatsApp Cloud API Error', [
                'status'   => $response->status(),
                'response' => $errorBody,
                'to'       => $toPhone,
            ]);

            throw new RuntimeException(
                'Erreur lors de l\'envoi du message WhatsApp : ' . $this->extractErrorMessage($response->json())
            );
        }

        Log::info('Message WhatsApp envoyé avec succès', [
            'to'   => $toPhone,
            'wamid' => $response->json('messages.0.id') ?? 'N/A',
        ]);

        return $response->json();
    }

    /**
     * Envoie un document (devis/facture) avec message personnalisé.
     *
     * @param Document $document
     * @param string $toPhone
     * @param string|null $customMessage Message optionnel
     * @return array
     */
    public function sendDocument(Document $document, string $toPhone, ?string $customMessage = null): array
    {
        $this->validateConfiguration();

        $document->loadMissing(['company', 'client']);
        $toPhone = $this->formatPhoneNumber($toPhone);

        $message = $customMessage ?: $this->buildDefaultMessage($document);

        $url = sprintf(
            '%s/%s/%s/messages',
            $this->baseUrl,
            $this->apiVersion,
            $this->phoneNumberId
        );

        // Construction du message avec document (lien)
        // Note: Meta API ne permet pas d'envoyer un fichier local directement.
        // On envoie un message texte avec le lien de consultation PDF.
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toPhone,
            'type'              => 'text',
            'text'              => [
                'preview_url' => true,
                'body'        => $message . "\n\n" . route('documents.view-pdf', $document),
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type'  => 'application/json',
        ])->post($url, $payload);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Meta WhatsApp Cloud API Error (sendDocument)', [
                'status'   => $response->status(),
                'response' => $errorBody,
                'document' => $document->number,
            ]);

            throw new RuntimeException(
                'Erreur lors de l\'envoi du document par WhatsApp : ' . $this->extractErrorMessage($response->json())
            );
        }

        return $response->json();
    }

    /**
     * Envoie un message avec un template approuvé (si vous avez des templates Meta Business).
     *
     * @param string $toPhone
     * @param string $templateName Nom du template
     * @param array $parameters Paramètres du template
     * @return array
     */
    public function sendTemplate(string $toPhone, string $templateName, array $parameters = []): array
    {
        $this->validateConfiguration();

        $toPhone = $this->formatPhoneNumber($toPhone);

        $url = sprintf(
            '%s/%s/%s/messages',
            $this->baseUrl,
            $this->apiVersion,
            $this->phoneNumberId
        );

        $components = [];

        if (!empty($parameters)) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(function ($param) {
                    return ['type' => 'text', 'text' => $param];
                }, $parameters),
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toPhone,
            'type'              => 'template',
            'template'          => [
                'name'       => $templateName,
                'language'   => [
                    'code' => 'fr',
                ],
                'components' => $components,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type'  => 'application/json',
        ])->post($url, $payload);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Meta WhatsApp Cloud API Error (sendTemplate)', [
                'status'   => $response->status(),
                'response' => $errorBody,
            ]);

            throw new RuntimeException(
                'Erreur lors de l\'envoi du template WhatsApp : ' . $this->extractErrorMessage($response->json())
            );
        }

        return $response->json();
    }

    /**
     * Construit le message par défaut pour un document.
     */
    protected function buildDefaultMessage(Document $document): string
    {
        $companyName = $document->company?->name ?? 'Votre entreprise';
        $clientName  = $document->client?->name ?? 'Cher client';
        $docType     = $document->type === 'quote' ? 'Devis' : 'Facture';
        $totalFCFA   = number_format($document->total, 0, ',', ' ') . ' FCFA';

        return "Bonjour {$clientName},\n"
            . "Veuillez trouver votre {$docType} {$document->number} d'un montant de {$totalFCFA}.\n\n"
            . "{$companyName}";
    }

    /**
     * Valide que la configuration Meta est présente.
     *
     * @throws RuntimeException
     */
    protected function validateConfiguration(): void
    {
        if (empty($this->accessToken)) {
            throw new RuntimeException(
                'Meta WhatsApp Cloud API non configurée. '
                . 'Ajoutez META_WHATSAPP_ACCESS_TOKEN dans le fichier .env.'
            );
        }

        if (empty($this->phoneNumberId)) {
            throw new RuntimeException(
                'Meta WhatsApp Cloud API non configurée. '
                . 'Ajoutez META_WHATSAPP_PHONE_NUMBER_ID dans le fichier .env.'
            );
        }
    }

    /**
     * Normalise un numéro de téléphone au format international (sans le +).
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Supprime 'whatsapp:' si présent
        $phone = preg_replace('/^whatsapp:/i', '', $phone);

        // Supprime tout ce qui n'est pas un chiffre
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Supprime les zéros au début
        $phone = ltrim($phone, '0');

        return $phone;
    }

    /**
     * Extrait le message d'erreur de la réponse JSON de l'API Meta.
     */
    protected function extractErrorMessage(?array $responseJson): string
    {
        if (isset($responseJson['error']['message'])) {
            $code = $responseJson['error']['code'] ?? '';
            return "{$responseJson['error']['message']} (Code: {$code})";
        }

        if (isset($responseJson['message'])) {
            return $responseJson['message'];
        }

        return 'Erreur inconnue lors de la communication avec l\'API Meta WhatsApp.';
    }
}

