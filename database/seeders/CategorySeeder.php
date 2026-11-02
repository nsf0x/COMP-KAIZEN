<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tenda & Flooring', 'icon' => 'tent', 'order' => 1],
            ['name' => 'LED Screen & Processor', 'icon' => 'display', 'order' => 2],
            ['name' => 'Genset & Power', 'icon' => 'bolt', 'order' => 3],
            ['name' => 'Sound System', 'icon' => 'speaker', 'order' => 4],
            ['name' => 'Lighting & Stage', 'icon' => 'lightbulb', 'order' => 5],
            ['name' => 'Furniture & Seating', 'icon' => 'chair', 'order' => 6],
            ['name' => 'Booth & Branding', 'icon' => 'booth', 'order' => 7],
            ['name' => 'Event Support', 'icon' => 'support', 'order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
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
