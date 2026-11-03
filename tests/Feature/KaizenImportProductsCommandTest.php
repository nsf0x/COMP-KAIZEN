<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KaizenImportProductsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_command_creates_categories_and_products_from_json_file(): void
    {
        $this->artisan('kaizen:import-products', ['--file' => 'produk-kaizen-kreasi.json'])
            ->assertSuccessful();

        $this->assertTrue(Category::query()->where('slug', 'tenda-flooring')->exists());
        $this->assertTrue(Product::query()->where('slug', 'tenda-roder')->exists());
        $this->assertDatabaseHas('products', [
            'name' => 'Tenda Roder',
            'status' => true,
            'price' => null,
        ]);

        $productCount = Product::query()->count();
        $categoryCount = Category::query()->count();

        $this->artisan('kaizen:import-products', ['--file' => 'produk-kaizen-kreasi.json'])
            ->assertSuccessful();

        $this->assertSame($productCount, Product::query()->count());
        $this->assertSame($categoryCount, Category::query()->count());
    }

    public function test_production_bootstrap_imports_only_the_kaizen_catalog_and_is_idempotent(): void
    {
        Storage::fake('public');
        $this->artisan('kaizen:production-startup')->assertSuccessful();

        $this->assertSame(5, Product::query()->whereIn('slug', [
            'genset-150-kva',
            'genset-100-kva',
            'genset-80-kva',
            'genset-60-kva',
            'genset-5000-watt',
        ])->where('status', true)->count());
        $this->assertSame(3, Product::query()->whereIn('slug', [
            'smart-tv-75-inch',
            'smart-tv-65-inch',
            'smart-tv-55-inch',
        ])->where('status', true)->count());
        $this->assertSame(3, Product::query()->whereIn('slug', [
            'processor-novastar-vx16s',
            'processor-novastar-vx600',
            'processor-novastar-vx400',
        ])->where('status', true)->count());
        $this->assertSame(4, BlogPost::query()->count());
        $this->assertSame(21, Portfolio::query()->count());
        $this->assertSame(0, Product::query()->where('status', false)->count());
        $this->assertSame(0, Product::query()->whereNotNull('price')->count());
        $this->assertDatabaseHas('products', [
            'slug' => 'grandstand',
            'status' => true,
            'price' => null,
            'thumbnail' => 'products/grand-stand.jpg',
        ]);
        $this->assertDatabaseHas('products', ['slug' => 'genset-150-kva', 'status' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'genset-5000-watt', 'status' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'smart-tv-75-inch', 'status' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'processor-novastar-vx16s', 'status' => true]);

        $productCount = Product::query()->count();
        $categoryCount = Category::query()->count();
        $this->artisan('kaizen:production-startup')->assertSuccessful();

        $this->assertSame($productCount, Product::query()->count());
        $this->assertSame($categoryCount, Category::query()->count());
        $this->assertSame(4, BlogPost::query()->count());
        $this->assertSame(21, Portfolio::query()->count());

        $product = Product::query()->firstOrFail();
        Product::query()->update(['price' => 'TEST PRICE']);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee('TEST PRICE');

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee('TEST PRICE');
    }

    public function test_production_bootstrap_continues_when_photo_sync_fails(): void
    {
        $this->artisan('kaizen:production-startup', [
            '--manifest' => 'storage/framework/testing/missing-photo-manifest.json',
        ])->assertSuccessful();

        $this->assertSame(5, Product::query()->whereIn('slug', [
            'genset-150-kva',
            'genset-100-kva',
            'genset-80-kva',
            'genset-60-kva',
            'genset-5000-watt',
        ])->where('status', true)->count());
    }

    public function test_production_startup_preserves_legacy_genset_variants_and_reuses_the_hub_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/genset-photo.jpg', 'genset-photo');
        Storage::disk('public')->put('products/duplicate-gallery-photo.jpg', 'gallery-photo');

        $category = Category::create([
            'name' => 'Genset',
            'slug' => 'genset',
            'type' => 'produk',
            'icon' => 'bolt',
            'is_active' => true,
            'order' => 1,
        ]);

        $productSlugs = [
            'genset-150-kva',
            'genset-100-kva',
            'genset-80-kva',
            'genset-60-kva',
            'genset-5000-watt',
            'genset-5000-watt-sampai-150-kva',
        ];

        foreach ($productSlugs as $order => $slug) {
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $slug,
                'slug' => $slug,
                'thumbnail' => $slug === 'genset-5000-watt-sampai-150-kva' ? 'products/genset-photo.jpg' : null,
                'status' => true,
                'order' => $order + 1,
            ]);

            if ($slug === 'genset-5000-watt-sampai-150-kva') {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'products/duplicate-gallery-photo.jpg',
                    'order' => 1,
                ]);
            }
        }

        $this->artisan('kaizen:production-startup')->assertSuccessful();

        $product = Product::query()->where('slug', 'genset-berbagai-kapasitas')->firstOrFail();

        $this->assertSame('Genset Berbagai Kapasitas', $product->name);
        $this->assertSame('products/genset-photo.jpg', $product->thumbnail);
        $this->assertSame(6, Product::query()->where('category_id', $category->id)->count());
        $this->assertSame(1, $product->images()->where('image_path', 'products/duplicate-gallery-photo.jpg')->count());
        $this->assertDatabaseHas('products', ['slug' => 'genset-150-kva', 'status' => true]);
        $this->assertDatabaseHas('products', ['slug' => 'genset-5000-watt', 'status' => true]);
    }

    public function test_startup_adds_exactly_eleven_photo_products_to_existing_catalog_without_sample_seed_data(): void
    {
        Storage::fake('public');

        $catalog = json_decode((string) file_get_contents(base_path('produk-kaizen-kreasi.json')), true);
        $fixtureName = 'kaizen-catalog-'.uniqid('', true).'.json';
        $fixturePath = 'storage/framework/testing/'.$fixtureName;
        $fixtureAbsolutePath = base_path($fixturePath);
        File::ensureDirectoryExists(dirname($fixtureAbsolutePath));
        File::put($fixtureAbsolutePath, json_encode([
            'products' => array_slice($catalog['products'], 0, 47),
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

        try {
            $this->artisan('kaizen:import-products', ['--file' => $fixturePath])
                ->assertSuccessful();

            $this->assertSame(47, Product::query()->count());

            $this->artisan('kaizen:production-startup')->assertSuccessful();

            $newPhotoProductSlugs = [
                'processor-novastar',
                'grandstand',
                'ringlock-system',
                'q-line',
                'soffa-2-seater',
                'kursi-dan-meja-kantin',
                'meja-barstool',
                'set-kursi-dan-meja-dining-table',
                'set-kursi-dan-meja-kayu-kotak',
                'produksi-gate-dan-branding',
                'produksian-gate',
            ];
            $this->assertSame(11, Product::query()->whereIn('slug', $newPhotoProductSlugs)->count());

            $newPhotoProducts = Product::query()->whereIn('slug', $newPhotoProductSlugs);
            $this->assertSame(0, (clone $newPhotoProducts)->where('status', false)->count());
            $this->assertSame(0, (clone $newPhotoProducts)->whereNotNull('price')->count());
            $this->assertSame(43, Product::query()->whereNotNull('thumbnail')->count());
            $this->assertSame(3, ProductImage::query()->count());
            $this->assertCount(40, Storage::disk('public')->allFiles('products'));
            $this->assertSame(1, Category::query()->where('slug', 'produksi-branding')->count());
            $this->assertSame(0, Product::query()->whereIn('slug', [
                'tenda-roder-span-10m-15m-20m',
                'tenda-sarnafil-3x3m-dan-5x5m',
                'flooring-playwood-18mm',
                'karpet-layak-dan-karpet-baru',
                'led-screen-p26-indoor-dan-outdoor',
                'led-screen-p39-indoor-dan-outdoor',
                'led-cube-p25-48x48cm',
                'genset-5000-watt-sampai-150-kva',
                'sound-system-event',
                'lighting-system',
                'stage-dan-rigging',
                'furniture-dan-seating-event',
                'produksi-booth-backdrop-dan-branding',
                'toilet-portable-dan-standing-ac',
                'ht-baofeng',
            ])->count());

            $this->artisan('kaizen:production-startup')->assertSuccessful();

            $this->assertSame(11, Product::query()->whereIn('slug', $newPhotoProductSlugs)->count());
            $this->assertSame(43, Product::query()->whereNotNull('thumbnail')->count());
            $this->assertSame(3, ProductImage::query()->count());
            $this->assertCount(40, Storage::disk('public')->allFiles('products'));
        } finally {
            File::delete($fixtureAbsolutePath);
        }
    }
}
