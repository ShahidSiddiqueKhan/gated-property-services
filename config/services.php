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

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | Stripe handles card payments, aimed at overseas property owners paying
    | in a foreign currency. JazzCash handles local mobile-wallet payments
    | for owners inside Pakistan. Both run against test/sandbox endpoints
    | until real merchant credentials are supplied in .env.
    |
    */

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'usd'),
    ],

    'jazzcash' => [
        'merchant_id' => env('JAZZCASH_MERCHANT_ID'),
        'password' => env('JAZZCASH_PASSWORD'),
        'integrity_salt' => env('JAZZCASH_INTEGRITY_SALT'),
        'environment' => env('JAZZCASH_ENV', 'sandbox'), // sandbox|live
    ],

    // Safepay — admin-added third gateway (cards, wallets & bank rails via a
    // single Pakistani aggregator). api_key is the public "client" key,
    // secret is the v1 signing secret used to verify the return redirect,
    // webhook_secret verifies async payment.succeeded/failed webhooks.
    'safepay' => [
        'api_key' => env('SAFEPAY_API_KEY'),
        'secret' => env('SAFEPAY_SECRET'),
        'webhook_secret' => env('SAFEPAY_WEBHOOK_SECRET'),
        'environment' => env('SAFEPAY_ENV', 'sandbox'), // sandbox|production
    ],

];
