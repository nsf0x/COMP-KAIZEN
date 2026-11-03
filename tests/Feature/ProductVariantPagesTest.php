<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_hub_pages_link_to_each_available_variant(): void
    {
        $groups = [
            [
                'category' => ['Genset', 'genset'],
                'hub' => ['Genset Berbagai Kapasitas', 'genset-berbagai-kapasitas'],
                'variants' => [
                    ['Genset 150 KVA', 'genset-150-kva'],
                    ['Genset 100 KVA', 'genset-100-kva'],
                    ['Genset 80 KVA', 'genset-80-kva'],
                    ['Genset 60 KVA', 'genset-60-kva'],
                    ['Genset 5000 Watt', 'genset-5000-watt'],
                ],
            ],
            [
                'category' => ['Smart TV', 'smart-tv'],
                'hub' => ['Smart TV Berbagai Ukuran', 'smart-tv-berbagai-ukuran'],
                'variants' => [
                    ['Smart TV 75 Inch', 'smart-tv-75-inch'],
                    ['Smart TV 65 Inch', 'smart-tv-65-inch'],
                    ['Smart TV 55 Inch', 'smart-tv-55-inch'],
                ],
            ],
            [
                'category' => ['LED Screen & Processor', 'led-screen-processor'],
                'hub' => ['Processor Novastar', 'processor-novastar'],
                'variants' => [
                    ['Processor Novastar VX16S', 'processor-novastar-vx16s'],
                    ['Processor Novastar VX600', 'processor-novastar-vx600'],
                    ['Processor Novastar VX400', 'processor-novastar-vx400'],
                ],
            ],
        ];

        foreach ($groups as $group) {
            [$categoryName, $categorySlug] = $group['category'];
            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                [
                    'name' => $categoryName,
                    'type' => 'produk',
                    'icon' => 'tag',
                    'is_active' => true,
                ],
            );

            $hub = Product::create([
                'category_id' => $category->id,
                'name' => $group['hub'][0],
                'slug' => $group['hub'][1],
                'status' => true,
            ]);

            foreach ($group['variants'] as [$name, $slug]) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => $slug,
                    'status' => true,
                ]);
            }

            $response = $this->get(route('products.show', $hub));
            $response->assertOk();
            foreach ($group['variants'] as [$name, $slug]) {
                $response->assertSee($name)->assertSee(route('products.show', $slug));
            }
        }
    }

    public function test_old_combined_product_urls_redirect_permanently_to_the_hubs(): void
    {
        $redirects = [
            '/produk/genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt' => '/produk/genset-berbagai-kapasitas',
            '/produk/genset-5000-watt-sampai-150-kva' => '/produk/genset-berbagai-kapasitas',
            '/produk/smart-tv-75-inch-65-inch-dan-55-inch' => '/produk/smart-tv-berbagai-ukuran',
            '/produk/processor-novastar-vx16s-vx600-vx400' => '/produk/processor-novastar',
        ];

        foreach ($redirects as $oldPath => $newPath) {
            $this->get($oldPath)->assertRedirect($newPath, 301);
        }
    }
}
