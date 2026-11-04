<?php

/**
 * Company Configuration
 * 
 * Update this file OR set values via .env:
 *   COMPANY_NAME, COMPANY_TAGLINE, COMPANY_WHATSAPP, COMPANY_ADDRESS, etc.
 * 
 * Run: php artisan config:clear after changing .env values
 */

return [
    'name'        => env('COMPANY_NAME', 'Kaizen Kreasi Indonesia'),
    'tagline'     => env('COMPANY_TAGLINE', 'Perlengkapan Event untuk Beragam Kebutuhan'),
    'description' => env('COMPANY_DESCRIPTION', 'Penyedia penyewaan perlengkapan event untuk wilayah Jabodetabek dan pengiriman ke seluruh Indonesia bagi event skala besar.'),
    'contact_description' => env('COMPANY_CONTACT_DESCRIPTION', 'Konsultasi dan pemesanan melalui WhatsApp 24 jam setiap hari.'),
    'whatsapp'    => env('COMPANY_WHATSAPP', '6281113051007'),
    'phone'       => env('COMPANY_PHONE', '0811-1305-1007'),
    'additional_phones' => [],
    'email'       => env('COMPANY_EMAIL', 'kaizenkreasi@gmail.com'),
    'address'     => env('COMPANY_ADDRESS', 'Ruko Grand Galaxy City, Jl. Boulevard Raya Timur RSNB 009, Kota Bekasi, Jawa Barat 17147'),
    'street_address'    => env('COMPANY_STREET_ADDRESS', 'Ruko Grand Galaxy City, Jl. Boulevard Raya Timur RSNB 009'),
    'city'        => env('COMPANY_CITY', 'Bekasi'),
    'region'      => env('COMPANY_REGION', 'Jawa Barat'),
    'country'     => env('COMPANY_COUNTRY', 'ID'),
    'maps_url'    => env('COMPANY_MAPS_URL', 'https://maps.app.goo.gl/YJibJiqrCoqz95qG8?g_st=ac'),
    'maps_embed'  => env('COMPANY_MAPS_EMBED', 'https://maps.google.com/maps?q=Kaizen+Kreasi+Indonesia+Ruko+Grand+Galaxy+City+JL.+Boulevard+Raya+Timur+RSNB+009+Bekasi+17147&output=embed'),
    'instagram'   => env('COMPANY_INSTAGRAM', ''),
    'facebook'    => env('COMPANY_FACEBOOK', ''),
    'youtube'     => env('COMPANY_YOUTUBE', ''),
    'tiktok'      => env('COMPANY_TIKTOK', ''),
    
    // Stats / badges
    'stats' => [
        'events'      => env('COMPANY_STAT_EVENTS', ''),
        'years'       => env('COMPANY_STAT_YEARS', ''),
        'clients'     => env('COMPANY_STAT_CLIENTS', ''),
        'products'    => env('COMPANY_STAT_PRODUCTS', ''),
    ],
    
    // Working hours
    'hours' => [
        'whatsapp' => 'Konsultasi & pemesanan via WhatsApp 24 jam setiap hari',
        'weekday' => 'Senin - Jumat: 08.00 - 17.00 WIB',
        'weekend' => 'Sabtu: 08.00 - 14.00 WIB',
        'holiday' => 'Minggu & Hari Libur: Dengan perjanjian',
    ],
];
