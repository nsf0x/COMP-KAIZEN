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

    public static function limitToCompleteSentences(string $text, int $limit): string
    {
        preg_match_all('/[^.!?]+[.!?](?:\s+|$)/u', trim($text), $matches);

        $description = '';
        foreach ($matches[0] as $sentence) {
            $candidate = $description.$sentence;
            if (mb_strlen(trim($candidate)) > $limit) {
                break;
            }

            $description = $candidate;
        }

        return trim($description);
    }
}
