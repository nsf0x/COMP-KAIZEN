<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsolidateKaizenCatalogCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_removes_and_merges_requested_catalog_products(): void
    {
        $category = Category::create([
            'name' => 'Catalog',
            'slug' => 'catalog',
            'type' => 'produk',
            'icon' => 'tag',
            'order' => 1,
            'is_active' => true,
        ]);

        $products = [
            ['Tenda Sarnafil + Flooring + Karpet Layak 3x3m', 'tenda-sarnafil-flooring-karpet-layak-3x3m'],
            ['Tenda Sarnafil + Flooring + Karpet Layak 5x5m', 'tenda-sarnafil-flooring-karpet-layak-5x5m'],
            ['LED Screen P2.6 Curve Outdoor', 'led-screen-p26-curve-outdoor'],
            ['Operator LED (Biasa)', 'operator-led-biasa'],
            ['Flooring + Karpet Layak', 'flooring-karpet-layak'],
            ['Smart TV 75 Inch', 'smart-tv-75-inch'],
            ['Smart TV 65 Inch', 'smart-tv-65-inch'],
            ['Smart TV 55 Inch', 'smart-tv-55-inch'],
            ['Partisi R8 Ruang 2 x 2m', 'partisi-r8-ruang-2-x-2m'],
            ['Partisi R8 Ruang 2 x 3m', 'partisi-r8-ruang-2-x-3m'],
            ['Partisi R8 Ruang 3 x 3m', 'partisi-r8-ruang-3-x-3m'],
            ['Partisi R8 Ruang 3 x 4m', 'partisi-r8-ruang-3-x-4m'],
        ];

        foreach ($products as $order => [$name, $slug]) {
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => $slug,
                'description' => 'Old description',
                'is_featured' => $order === 5,
                'status' => true,
                'order' => $order + 1,
            ]);

            if (str_starts_with($slug, 'smart-tv-') || str_starts_with($slug, 'partisi-r8-')) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $slug.'.jpg',
                    'order' => 0,
                ]);
            }
        }

        $this->artisan('kaizen:consolidate-catalog')->assertSuccessful();

        $this->assertDatabaseHas('products', [
            'slug' => 'flooring',
            'name' => 'Flooring',
        ]);
        $this->assertDatabaseHas('products', [
            'slug' => 'smart-tv-75-inch-65-inch-dan-55-inch',
            'name' => 'Smart TV 75 Inch, 65 Inch, dan 55 Inch',
        ]);
        $this->assertDatabaseHas('products', [
            'slug' => 'partisi-r8',
            'name' => 'Partisi R8',
        ]);
        $this->assertSame(3, ProductImage::query()->where('product_id', Product::query()->where('slug', 'smart-tv-75-inch-65-inch-dan-55-inch')->value('id'))->count());
        $this->assertSame(4, ProductImage::query()->where('product_id', Product::query()->where('slug', 'partisi-r8')->value('id'))->count());
        $this->assertSame(1, Product::query()->where('slug', 'smart-tv-75-inch-65-inch-dan-55-inch')->count());
        $this->assertSame(0, Product::query()->whereIn('slug', [
            'tenda-sarnafil-flooring-karpet-layak-3x3m',
            'tenda-sarnafil-flooring-karpet-layak-5x5m',
            'led-screen-p26-curve-outdoor',
            'operator-led-biasa',
            'smart-tv-75-inch',
            'smart-tv-65-inch',
            'smart-tv-55-inch',
            'partisi-r8-ruang-2-x-2m',
            'partisi-r8-ruang-2-x-3m',
            'partisi-r8-ruang-3-x-3m',
            'partisi-r8-ruang-3-x-4m',
        ])->count());

        $this->artisan('kaizen:consolidate-catalog')->assertSuccessful();

        $this->assertSame(1, Product::query()->where('slug', 'smart-tv-75-inch-65-inch-dan-55-inch')->count());
        $this->assertSame(3, ProductImage::query()->where('product_id', Product::query()->where('slug', 'smart-tv-75-inch-65-inch-dan-55-inch')->value('id'))->count());
        $this->assertSame(4, ProductImage::query()->where('product_id', Product::query()->where('slug', 'partisi-r8')->value('id'))->count());
    }
}
