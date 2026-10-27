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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'mapbox' => [
        'public_token' => env('MAPBOX_PUBLIC_TOKEN', 'pk.eyJ1IjoidGVzdHVzZXIiLCJhIjoiY2x5NTkwNzl0MGh6dzJqcTI3dDB1aG9jaSJ9.test_token'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'base_url' => env('OPENAI_BASE_URL'),
        'timeout' => env('OPENAI_TIMEOUT', 60),
    ],

    // Social Authentication
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/facebook/callback',
    ],

    'microsoft' => [
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/microsoft/callback',
        'tenant' => env('MICROSOFT_TENANT', 'common'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/linkedin/callback',
    ],

    // ANSUT Stock (gestion des stocks d'équipements) : attribution des ordinateurs portables et suivi de leur remise.
    'ansut_stock' => [
        'base_url' => env('ANSUT_STOCK_API_URL'),          // ex. https://api-recette.ansut.ci
        'token_url' => env('ANSUT_STOCK_TOKEN_URL'),       // ex. https://auth-recette.ansut.ci/realms/ansut/protocol/openid-connect/token
        'client_id' => env('ANSUT_STOCK_CLIENT_ID'),       // clé d'API partenaire créée dans ANSUT Stock (/admin/partenaires)
        'client_secret' => env('ANSUT_STOCK_CLIENT_SECRET'),
        'webhook_secret' => env('ANSUT_STOCK_WEBHOOK_SECRET'), // secret whsec_ du webhook créé dans ANSUT Stock (/admin/webhooks)
    ],

];
