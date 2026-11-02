<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'JAKARTA INTERNASIONAL CHAMPIONSHIP',
                'description' => "International Gol Tournament\nVenue : Damai Indah Golf PIK Course",
            ],
            [
                'title' => 'INDONESIA WOMEN OPEN 2025',
                'description' => "Tournament Golf Internasional\nVenue event : Damai Indah Golf -BSD Course",
            ],
            [
                'title' => 'BNI INDONESIAN MASTERS 2024',
                'description' => "Tournament Golf Internasional\nVenue event : Royale Golf - Halim Perdana Kusuma",
            ],
            [
                'title' => 'MANDIRI INDONESIAN OPEN 2024',
                'description' => "Tournament Golf Internasional\nVenue event : Royale Golf - Halim Perdana Kusuma",
            ],
            [
                'title' => 'ROAD TO SPORT NATION 2024',
                'description' => "Sport event\nVenue : ICE BSD",
            ],
            [
                'title' => 'SIMONE APAC CUP 2023',
                'description' => "Tournament Golf Internasional\nVenue : Pondok Indah Golf",
            ],
            [
                'title' => 'INDONESIAN OPEN 2023',
                'description' => "Tournament Golf Internasional\nVenue event : PONDOK INDAH GOLF",
            ],
            [
                'title' => 'MANDIRI INDONESIA OPEN 2022',
                'description' => "Sport event\nVenue : ICE BSD",
            ],
            [
                'title' => 'PARADE PASAR RAKYAT DEPOK',
                'photos' => ['portfolio/events/parade-pasar-rakyat-depok/01.png'],
                'is_featured' => true,
            ],
            [
                'title' => 'INFACT EXPO (HUT KOTA DEPOK)',
                'photos' => ['portfolio/events/infact-expo-hut-kota-depok/01.png'],
                'is_featured' => true,
            ],
            [
                'title' => 'HKSN & KS EXPO (KALIMANTAN SELATAN)',
                'photos' => ['portfolio/events/hksn-ks-expo-kalimantan-selatan/01.png'],
                'is_featured' => true,
            ],
            [
                'title' => 'ICNT & GIANYAR EXPO',
                'photos' => ['portfolio/events/icnt-gianyar-expo/01.png'],
                'is_featured' => true,
            ],
            [
                'title' => 'KSN EXPO GORONTALO',
                'photos' => ['portfolio/events/ksn-expo-gorontalo/01.png'],
                'is_featured' => true,
            ],
            [
                'title' => 'PERAYAAN NATAL NASIONAL',
                'photos' => ['portfolio/events/perayaan-natal-nasional/01.png'],
                'is_featured' => true,
            ],
            [
                'title' => 'INDONESIA OUTDOOR FESTIVAL',
                'photos' => ['portfolio/events/indonesia-outdoor-festival/01.png'],
            ],
            [
                'title' => 'ERAU EXPO',
                'photos' => ['portfolio/events/erau-expo/01.jpg'],
            ],
            [
                'title' => 'KEMILAU KALTIM NASIONAL',
                'photos' => ['portfolio/events/kemilau-kaltim-nasional/01.png'],
            ],
            [
                'title' => 'KUKAR FAIR',
                'photos' => ['portfolio/events/kukar-fair/01.png'],
            ],
            [
                'title' => 'KALTIM FAIR',
                'photos' => ['portfolio/events/kaltim-fair/01.png'],
            ],
            [
                'title' => 'RAKORNAS KEMENSOS RI',
                'photos' => ['portfolio/events/rakornas-kemensos-ri/01.png'],
            ],
            [
                'title' => 'SOF LAUNCHING "KUKAR CSR VAGANZA"',
                'photos' => ['portfolio/events/sof-launching-kukar-csr-vaganza/01.png'],
            ],
        ];

        foreach ($items as $index => $item) {
            $photos = $item['photos'] ?? [];
            $portfolio = Portfolio::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'title' => $item['title'],
                    'type' => 'event',
                    'thumbnail' => $photos[0] ?? null,
                    'description' => $item['description'] ?? null,
                    'client_name' => null,
                    'event_date' => null,
                    'is_featured' => $item['is_featured'] ?? false,
                    'order' => $index + 1,
                ],
            );

            $portfolio->images()->delete();
            $portfolio->images()->createMany(array_map(
                fn (string $path, int $photoIndex): array => [
                    'image_path' => $path,
                    'caption' => null,
                    'order' => $photoIndex + 1,
                ],
                $photos,
                array_keys($photos),
            ));
        }
    }
}
