<?php

return [
    'secret_key' => env('KHALTI_SECRET_KEY'),
    'sandbox' => env('KHALTI_SANDBOX', true),
    'website_url' => env('KHALTI_WEBSITE_URL', env('APP_URL', 'http://127.0.0.1:8000')),

    'base_url' => env('KHALTI_SANDBOX', true)
        ? 'https://dev.khalti.com/api/v2/'
        : 'https://khalti.com/api/v2/',

    'payment_url' => env('KHALTI_SANDBOX', true)
        ? 'https://test-pay.khalti.com/'
        : 'https://pay.khalti.com/',
];
