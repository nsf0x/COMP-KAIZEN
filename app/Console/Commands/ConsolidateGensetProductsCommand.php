<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConsolidateGensetProductsCommand extends Command
{
    protected $signature = 'kaizen:consolidate-genset';

    protected $description = 'Consolidate legacy genset products into the combined catalog item';

    public function handle(): int
    {
        $name = 'Genset Kapasitas 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 Watt';
        $slug = Str::slug($name);
        $description = 'Genset kapasitas 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 watt. Pemakaian 12 jam, solar untuk 12 jam sudah termasuk, kabel 100m, tersedia layanan overtime per jam.';
        $legacySlugs = [
            'genset-150-kva',
            'genset-100-kva',
            'genset-80-kva',
            'genset-60-kva',
            'genset-5000-watt',
            'genset-5000-watt-sampai-150-kva',
            $slug,
        ];

        DB::transaction(function () use ($description, $legacySlugs, $name, $slug): void {
            $products = Product::query()->whereIn('slug', $legacySlugs)->orderBy('order')->get();
            $product = $products->firstWhere('slug', $slug)
                ?? $products->firstWhere('slug', 'genset-5000-watt-sampai-150-kva')
                ?? $products->first();

            if (! $product) {
                $category = Category::firstOrCreate(
                    ['slug' => 'genset'],
                    [
                        'name' => 'Genset',
                        'type' => 'produk',
                        'icon' => 'bolt',
                        'description' => null,
                        'order' => (Category::query()->max('order') ?? 0) + 1,
                        'is_active' => true,
                    ],
                );

                $product = new Product(['category_id' => $category->id]);
                $product->order = (Product::query()->max('order') ?? 0) + 1;
            }

            $product->fill([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'is_featured' => true,
                'status' => true,
                'meta_description' => Str::limit($description, 160),
            ]);
            $product->save();
            $product->images()->delete();

            foreach ($products as $duplicate) {
                if ($duplicate->isNot($product)) {
                    $duplicate->delete();
                }
            }
        });

        return self::SUCCESS;
    }
}