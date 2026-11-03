<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryMergeMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_overlapping_categories_merge_without_deleting_products_or_category_records(): void
    {
        $tenda = $this->createCategory('Tenda', 'tenda');
        $flooring = $this->createCategory('Flooring & Karpet', 'flooring-karpet');
        $led = $this->createCategory('LED Screen', 'led-screen');
        $products = collect([
            $this->createProduct($tenda, 'Tenda Roder'),
            $this->createProduct($flooring, 'Flooring'),
            $this->createProduct($led, 'LED Screen P3.9'),
        ]);

        $migration = require database_path('migrations/2026_11_02_000002_merge_overlapping_product_categories.php');
        $migration->up();

        $tendaTarget = Category::query()->where('slug', 'tenda-flooring')->firstOrFail();
        $ledTarget = Category::query()->where('slug', 'led-screen-processor')->firstOrFail();

        $this->assertSame('Tenda & Flooring', $tendaTarget->name);
        $this->assertSame('LED Screen & Processor', $ledTarget->name);
        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseHas('products', ['id' => $products[0]->id, 'category_id' => $tendaTarget->id]);
        $this->assertDatabaseHas('products', ['id' => $products[1]->id, 'category_id' => $tendaTarget->id]);
        $this->assertDatabaseHas('products', ['id' => $products[2]->id, 'category_id' => $ledTarget->id]);
        $this->assertDatabaseHas('categories', ['id' => $tenda->id, 'is_active' => false]);
        $this->assertDatabaseHas('categories', ['id' => $flooring->id, 'is_active' => false]);
        $this->assertDatabaseHas('categories', ['id' => $led->id, 'is_active' => false]);
        $this->assertDatabaseCount('categories', 5);

        $migration->up();
        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseCount('categories', 5);
    }

    private function createCategory(string $name, string $slug): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'type' => 'produk',
            'is_active' => true,
        ]);
    }

    private function createProduct(Category $category, string $name): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => $name,
            'status' => true,
        ]);
    }
}
