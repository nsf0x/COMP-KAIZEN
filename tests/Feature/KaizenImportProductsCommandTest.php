<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $this->artisan('kaizen:bootstrap-production')->assertSuccessful();

        $this->assertSame(51, Product::query()->count());
        $this->assertSame(11, Category::query()->count());
        $this->assertSame(0, Product::query()->where('status', false)->count());
        $this->assertSame(0, Product::query()->whereNotNull('price')->count());

        $this->artisan('kaizen:bootstrap-production')->assertSuccessful();

        $this->assertSame(51, Product::query()->count());
        $this->assertSame(11, Category::query()->count());

        $product = Product::query()->firstOrFail();
        Product::query()->update(['price' => 'TEST PRICE']);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee('TEST PRICE');

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee('TEST PRICE');
    }
}
