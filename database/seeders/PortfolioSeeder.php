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
            ['title' => 'Pernikahan Mewah di Ballroom Grand Hyatt', 'type' => 'event', 'client_name' => 'Grand Hyatt', 'event_date' => '2026-03-15', 'is_featured' => true,
             'description' => 'Dekorasi pernikahan bertema white elegance dengan sentuhan bunga segar mawar putih dan peach. Melayani 500 tamu undangan dengan setup tenda, lighting premium, dan sound system profesional.'],
            ['title' => 'Ulang Tahun ke-17 Nadya di Kafe Rooftop', 'type' => 'event', 'client_name' => 'Nadya', 'event_date' => '2026-05-20', 'is_featured' => true,
             'description' => 'Pesta ulang tahun bertema pink boho dengan dekorasi balon, backdrop foto interaktif, dan pencahayaan fairy light yang memukau.'],
            ['title' => 'Reuni Akbar Alumni SMA Nusantara', 'type' => 'event', 'client_name' => 'SMA Nusantara', 'event_date' => '2026-02-10', 'is_featured' => false,
             'description' => 'Penanganan reuni 300 alumni dengan tenda roder besar, kursi, meja, sound system, dan dekorasi bertema nostalgia.'],
            ['title' => 'Sunatan Massal di Lapangan RW 05', 'type' => 'event', 'client_name' => 'RW 05', 'event_date' => '2026-04-12', 'is_featured' => true,
             'description' => 'Tenda besar 20x40m untuk sunatan massal 50 anak. Dilengkapi kursi, meja registrasi, dan sound system untuk sambutan.'],
            ['title' => 'Launching Produk Kosmetik BeautyFirst', 'type' => 'produk', 'client_name' => 'BeautyFirst', 'event_date' => '2026-06-08', 'is_featured' => false,
             'description' => 'Event peluncuran produk dengan setup stage profesional, LED backdrop, tata cahaya moving head, dan sound system bertenaga tinggi.'],
            ['title' => 'Wisuda Angkatan 2026 Universitas Maju', 'type' => 'event', 'client_name' => 'Universitas Maju', 'event_date' => '2026-07-22', 'is_featured' => true,
             'description' => 'Dekorasi wisuda untuk 800 wisudawan dengan tenda besar, kursi Tiffany, panggung, dan sound system lengkap.'],
        ];

        foreach ($items as $i => $item) {
            Portfolio::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'title'       => $item['title'],
                    'type'        => $item['type'],
                    'client_name' => $item['client_name'] ?? null,
                    'event_date'  => $item['event_date'],
                    'description' => $item['description'],
                    'is_featured' => $item['is_featured'],
                    'order'       => $i + 1,
                ],
            );
        }
    }
}
