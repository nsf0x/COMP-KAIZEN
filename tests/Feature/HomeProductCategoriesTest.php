<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeProductCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_ordering_steps_have_a_dark_mode_card_style(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('class="order-step-card h-100 p-4 rounded-4 shadow-sm"', false)
            ->assertSee('html.dark .order-step-card', false)
            ->assertSee('background: #202230', false)
            ->assertSee('color: #e5e6ec', false);
    }

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

        $this->assertSame(2, substr_count($response->getContent(), '/sewa-smart-tv-jabodetabek'));
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

        $offsets = array_map(fn (string $slug): int|false => strpos($homeContent, '/sewa-'.$slug.'-jabodetabek'), $slugs);
        $this->assertNotContains(false, $offsets);
        $this->assertSame($offsets, collect($offsets)->sort()->values()->all());
    }

    public function test_homepage_uses_category_display_names_and_only_mentions_rental_terms_with_matching_products(): void
    {
        $catalog = [
            [
                'slug' => 'lighting-stage',
                'name' => 'Lighting & Stage',
                'product' => 'Stage dan Rigging',
                'description' => 'Panggung dan rigging untuk event.',
                'display_name' => 'Panggung & Backdrop',
            ],
            [
                'slug' => 'furniture-seating',
                'name' => 'Furniture & Seating',
                'product' => 'Kursi dan Meja Event',
                'description' => 'Furniture untuk acara.',
                'display_name' => 'Furniture (Kursi & Meja)',
            ],
            [
                'slug' => 'tenda-flooring',
                'name' => 'Tenda & Flooring',
                'product' => 'Karpet Event',
                'description' => 'Karpet untuk kebutuhan acara.',
                'display_name' => 'Tenda & Karpet Flooring',
            ],
        ];

        foreach ($catalog as $order => $item) {
            $category = Category::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'type' => 'produk',
                    'order' => $order + 1,
                    'is_active' => true,
                ],
            );

            Product::create([
                'category_id' => $category->id,
                'name' => $item['product'],
                'slug' => $item['slug'].'-product',
                'description' => $item['description'],
                'status' => true,
                'is_featured' => false,
                'order' => $order + 1,
            ]);
        }

        $content = html_entity_decode($this->get(route('home'))->assertOk()->getContent());

        foreach ($catalog as $item) {
            $this->assertStringContainsString($item['display_name'], $content);
            $this->assertStringContainsString('/sewa-'.$item['slug'].'-jabodetabek', $content);
        }

        foreach (['rental alat event', 'sewa kursi dan meja', 'sewa panggung', 'sewa karpet'] as $keyword) {
            $this->assertSame(1, substr_count(strtolower($content), $keyword), 'Expected "'.$keyword.'" exactly once.');
        }

        $this->assertSame(1, substr_count($content, 'Perlengkapan Event untuk Beragam Kebutuhan'));
    }

    public function test_homepage_omits_rental_terms_when_matching_products_are_not_in_the_catalog(): void
    {
        $catalog = [
            ['slug' => 'lighting-stage', 'name' => 'Lighting & Stage', 'product' => 'Lighting System', 'description' => 'Lampu untuk event.'],
            ['slug' => 'furniture-seating', 'name' => 'Furniture & Seating', 'product' => 'Sofa Event', 'description' => 'Sofa untuk acara.'],
            ['slug' => 'tenda-flooring', 'name' => 'Tenda & Flooring', 'product' => 'Tenda Roder', 'description' => 'Tenda untuk acara.'],
        ];

        foreach ($catalog as $order => $item) {
            $category = Category::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'type' => 'produk',
                    'order' => $order + 1,
                    'is_active' => true,
                ],
            );

            Product::create([
                'category_id' => $category->id,
                'name' => $item['product'],
                'slug' => $item['slug'].'-product',
                'description' => $item['description'],
                'status' => true,
                'is_featured' => false,
                'order' => $order + 1,
            ]);
        }

        $content = $this->get(route('home'))->assertOk()->getContent();

        $content = strtolower($content);

        $this->assertStringContainsString('rental alat event', $content);
        $this->assertStringNotContainsString('sewa kursi dan meja', $content);
        $this->assertStringNotContainsString('sewa panggung', $content);
        $this->assertStringNotContainsString('sewa karpet', $content);
    }

    public function test_homepage_omits_all_catalog_rental_terms_when_no_products_are_available(): void
    {
        $content = $this->get(route('home'))->assertOk()->getContent();

        foreach (['rental alat event', 'sewa kursi dan meja', 'sewa panggung', 'sewa karpet'] as $keyword) {
            $this->assertStringNotContainsString($keyword, strtolower($content));
        }
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
