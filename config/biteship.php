<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Biteship API Configuration
    |--------------------------------------------------------------------------
    |
    | API Key from Biteship Dashboard
    |
    */
    
    'api_key' => env('BITESHIP_API_KEY', ''),
    
    'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com/v1'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Origin (Alamat Pengirim)
    |--------------------------------------------------------------------------
    |
    | Origin Area ID from Biteship
    | 
    */
    
    'origin_area_id' => env('BITESHIP_ORIGIN_AREA_ID', ''),
    'origin_postal_code' => env('BITESHIP_ORIGIN_POSTAL_CODE', ''),
    'origin_latitude' => env('BITESHIP_ORIGIN_LATITUDE', ''),
    'origin_longitude' => env('BITESHIP_ORIGIN_LONGITUDE', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Supported Couriers
    |--------------------------------------------------------------------------
    */
    
    'couriers' => ['jne'], 
];
