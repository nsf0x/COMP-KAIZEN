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

    public function test_run_updates_display_names_without_changing_category_slugs(): void
    {
        $expectedNames = [];

        foreach ([
            'lighting-stage' => ['old' => 'Lighting & Stage', 'new' => 'Panggung & Backdrop'],
            'furniture-seating' => ['old' => 'Furniture & Seating', 'new' => 'Furniture (Kursi & Meja)'],
            'tenda-flooring' => ['old' => 'Tenda & Flooring', 'new' => 'Tenda & Karpet Flooring'],
        ] as $slug => $names) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $names['old'],
                    'type' => 'produk',
                    'is_active' => true,
                ],
            );

            $expectedNames[$slug] = $names['new'];
        }

        app(CategorySeeder::class)->run();

        foreach ($expectedNames as $slug => $name) {
            $this->assertDatabaseHas('categories', ['slug' => $slug, 'name' => $name]);
        }
    }
}
