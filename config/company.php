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
    'tagline'     => env('COMPANY_TAGLINE', 'Profesional Event Equipment Rental Service'),
    'description' => env('COMPANY_DESCRIPTION', 'Penyedia layanan event organizer dan penyewaan perlengkapan event profesional untuk acara skala kecil hingga besar di seluruh Indonesia.'),
    'whatsapp'    => env('COMPANY_WHATSAPP', '6281119998629'),
    'phone'       => env('COMPANY_PHONE', '0811-1999-8629'),
    'additional_phones' => ['0812-3456-7890', '0813-4567-8901'],
    'email'       => env('COMPANY_EMAIL', 'info@kaizenkreasiindonesia.com'),
    'address'     => env('COMPANY_ADDRESS', 'Ruko Grand Galaxy City, Jl. Boulevard Raya Timur RSNB 009, Kota Bekasi, Jawa Barat 17147'),
    'maps_embed'  => env('COMPANY_MAPS_EMBED', 'https://maps.google.com/maps?q=Ruko+Grand+Galaxy+City+Bekasi&output=embed'),
    'instagram'   => env('COMPANY_INSTAGRAM', ''),
    'facebook'    => env('COMPANY_FACEBOOK', ''),
    'youtube'     => env('COMPANY_YOUTUBE', ''),
    'tiktok'      => env('COMPANY_TIKTOK', ''),
    
    // Stats / badges
    'stats' => [
        'events'      => env('COMPANY_STAT_EVENTS', '17+'),
        'years'       => env('COMPANY_STAT_YEARS', '8+'),
        'clients'     => env('COMPANY_STAT_CLIENTS', '50+'),
        'products'    => env('COMPANY_STAT_PRODUCTS', '30+'),
    ],
    
    // Working hours
    'hours' => [
        'weekday' => 'Senin - Jumat: 08.00 - 17.00 WIB',
        'weekend' => 'Sabtu: 08.00 - 14.00 WIB',
        'holiday' => 'Minggu & Hari Libur: Dengan perjanjian',
    ],
];
