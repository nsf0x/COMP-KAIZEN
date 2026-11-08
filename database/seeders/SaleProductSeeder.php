<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaleProductSeeder extends Seeder
{
    private const PRODUCTS = [
        'Beam Redsun 440 BSW',
        'Par Redsun 18 x 10',
        'Walwasher',
        'Strobo IBS',
        'Minibriute Redsun',
        'Splitter 8 Channel Redsun',
        'Presnel COB 200 Watt',
        'Polo Spot 350',
        'Stand Lighting @4 Mtr',
        'Smoke 1500 (smoke machine)',
        'Mixer Avolite (lighting console)',
    ];

    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'led-lighting'],
            [
                'name' => 'LED Lighting',
                'type' => 'produk',
                'icon' => 'lightbulb',
                'order' => (Category::max('order') ?? 0) + 1,
                'is_active' => true,
            ],
        );

        foreach (self::PRODUCTS as $order => $name) {
            $slug = Str::slug($name);
            $existing = $this->findMatchingProduct($name, $slug);

            if ($existing) {
                $existing->update(['bisa_dijual' => true]);

                continue;
            }

            Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => $slug,
                'description' => 'Perlengkapan LED Lighting dengan nama '.$name.'.',
                'specification' => null,
                'thumbnail' => null,
                'bisa_dijual' => true,
                'bisa_disewa' => true,
                'is_featured' => false,
                'status' => true,
                'order' => $order + 1,
                'meta_description' => 'Jual dan sewa '.$name.' di Bekasi. Kaizen Kreasi Indonesia melayani Jabodetabek.',
            ]);
        }
    }

    private function findMatchingProduct(string $name, string $slug): ?Product
    {
        $normalizedName = $this->normalizeName($name);

        foreach (Product::query()->get() as $product) {
            if ($product->slug === $slug || $this->normalizeName($product->name) === $normalizedName) {
                return $product;
            }

            similar_text($normalizedName, $this->normalizeName($product->name), $similarity);
            if ($similarity >= 80) {
                return $product;
            }
        }

        return null;
    }

    private function normalizeName(string $name): string
    {
        return preg_replace('/[^a-z0-9]/', '', mb_strtolower($name)) ?? '';
    }
}
