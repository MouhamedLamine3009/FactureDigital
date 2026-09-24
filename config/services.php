<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/auth/callback/google',
    ],

    'microsoft' => [
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'redirect' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/auth/callback/microsoft',
    ],

    'twilio_whatsapp' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from' => env('TWILIO_WHATSAPP_FROM'),
        'base_url' => env('TWILIO_BASE_URL', 'https://api.twilio.com'),
        'api_version' => env('TWILIO_API_VERSION', '2010-04-01'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta WhatsApp Cloud API
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'envoi de messages via l'API officielle
    | Meta WhatsApp Cloud API (Methode 2 - Automatique).
    |
    | Documentation: https://developers.facebook.com/docs/whatsapp/cloud-api
    |
    | Variables .env requises:
    |   META_WHATSAPP_ACCESS_TOKEN=VotreTokenAcces
    |   META_WHATSAPP_PHONE_NUMBER_ID=VotrePhoneNumberID
    |
    | Optionnelles:
    |   META_WHATSAPP_BASE_URL=https://graph.facebook.com
    |   META_WHATSAPP_API_VERSION=v22.0
    |
    */
    'meta_whatsapp' => [
        'access_token'     => env('META_WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id'  => env('META_WHATSAPP_PHONE_NUMBER_ID'),
        'base_url'         => env('META_WHATSAPP_BASE_URL', 'https://graph.facebook.com'),
        'api_version'      => env('META_WHATSAPP_API_VERSION', 'v22.0'),
    ],

];

