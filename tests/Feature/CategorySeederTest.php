<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategorySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_run_preserves_catalog_categories_and_products_not_in_the_default_category_list(): void
    {
        $category = Category::create([
            'name' => 'Flooring & Karpet',
            'slug' => 'flooring-karpet',
            'type' => 'produk',
            'is_active' => true,
            'order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Flooring',
            'slug' => 'flooring',
            'status' => true,
            'is_featured' => false,
            'order' => 1,
        ]);

        app(CategorySeeder::class)->run();

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'slug' => 'flooring-karpet']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'slug' => 'flooring']);
    }
}
