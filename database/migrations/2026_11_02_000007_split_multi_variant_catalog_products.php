<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $this->splitProductGroup(
                'genset-berbagai-kapasitas',
                'Genset Berbagai Kapasitas',
                'Genset kapasitas 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 watt. Pemakaian 12 jam, solar untuk 12 jam sudah termasuk, kabel 100m, tersedia layanan overtime per jam.',
                ['genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt', 'genset-5000-watt-sampai-150-kva'],
                ['genset', 'genset-power'],
                'Genset',
                [
                    ['Genset 150 KVA', 'Genset kapasitas 150 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                    ['Genset 100 KVA', 'Genset kapasitas 100 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                    ['Genset 80 KVA', 'Genset kapasitas 80 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                    ['Genset 60 KVA', 'Genset kapasitas 60 KVA. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                    ['Genset 5000 Watt', 'Genset kapasitas 5000 watt. Hubungi kami untuk memastikan kebutuhan dan ketersediaan.'],
                ],
            );

            $this->splitProductGroup(
                'smart-tv-berbagai-ukuran',
                'Smart TV Berbagai Ukuran',
                'Smart TV ukuran 75 inch, 65 inch, dan 55 inch. Sudah termasuk kabel power 15m dan kabel HDMI 20m.',
                ['smart-tv-75-inch-65-inch-dan-55-inch'],
                ['smart-tv'],
                'Smart TV',
                [
                    ['Smart TV 75 Inch', 'Smart TV ukuran 75 inch, sudah termasuk kabel power 15m dan kabel HDMI 20m.'],
                    ['Smart TV 65 Inch', 'Smart TV ukuran 65 inch, sudah termasuk kabel power 15m dan kabel HDMI 20m.'],
                    ['Smart TV 55 Inch', 'Smart TV ukuran 55 inch, sudah termasuk kabel power 15m dan kabel HDMI 20m.'],
                ],
            );

            $this->splitProductGroup(
                'processor-novastar',
                'Processor Novastar',
                'Video processor profesional untuk pengelolaan tampilan LED screen.',
                ['processor-novastar-vx16s-vx600-vx400'],
                ['led-screen-processor'],
                'LED Screen & Processor',
                [
                    ['Processor Novastar VX16S', 'Video processor Novastar VX16S untuk pengelolaan tampilan LED screen.'],
                    ['Processor Novastar VX600', 'Video processor Novastar VX600 untuk pengelolaan tampilan LED screen.'],
                    ['Processor Novastar VX400', 'Video processor Novastar VX400 untuk pengelolaan tampilan LED screen.'],
                ],
            );
        });
    }

    public function down(): void {}

    private function splitProductGroup(
        string $hubSlug,
        string $hubName,
        string $hubDescription,
        array $legacyHubSlugs,
        array $categorySlugs,
        string $categoryName,
        array $variants,
    ): void {
        $variantSlugs = array_map(fn (array $variant): string => Str::slug($variant[0]), $variants);
        $groupSlugs = [$hubSlug, ...$legacyHubSlugs, ...$variantSlugs];
        $existingProduct = DB::table('products')->whereIn('slug', $groupSlugs)->first();

        if (! $existingProduct) {
            return;
        }

        $category = DB::table('categories')->whereIn('slug', $categorySlugs)->orderBy('id')->first();
        $legacyProducts = DB::table('products')->whereIn('slug', $legacyHubSlugs)->orderBy('id')->get();
        $source = $legacyProducts->first();

        if ($category) {
            $categoryId = $category->id;
        } else {
            $categoryId = $source?->category_id ?? $existingProduct->category_id ?? DB::table('categories')->insertGetId([
                'name' => $categoryName,
                'slug' => $categorySlugs[0],
                'type' => 'produk',
                'icon' => 'tag',
                'description' => null,
                'order' => (DB::table('categories')->max('order') ?? 0) + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $hub = DB::table('products')->where('slug', $hubSlug)->first();
        if (! $hub && $source) {
            $hub = $source;
            DB::table('products')->where('id', $hub->id)->update([
                'slug' => $hubSlug,
                'updated_at' => now(),
            ]);
        }

        $hubOrder = $hub->order ?? ((DB::table('products')->max('order') ?? 0) + 1);
        $hubValues = [
            'category_id' => $categoryId,
            'name' => $hubName,
            'slug' => $hubSlug,
            'description' => $hubDescription,
            'price' => null,
            'is_featured' => true,
            'status' => true,
            'order' => $hubOrder,
            'meta_description' => Str::limit(strip_tags($hubDescription), 160),
            'updated_at' => now(),
        ];

        if ($hub) {
            DB::table('products')->where('id', $hub->id)->update($hubValues);
        } else {
            $hubValues['created_at'] = now();
            DB::table('products')->insert($hubValues);
        }

        foreach ($variants as $index => [$name, $description]) {
            $slug = Str::slug($name);
            $existing = DB::table('products')->where('slug', $slug)->first();
            $values = [
                'category_id' => $categoryId,
                'name' => $name,
                'slug' => $slug,
                'description' => $existing?->description ?: $description,
                'price' => null,
                'is_featured' => false,
                'status' => true,
                'order' => $hubOrder + $index + 1,
                'meta_description' => Str::limit(strip_tags($description), 160),
                'updated_at' => now(),
            ];

            if ($existing) {
                DB::table('products')->where('id', $existing->id)->update($values);
            } else {
                $values['created_at'] = now();
                DB::table('products')->insert($values);
            }
        }

        $duplicates = $legacyHubSlugs;
        if ($source && $source->slug !== $hubSlug) {
            $duplicates = array_values(array_diff($duplicates, [$source->slug]));
        }
        if ($duplicates !== []) {
            DB::table('products')->whereIn('slug', $duplicates)->update([
                'status' => false,
                'updated_at' => now(),
            ]);
        }

        DB::table('products')->whereIn('slug', $variantSlugs)->update([
            'category_id' => $categoryId,
            'status' => true,
            'updated_at' => now(),
        ]);
    }
};
