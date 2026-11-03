<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['category' => 'tenda-flooring', 'name' => 'Tenda Roder Span 10m, 15m, 20m', 'description' => 'Tenda roder untuk pameran, corporate event, wedding, dan event outdoor skala besar.', 'featured' => true],
            ['category' => 'tenda-flooring', 'name' => 'Tenda Sarnafil 3x3m dan 5x5m', 'description' => 'Tenda modular untuk area registrasi, booth, dan kebutuhan event outdoor.', 'featured' => true],
            ['category' => 'tenda-flooring', 'name' => 'Tenda Konvensional', 'description' => 'Solusi tenda fleksibel untuk berbagai kebutuhan acara dan area tamu.', 'featured' => false],
            ['category' => 'tenda-flooring', 'name' => 'Flooring Playwood 18mm', 'description' => 'Lantai event yang kuat dan rapi untuk tenda, panggung, dan area aktivitas.', 'featured' => false],
            ['category' => 'tenda-flooring', 'name' => 'Karpet Layak dan Karpet Baru', 'description' => 'Pilihan karpet untuk memperkuat kenyamanan dan tampilan area event.', 'featured' => false],
            ['category' => 'tenda-flooring', 'name' => 'Ringlock System', 'description' => 'Sistem struktur vertical dan horizontal untuk kebutuhan konstruksi event.', 'featured' => false],
            ['category' => 'led-screen-processor', 'name' => 'LED Screen P2.6 Indoor dan Outdoor', 'description' => 'LED screen dengan pilihan indoor, outdoor, curve, dan straight untuk panggung maupun branding.', 'featured' => true],
            ['category' => 'led-screen-processor', 'name' => 'LED Screen P3.9 Indoor dan Outdoor', 'description' => 'LED screen modular untuk konser, exhibition, corporate event, dan wedding.', 'featured' => true],
            ['category' => 'led-screen-processor', 'name' => 'LED Cube P2.5 48x48cm', 'description' => 'LED cube modular untuk instalasi visual kreatif dan kebutuhan event experience.', 'featured' => false],
            ['category' => 'led-screen-processor', 'name' => 'Processor Novastar', 'description' => 'Video processor profesional untuk pengelolaan tampilan LED screen.', 'featured' => true],
            ['category' => 'led-screen-processor', 'name' => 'Processor Novastar VX16S', 'description' => 'Video processor Novastar VX16S untuk pengelolaan tampilan LED screen.', 'featured' => false],
            ['category' => 'led-screen-processor', 'name' => 'Processor Novastar VX600', 'description' => 'Video processor Novastar VX600 untuk pengelolaan tampilan LED screen.', 'featured' => false],
            ['category' => 'led-screen-processor', 'name' => 'Processor Novastar VX400', 'description' => 'Video processor Novastar VX400 untuk pengelolaan tampilan LED screen.', 'featured' => false],
            ['category' => 'genset-power', 'name' => 'Genset Berbagai Kapasitas', 'description' => 'Genset kapasitas 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 watt.', 'featured' => true],
            ['category' => 'genset-power', 'name' => 'Genset 150 KVA', 'description' => 'Genset kapasitas 150 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.', 'featured' => false],
            ['category' => 'genset-power', 'name' => 'Genset 100 KVA', 'description' => 'Genset kapasitas 100 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.', 'featured' => false],
            ['category' => 'genset-power', 'name' => 'Genset 80 KVA', 'description' => 'Genset kapasitas 80 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.', 'featured' => false],
            ['category' => 'genset-power', 'name' => 'Genset 60 KVA', 'description' => 'Genset kapasitas 60 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.', 'featured' => false],
            ['category' => 'genset-power', 'name' => 'Genset 5000 Watt', 'description' => 'Genset kapasitas 5000 watt. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.', 'featured' => false],
            ['category' => 'smart-tv', 'name' => 'Smart TV Berbagai Ukuran', 'description' => 'Smart TV ukuran 75 inch, 65 inch, dan 55 inch. Sudah termasuk kabel power 15m dan kabel HDMI 20m.', 'featured' => true],
            ['category' => 'smart-tv', 'name' => 'Smart TV 75 Inch', 'description' => 'Smart TV ukuran 75 inch, sudah termasuk kabel power 15m dan kabel HDMI 20m.', 'featured' => false],
            ['category' => 'smart-tv', 'name' => 'Smart TV 65 Inch', 'description' => 'Smart TV ukuran 65 inch, sudah termasuk kabel power 15m dan kabel HDMI 20m.', 'featured' => false],
            ['category' => 'smart-tv', 'name' => 'Smart TV 55 Inch', 'description' => 'Smart TV ukuran 55 inch, sudah termasuk kabel power 15m dan kabel HDMI 20m.', 'featured' => false],
            ['category' => 'sound-system', 'name' => 'Sound System Event', 'description' => 'Sistem tata suara profesional untuk seminar, konser, golf tournament, dan corporate event.', 'featured' => true],
            ['category' => 'lighting-stage', 'name' => 'Lighting System', 'description' => 'Pencahayaan panggung dan area event untuk menghasilkan suasana serta visual yang maksimal.', 'featured' => true],
            ['category' => 'lighting-stage', 'name' => 'Stage dan Rigging', 'description' => 'Panggung, grand stand, dan struktur rigging untuk event skala kecil hingga besar.', 'featured' => true],
            ['category' => 'furniture-seating', 'name' => 'Furniture dan Seating Event', 'description' => 'Kursi, meja, sofa, puff, bean bag, dan furniture event dengan beragam kebutuhan layout.', 'featured' => true],
            ['category' => 'booth-branding', 'name' => 'Produksi Booth, Backdrop, dan Branding', 'description' => 'Produksi gate, backdrop, branding, booth special design, dan booth maxima system.', 'featured' => true],
            ['category' => 'event-support', 'name' => 'Toilet Portable dan Standing AC', 'description' => 'Perlengkapan pendukung kenyamanan tamu dan operasional event di berbagai lokasi.', 'featured' => false],
            ['category' => 'event-support', 'name' => 'HT Baofeng', 'description' => 'Radio komunikasi untuk koordinasi tim selama persiapan dan pelaksanaan event.', 'featured' => false],
        ];

        Category::firstOrCreate(
            ['slug' => 'smart-tv'],
            [
                'name' => 'Smart TV',
                'type' => 'produk',
                'icon' => 'tv',
                'description' => null,
                'order' => (Category::max('order') ?? 0) + 1,
                'is_active' => true,
            ],
        );

        foreach ($products as $order => $data) {
            $category = Category::where('slug', $data['category'])->first();

            if (! $category) {
                continue;
            }

            $slug = Str::slug($data['name']);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'specification' => null,
                    'price' => null,
                    'is_featured' => $data['featured'],
                    'status' => true,
                    'order' => $order + 1,
                    'meta_description' => Str::limit($data['description'], 160),
                ],
            );
        }
    }
}
