<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'tenda-flooring', 'name' => 'Tenda & Karpet Flooring', 'icon' => 'tent', 'order' => 1],
            ['slug' => 'led-screen-processor', 'name' => 'LED Screen & Processor', 'icon' => 'display', 'order' => 2],
            ['slug' => 'genset-power', 'name' => 'Genset & Power', 'icon' => 'bolt', 'order' => 3],
            ['slug' => 'sound-system', 'name' => 'Sound System', 'icon' => 'speaker', 'order' => 4],
            ['slug' => 'lighting-stage', 'name' => 'Panggung & Backdrop', 'icon' => 'lightbulb', 'order' => 5],
            ['slug' => 'furniture-seating', 'name' => 'Furniture (Kursi & Meja)', 'icon' => 'chair', 'order' => 6],
            ['slug' => 'booth-branding', 'name' => 'Booth & Branding', 'icon' => 'booth', 'order' => 7],
            ['slug' => 'event-support', 'name' => 'Event Support', 'icon' => 'support', 'order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name'      => $cat['name'],
                    'icon'      => $cat['icon'],
                    'is_active' => true,
                    'order'     => $cat['order'],
                ],
            );
        }

    }
}
