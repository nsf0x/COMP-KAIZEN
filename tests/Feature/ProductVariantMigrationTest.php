<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_splits_combined_catalog_rows_without_deleting_products_or_images(): void
    {
        $categoryIds = [];
        foreach ([
            ['Genset', 'genset'],
            ['Smart TV', 'smart-tv'],
            ['LED Screen & Processor', 'led-screen-processor'],
        ] as [$name, $slug]) {
            $categoryIds[$slug] = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'type' => 'produk',
                    'icon' => 'tag',
                    'order' => count($categoryIds) + 1,
                    'is_active' => true,
                ],
            )->id;
        }

        $legacyProducts = [
            ['Genset gabungan', 'genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt', 'genset'],
            ['Smart TV gabungan', 'smart-tv-75-inch-65-inch-dan-55-inch', 'smart-tv'],
            ['Processor gabungan', 'processor-novastar-vx16s-vx600-vx400', 'led-screen-processor'],
        ];

        foreach ($legacyProducts as [$name, $slug, $categorySlug]) {
            $product = Product::create([
                'category_id' => $categoryIds[$categorySlug],
                'name' => $name,
                'slug' => $slug,
                'description' => 'Deskripsi lama',
                'price' => 'Hubungi Kami',
                'thumbnail' => $slug.'.jpg',
                'status' => true,
            ]);
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $slug.'-gallery.jpg',
                'order' => 1,
            ]);
        }

        $gensetVariant = Product::create([
            'category_id' => $categoryIds['genset'],
            'name' => 'Genset 150 KVA lama',
            'slug' => 'genset-150-kva',
            'status' => true,
        ]);
        $oldIds = Product::query()->orderBy('id')->pluck('id')->all();

        $migration = require base_path('database/migrations/2026_11_02_000007_split_multi_variant_catalog_products.php');
        $migration->up();

        $this->assertSame(14, Product::query()->count());
        $this->assertSame('genset-berbagai-kapasitas', Product::query()->findOrFail($oldIds[0])->slug);
        $this->assertSame('smart-tv-berbagai-ukuran', Product::query()->findOrFail($oldIds[1])->slug);
        $this->assertSame('processor-novastar', Product::query()->findOrFail($oldIds[2])->slug);
        $this->assertSame('Genset 150 KVA', $gensetVariant->fresh()->name);
        $this->assertSame('genset-150-kva', $gensetVariant->fresh()->slug);
        $this->assertSame('genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt.jpg', Product::query()
            ->where('slug', 'genset-berbagai-kapasitas')
            ->value('thumbnail'));
        $this->assertSame(1, ProductImage::query()->where('product_id', $oldIds[0])->count());
        $this->assertDatabaseHas('products', ['slug' => 'genset-berbagai-kapasitas', 'price' => null, 'status' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'smart-tv-75-inch', 'status' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'processor-novastar-vx600', 'status' => true]);
    }

    public function test_it_is_safe_to_run_more_than_once(): void
    {
        $migration = require base_path('database/migrations/2026_11_02_000007_split_multi_variant_catalog_products.php');

        $migration->up();
        $initialCount = Product::query()->count();
        $migration->up();

        $this->assertSame($initialCount, Product::query()->count());
    }
}
