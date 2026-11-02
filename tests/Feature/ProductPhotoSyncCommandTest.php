<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductPhotoSyncCommandTest extends TestCase
{
    use RefreshDatabase;

    private string $fixtureDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->fixtureDirectory = storage_path('framework/testing/product-photo-sync');
        File::deleteDirectory($this->fixtureDirectory);
        File::ensureDirectoryExists($this->fixtureDirectory);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->fixtureDirectory);

        parent::tearDown();
    }

    public function test_photo_is_attached_to_an_existing_product_without_a_photo(): void
    {
        $product = $this->createProduct('existing-product');
        $manifest = $this->writeManifest([
            ['file' => 'photo.jpg', 'products' => [['slug' => 'existing-product']]],
        ]);

        $this->runSync($manifest);

        $this->assertSame('products/photo.jpg', $product->fresh()->thumbnail);
        Storage::disk('public')->assertExists('products/photo.jpg');
    }

    public function test_existing_photo_file_is_never_overwritten(): void
    {
        Storage::disk('public')->put('products/manual.jpg', 'manual-photo');
        $product = $this->createProduct('product-with-photo', 'products/manual.jpg');
        $manifest = $this->writeManifest([
            ['file' => 'new-photo.jpg', 'products' => [['slug' => 'product-with-photo']]],
        ]);

        $this->runSync($manifest);

        $this->assertSame('products/manual.jpg', $product->fresh()->thumbnail);
        $this->assertSame('manual-photo', Storage::disk('public')->get('products/manual.jpg'));
        Storage::disk('public')->assertMissing('products/new-photo.jpg');
    }

    public function test_missing_thumbnail_file_is_replaced_with_a_repository_photo(): void
    {
        $product = $this->createProduct('product-with-missing-photo', 'products/deleted-after-deploy.jpg');
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/deleted-gallery-photo.jpg',
            'order' => 1,
        ]);
        $manifest = $this->writeManifest([
            ['file' => 'restored.jpg', 'products' => [['slug' => 'product-with-missing-photo']]],
        ]);

        $this->runSync($manifest);

        $this->assertSame('products/restored.jpg', $product->fresh()->thumbnail);
        $this->assertSame('products/restored.jpg', $product->images()->firstOrFail()->image_path);
        Storage::disk('public')->assertExists('products/restored.jpg');
    }

    public function test_new_product_and_category_are_created_only_once_from_manifest_metadata(): void
    {
        $manifest = $this->writeManifest([
            [
                'file' => 'new-item.jpg',
                'products' => [[
                    'slug' => 'new-photo-item',
                    'name' => 'New Photo Item',
                    'category' => 'Photo Equipment',
                ]],
            ],
        ]);

        $this->runSync($manifest);
        $this->runSync($manifest);

        $this->assertSame(1, Product::query()->where('slug', 'new-photo-item')->count());
        $this->assertSame(1, Category::query()->where('slug', 'photo-equipment')->count());
        $this->assertDatabaseHas('products', [
            'slug' => 'new-photo-item',
            'name' => 'New Photo Item',
            'status' => true,
            'price' => null,
            'thumbnail' => 'products/new-item.jpg',
        ]);
    }

    public function test_running_twice_does_not_duplicate_products_or_gallery_images_and_reuses_a_photo(): void
    {
        $firstProduct = $this->createProduct('first-product');
        $secondProduct = $this->createProduct('second-product');
        $manifest = $this->writeManifest([
            ['file' => 'front.jpg', 'products' => [['slug' => 'first-product'], ['slug' => 'second-product']]],
            ['file' => 'side.jpg', 'products' => [['slug' => 'first-product']]],
        ]);

        $this->runSync($manifest);
        $this->runSync($manifest);

        $this->assertSame('products/front.jpg', $firstProduct->fresh()->thumbnail);
        $this->assertSame('products/front.jpg', $secondProduct->fresh()->thumbnail);
        $this->assertSame(2, Product::query()->count());
        $this->assertSame(1, ProductImage::query()->where('product_id', $firstProduct->id)->count());
        $this->assertSame('products/side.jpg', $firstProduct->images()->firstOrFail()->image_path);
        Storage::disk('public')->assertExists('products/front.jpg');
        Storage::disk('public')->assertExists('products/side.jpg');
    }

    public function test_catering_product_and_category_are_never_created(): void
    {
        $manifest = $this->writeManifest([
            [
                'file' => 'catering.jpg',
                'products' => [[
                    'slug' => 'paket-katering',
                    'name' => 'Paket Katering',
                    'category' => 'Katering',
                ]],
            ],
        ]);

        $this->runSync($manifest);

        $this->assertSame(0, Product::query()->count());
        $this->assertSame(0, Category::query()->count());
    }

    public function test_existing_catering_product_is_reported_and_left_unchanged(): void
    {
        $category = Category::create([
            'name' => 'Katering',
            'slug' => 'katering',
            'type' => 'produk',
            'icon' => 'tag',
            'order' => 1,
            'is_active' => false,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Catering Buffet',
            'slug' => 'catering-buffet',
            'description' => 'Existing user data',
            'price' => 'Existing price',
            'thumbnail' => null,
            'status' => false,
            'is_featured' => true,
            'order' => 7,
        ]);
        $manifest = $this->writeManifest([
            ['file' => 'catering.jpg', 'products' => [[
                'slug' => 'catering-buffet',
                'name' => 'Changed Name',
                'category' => 'Changed Category',
            ]]],
        ]);

        $this->artisan('kaizen:sync-product-photos', ['--manifest' => $manifest])
            ->expectsOutputToContain('Existing catering products left unchanged: Catering Buffet')
            ->assertSuccessful();

        $this->assertSame('Catering Buffet', $product->fresh()->name);
        $this->assertSame('Existing price', $product->fresh()->price);
        $this->assertFalse($product->fresh()->status);
        $this->assertSame('Katering', $category->fresh()->name);
        Storage::disk('public')->assertMissing('products/catering.jpg');
    }

    private function createProduct(string $slug, ?string $thumbnail = null): Product
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-category'],
            [
                'name' => 'Test Category',
                'type' => 'produk',
                'icon' => 'tag',
                'order' => 1,
                'is_active' => true,
            ],
        );

        return Product::create([
            'category_id' => $category->id,
            'name' => str($slug)->replace('-', ' ')->title()->toString(),
            'slug' => $slug,
            'thumbnail' => $thumbnail,
            'status' => true,
            'is_featured' => false,
            'order' => 1,
        ]);
    }

    private function writeManifest(array $photos): string
    {
        foreach ($photos as $photo) {
            File::put($this->fixtureDirectory.DIRECTORY_SEPARATOR.$photo['file'], 'photo-'.$photo['file']);
        }

        File::put(
            $this->fixtureDirectory.DIRECTORY_SEPARATOR.'manifest.json',
            json_encode(['version' => 1, 'photos' => $photos], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT),
        );

        return 'storage/framework/testing/product-photo-sync/manifest.json';
    }

    private function runSync(string $manifest): void
    {
        $this->artisan('kaizen:sync-product-photos', ['--manifest' => $manifest])
            ->assertSuccessful();
    }
}
