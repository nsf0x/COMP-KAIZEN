<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeProductCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_includes_active_categories_with_active_products_without_requiring_a_photo(): void
    {
        $category = $this->createCategoryWithProduct('Smart TV', 'smart-tv', 5, null);
        $this->createCategoryWithProduct('Kategori Tanpa Produk Aktif', 'no-active-products', 6, null, true, false);
        $this->createCategoryWithProduct('Kategori Nonaktif', 'inactive-category', 7, null, false, true);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Smart TV')
            ->assertDontSee('Kategori Tanpa Produk Aktif')
            ->assertDontSee('Kategori Nonaktif');

        $this->assertSame(1, substr_count($response->getContent(), 'category=smart-tv'));
    }

    public function test_homepage_and_product_filters_show_the_same_ordered_twelve_categories(): void
    {
        $slugs = [];

        for ($index = 1; $index <= 12; $index++) {
            $slug = sprintf('katalog-%02d', $index);
            $this->createCategoryWithProduct('Kategori '.sprintf('%02d', $index), $slug, 1, $index % 2 === 0 ? null : 'products/category.jpg');
            $slugs[] = $slug;
        }

        $this->createCategoryWithProduct('Kategori Kosong', 'empty-category', 0, null, true, false);

        $homeResponse = $this->get(route('home'))->assertOk();
        $productResponse = $this->get(route('products.index'))->assertOk();
        $homeContent = $homeResponse->getContent();
        $productContent = $productResponse->getContent();

        $this->assertSame(12, substr_count($homeContent, 'class="card category-card'));
        $this->assertSame(13, substr_count($productContent, 'name="category"'));
        $this->assertStringNotContainsString('Kategori Kosong', $homeContent);
        $this->assertStringNotContainsString('Kategori Kosong', $productContent);

        $offsets = array_map(fn (string $slug): int|false => strpos($homeContent, 'category='.$slug), $slugs);
        $this->assertNotContains(false, $offsets);
        $this->assertSame($offsets, collect($offsets)->sort()->values()->all());
    }

    private function createCategoryWithProduct(
        string $name,
        string $slug,
        int $order,
        ?string $thumbnail,
        bool $categoryIsActive = true,
        bool $productIsActive = true,
    ): Category {
        $category = Category::create([
            'name' => $name,
            'slug' => $slug,
            'type' => 'produk',
            'icon' => 'tag',
            'order' => $order,
            'is_active' => $categoryIsActive,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => $name.' Product',
            'slug' => $slug.'-product',
            'thumbnail' => $thumbnail,
            'status' => $productIsActive,
            'is_featured' => false,
            'order' => $order,
        ]);

        return $category;
    }
}
