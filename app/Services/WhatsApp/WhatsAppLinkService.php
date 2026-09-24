<?php

namespace App\Services\WhatsApp;

use App\Models\Document;

/**
 * Service WhatsApp - Méthode 1 (Recommandée et Simple)
 * 
 * Génère un lien d'action rapide https://wa.me/ pré-rempli avec :
 * - Le nom du client
 * - Le numéro du devis/facture
 * - Le montant total TTC en FCFA
 * - Un lien sécurisé pour consulter/télécharger le PDF
 * 
 * L'utilisateur clique sur le lien, WhatsApp s'ouvre, il confirme l'envoi.
 * Aucune clé API ni configuration complexe requise.
 */
class WhatsAppLinkService
{
    /**
     * Génère un lien wa.me complet pour envoyer un document au client.
     *
     * @param Document $document Le document (devis ou facture) à partager
     * @return string URL wa.me complète avec le message pré-rempli
     */
    public function generateLink(Document $document): string
    {
        $document->loadMissing(['company', 'client']);

        $phone = $this->formatPhoneNumber($document->client?->phone ?? '');
        $message = $this->buildMessage($document);

        // Encoder le message pour l'URL (évite les caractères spéciaux)
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }

    /**
     * Construit le message texte pré-rempli pour WhatsApp.
     *
     * @param Document $document
     * @return string Message formaté
     */
    public function buildMessage(Document $document): string
    {
        $companyName = $document->company?->name ?? 'Votre entreprise';
        $clientName  = $document->client?->name ?? 'Cher client';
        $docType     = $document->type === 'quote' ? 'Devis' : 'Facture';
        $docNumber   = $document->number;
        $totalFCFA   = $this->formatFCFA($document->total);

        // Lien sécurisé pour consulter le PDF
        $pdfLink = route('documents.view-pdf', $document);

        return implode("\n\n", [
            "Bonjour {$clientName},",
            "Veuillez trouver ci-joint votre {$docType} N°{$docNumber} d'un montant de {$totalFCFA}.",
            "🔗 Lien de consultation sécurisé : {$pdfLink}",
            "Merci pour votre confiance.\n{$companyName}",
        ]);
    }

    /**
     * Formate un montant en FCFA (XOF).
     *
     * @param float $amount
     * @return string Ex: "150 000 FCFA"
     */
    public function formatFCFA(float $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Normalise un numéro de téléphone au format international (sans le +).
     *
     * Règles :
     * - Supprime les espaces, tirets, parenthèses
     * - Supprime le préfixe '+' s'il est présent
     * - Supprime 'whatsapp:' si présent
     * - Garde uniquement les chiffres
     *
     * @param string $phone Numéro brut (ex: +221 77 123 45 67)
     * @return string Numéro formaté (ex: 221771234567)
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Supprime 'whatsapp:' si présent
        $phone = preg_replace('/^whatsapp:/i', '', $phone);

        // Supprime tout ce qui n'est pas un chiffre
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Si le numéro commence par '00', on remplace par rien (format international mal formé)
        $phone = ltrim($phone, '0');

        return $phone;
    }

    /**
     * Vérifie si un numéro de téléphone est valide pour WhatsApp.
     *
     * @param string $phone
     * @return bool
     */
    public function isValidPhone(string $phone): bool
    {
        $cleaned = $this->formatPhoneNumber($phone);

        // Au moins 7 chiffres et pas plus de 15 chiffres (standard E.164)
        return strlen($cleaned) >= 7 && strlen($cleaned) <= 15;
    }
}

