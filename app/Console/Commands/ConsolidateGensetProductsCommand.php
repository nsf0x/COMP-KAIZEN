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

    protected $description = 'Ensure the genset hub and capacity variants exist without deleting catalog records';

    public function handle(): int
    {
        DB::transaction(function (): void {
            $category = Category::query()
                ->whereIn('slug', ['genset', 'genset-power'])
                ->orderByRaw("CASE WHEN slug = 'genset' THEN 0 ELSE 1 END")
                ->first();

            if (! $category) {
                $category = Category::create([
                    'name' => 'Genset',
                    'slug' => 'genset',
                    'type' => 'produk',
                    'icon' => 'bolt',
                    'order' => (Category::query()->max('order') ?? 0) + 1,
                    'is_active' => true,
                ]);
            }

            $hubSlug = 'genset-berbagai-kapasitas';
            $hub = Product::query()->where('slug', $hubSlug)->first();

            if (! $hub) {
                $hub = Product::query()
                    ->whereIn('slug', [
                        'genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt',
                        'genset-5000-watt-sampai-150-kva',
                    ])
                    ->orderBy('id')
                    ->first();
            }

            $hubDescription = 'Genset kapasitas 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 watt. Pemakaian 12 jam, solar untuk 12 jam sudah termasuk, kabel 100m, tersedia layanan overtime per jam.';
            $hub ??= new Product(['order' => (Product::query()->max('order') ?? 0) + 1]);
            $hub->fill([
                'category_id' => $category->id,
                'name' => 'Genset Berbagai Kapasitas',
                'slug' => $hubSlug,
                'description' => $hubDescription,
                'price' => null,
                'is_featured' => true,
                'status' => true,
                'meta_description' => Str::limit($hubDescription, 160),
            ])->save();

            foreach ([
                ['name' => 'Genset 150 KVA', 'description' => 'Genset kapasitas 150 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                ['name' => 'Genset 100 KVA', 'description' => 'Genset kapasitas 100 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                ['name' => 'Genset 80 KVA', 'description' => 'Genset kapasitas 80 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                ['name' => 'Genset 60 KVA', 'description' => 'Genset kapasitas 60 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                ['name' => 'Genset 5000 Watt', 'description' => 'Genset kapasitas 5000 watt. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
            ] as $index => $variant) {
                $slug = Str::slug($variant['name']);
                $product = Product::query()->firstOrNew(['slug' => $slug]);
                $product->fill([
                    'category_id' => $category->id,
                    'name' => $variant['name'],
                    'description' => $product->description ?: $variant['description'],
                    'price' => null,
                    'is_featured' => false,
                    'status' => true,
                    'order' => $hub->order + $index + 1,
                    'meta_description' => Str::limit($variant['description'], 160),
                ])->save();
            }
        });

        return self::SUCCESS;
    }
}
