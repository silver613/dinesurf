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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'api_key' => env('GOOGLE_CLIENT_API_KEY'),
        'redirect' => env('APP_URL').'/google-callback',
        'project_id' => 'dinesurf',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'redirect_uris' => [
            'http://127.0.0.1:8000/google-redirect',
            'http://localhost:8000/google-redirect',
            'https://app.dinesurf.com/google-redirect',
            'https://dev.dinesurf.com/google-redirect',
            'http://127.0.0.1:8000/google-callback',
            'http://localhost:8000/google-callback',
            'https://app.dinesurf.com/google-callback',
            'https://dev.dinesurf.com/google-callback',
        ],
        'javascript_origins' => [
            'http://127.0.0.1:8000',
            'http://localhost:8000',
            'https://app.dinesurf.com',
            'https://dev.dinesurf.com',
        ],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/facebook-callback',
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'app_sid' => env('TWILIO_APP_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'number' => env('TWILIO_NUMBER'),
    ],

    'paystack' => [
        'mode' => env('PAYSTACK'),
        'test_pk' => env('PAYSTACK_TEST_PK'),
        'test_sk' => env('PAYSTACK_TEST_SK'),
        'live_pk' => env('PAYSTACK_PK'),
        'live_sk' => env('PAYSTACK_SK'),
    ],

    'stripe' => [
        'mode' => env('STRIPE'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'test_key' => env('STRIPE_TEST_KEY'),
        'test_secret' => env('STRIPE_TEST_SECRET'),
    ],

    'termii' => [
        'key' => env('TERMII_KEY'),
        'sender_id' => env('TERMII_SENDER_ID'),
    ],

    'trip_advisor' => [
        'api_key' => env('TRIP_ADVISOR_API_KEY'),
    ],

    'bulk_sms_ng' => [
        'api_key' => env('BULK_SMS_NG_API_KEY'),
    ],

    'mira' => [
        'url' => env('MIRA_URL'),
        'key' => env('MIRA_KEY'),
        'test_url' => env('MIRA_TEST_URL'),
        'test_key' => env('MIRA_TEST_KEY'),
    ],
];
