<?php

namespace App\Support;

class PublicText
{
    public static function normalize(string $text): string
    {
        return str_ireplace(
            ['perusahaandi Bekasi', 'Jabodetabekdan pengiriman', '24jam'],
            ['perusahaan di Bekasi', 'Jabodetabek dan pengiriman', '24 jam'],
            $text,
        );
    }
}
