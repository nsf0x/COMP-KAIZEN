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

    public function test_homepage_uses_category_display_names_and_preserves_existing_category_urls(): void
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

    }

    public function test_homepage_contains_all_required_search_phrases_and_one_primary_heading(): void
    {
        $phrases = [
            'sewa perlengkapan event jabodetabek',
            'rental alat event jakarta',
            'sewa tenda event jabodetabek',
            'sewa genset jakarta',
            'sewa led screen jakarta',
            'sewa sound system event jabodetabek',
            'sewa toilet portable jabodetabek',
            'sewa panggung event jakarta',
            'sewa karpet event jabodetabek',
            'sewa tenda pameran jakarta',
            'sewa perlengkapan event bekasi',
            'sewa lighting event jakarta',
            'sewa kursi meja event jabodetabek',
            'sewa genset jabodetabek',
            'event equipment rental indonesia',
        ];

        $response = $this->get(route('home'))->assertOk();
        $content = strtolower($response->getContent());

        foreach ($phrases as $phrase) {
            $this->assertStringContainsString($phrase, $content);
        }

        preg_match_all('/<h1\b/i', $response->getContent(), $headings);
        $this->assertCount(1, $headings[0]);
        $response->assertSee('<h2 class="section-title">Mitra Sewa Perlengkapan Event Jabodetabek untuk Berbagai Acara</h2>', false);
    }

    public function test_existing_stage_and_furniture_category_urls_use_the_requested_display_names(): void
    {
        foreach ([
            ['slug' => 'stage-backdrop', 'name' => 'Stage & Backdrop', 'display_name' => 'Panggung & Backdrop'],
            ['slug' => 'furniture', 'name' => 'Furniture', 'display_name' => 'Furniture (Kursi & Meja)'],
        ] as $order => $item) {
            $category = Category::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'type' => 'produk',
                'order' => $order + 1,
                'is_active' => true,
            ]);

            Product::create([
                'category_id' => $category->id,
                'name' => $item['name'].' Product',
                'slug' => $item['slug'].'-product',
                'status' => true,
                'is_featured' => false,
                'order' => $order + 1,
            ]);

            $expectedHeading = $item['slug'] === 'stage-backdrop'
                ? 'Sewa Panggung Event Jakarta dan Jabodetabek'
                : 'Perlengkapan '.$item['display_name'].' untuk Event';

            $this->get(route('products.category', ['slug' => $item['slug']]))
                ->assertOk()
                ->assertSee($expectedHeading)
                ->assertSee('aria-current="page">'.htmlspecialchars($item['display_name'], ENT_QUOTES, 'UTF-8').'</li>', false)
                ->assertSee('/sewa-'.$item['slug'].'-jabodetabek', false);
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
