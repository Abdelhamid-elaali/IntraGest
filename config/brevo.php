<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Brevo API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Brevo API integration.
    | Brevo is used for sending transactional emails and notifications.
    |
    */

    // API key for Brevo API access
    'api_key' => env('BREVO_API_KEY'),

    // Default sender information
    'default_from_email' => env('MAIL_FROM_ADDRESS', 'support@intragest.com'),
    'default_from_name' => env('MAIL_FROM_NAME', 'IntraGest Support'),

    // Email templates
    'templates' => [
        'password_reset' => env('BREVO_TEMPLATE_ID', 1),
        'welcome' => env('BREVO_WELCOME_TEMPLATE_ID', 2),
        'notification' => env('BREVO_NOTIFICATION_TEMPLATE_ID', 3),
    ],

    // Contact lists
    'lists' => [
        'users' => env('BREVO_LIST_ID', 1),
    ],
];
