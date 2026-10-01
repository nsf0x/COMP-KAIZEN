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

        $this->assertGreaterThan(0, Product::query()->count());
    }
}
