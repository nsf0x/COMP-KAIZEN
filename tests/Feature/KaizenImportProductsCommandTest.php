<?php

namespace Tests\Feature;

use App\Models\Category;
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

        $this->assertTrue(Category::query()->where('slug', 'tenda')->exists());
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

        $this->assertSame(62, Product::query()->count());
        $this->assertSame(12, Category::query()->count());
        $this->assertSame(0, Product::query()->where('status', false)->count());
        $this->assertSame(0, Product::query()->whereNotNull('price')->count());
        $this->assertDatabaseHas('products', [
            'slug' => 'grandstand',
            'status' => true,
            'price' => null,
            'thumbnail' => 'products/grand-stand.jpg',
        ]);

        $this->artisan('kaizen:production-startup')->assertSuccessful();

        $this->assertSame(62, Product::query()->count());
        $this->assertSame(12, Category::query()->count());

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

        $this->assertSame(62, Product::query()->count());
        $this->assertSame(12, Category::query()->count());
    }

    public function test_startup_adds_exactly_eleven_photo_products_to_existing_fifty_one_without_sample_seed_data(): void
    {
        Storage::fake('public');

        $catalog = json_decode((string) file_get_contents(base_path('produk-kaizen-kreasi.json')), true);
        $fixtureName = 'kaizen-catalog-51-'.uniqid('', true).'.json';
        $fixturePath = 'storage/framework/testing/'.$fixtureName;
        $fixtureAbsolutePath = base_path($fixturePath);
        File::ensureDirectoryExists(dirname($fixtureAbsolutePath));
        File::put($fixtureAbsolutePath, json_encode([
            'products' => array_slice($catalog['products'], 0, 51),
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

        try {
            $this->artisan('kaizen:import-products', ['--file' => $fixturePath])
                ->assertSuccessful();

            $this->assertSame(51, Product::query()->count());
            $this->assertSame(11, Category::query()->count());

            $this->artisan('kaizen:production-startup')->assertSuccessful();

            $this->assertSame(62, Product::query()->count());
            $this->assertSame(12, Category::query()->count());
            $newPhotoProductSlugs = [
                'processor-novastar-vx16s-vx600-vx400',
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
            $this->assertSame(52, Product::query()->whereNotNull('thumbnail')->count());
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

            $this->assertSame(62, Product::query()->count());
            $this->assertSame(12, Category::query()->count());
            $this->assertSame(52, Product::query()->whereNotNull('thumbnail')->count());
            $this->assertSame(3, ProductImage::query()->count());
            $this->assertCount(40, Storage::disk('public')->allFiles('products'));
        } finally {
            File::delete($fixtureAbsolutePath);
        }
    }
}
