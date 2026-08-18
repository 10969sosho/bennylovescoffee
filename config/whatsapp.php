<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp floating button
    |
    */

    'phone_number' => env('WHATSAPP_PHONE_NUMBER', '628123288940'),
    'message' => env('WHATSAPP_MESSAGE', 'Halo Benny, saya tertarik dengan produk Bennylovescoffee!'),
    'enabled' => env('WHATSAPP_ENABLED', true),
];
