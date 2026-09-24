<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TwilioWhatsApp
{
    public function sendMessage(string $fromWhatsApp, string $toWhatsApp, string $messageBody): void
    {
        $accountSid = config('services.twilio_whatsapp.account_sid');
        $authToken = config('services.twilio_whatsapp.auth_token');
        $baseUrl = rtrim(config('services.twilio_whatsapp.base_url', 'https://api.twilio.com'), '/');
        $apiVersion = config('services.twilio_whatsapp.api_version', '2010-04-01');

        if (!$accountSid || !$authToken) {
            throw new RuntimeException('Twilio WhatsApp n\'est pas configuré (services.twilio_whatsapp manquants).');
        }

        // Twilio expects: https://api.twilio.com/2010-04-01/Accounts/{AccountSid}/Messages.json
        $url = sprintf('%s/%s/Accounts/%s/Messages.json', $baseUrl, $apiVersion, $accountSid);

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post($url, [
                'From' => $fromWhatsApp,
                'To' => $toWhatsApp,
                'Body' => $messageBody,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Erreur Twilio WhatsApp: ' . $response->body());
        }
    }

    /**
     * Normalize phone number to WhatsApp format.
     * Twilio typically uses: whatsapp:+{E164}
     */
    public function toWhatsAppNumber(string $phone): string
    {
        $phone = trim($phone);

        if ($phone === '') {
            return '';
        }

        // If already starts with whatsapp:
        if (Str::startsWith(strtolower($phone), 'whatsapp:')) {
            return $phone;
        }

        // Remove spaces, dashes, parentheses
        $normalized = preg_replace('/[^0-9+]/', '', $phone) ?: $phone;

        // Ensure + prefix if missing
        if (!Str::startsWith($normalized, '+')) {
            $normalized = '+' . ltrim($normalized, '+');
        }

        return 'whatsapp:' . $normalized;
    }
}

