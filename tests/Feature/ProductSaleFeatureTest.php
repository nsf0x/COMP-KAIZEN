<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SaleProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class ProductSaleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_products_default_to_rental_only_and_show_the_existing_rental_flow(): void
    {
        $category = $this->createCategory('sound-system', 'Sound System');
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sound System Test',
            'slug' => 'sound-system-test',
            'status' => true,
        ]);

        $this->assertFalse($product->fresh()->bisa_dijual);
        $this->assertTrue($product->fresh()->bisa_disewa);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Sewa via WhatsApp')
            ->assertDontSee('Beli via WhatsApp');

        $this->get(route('products.category', ['slug' => 'sound-system']))
            ->assertOk()
            ->assertSee('Sound System Test')
            ->assertSee('Sewa via WhatsApp')
            ->assertDontSee('Beli via WhatsApp');

        $detailResponse = $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Sewa via WhatsApp')
            ->assertDontSee('Beli via WhatsApp');

        $detailResponse->assertSee($product->whatsapp_rental_url, false);
    }

    public function test_sale_rental_flag_migration_can_be_rolled_back_and_reapplied(): void
    {
        $migration = require database_path('migrations/2026_11_08_000001_add_sale_and_rental_flags_to_products_table.php');

        $migration->down();
        $this->assertFalse(Schema::hasColumn('products', 'bisa_dijual'));
        $this->assertFalse(Schema::hasColumn('products', 'bisa_disewa'));

        $migration->up();
        $this->assertTrue(Schema::hasColumn('products', 'bisa_dijual'));
        $this->assertTrue(Schema::hasColumn('products', 'bisa_disewa'));
    }

    public function test_product_flags_control_badges_messages_and_sale_product_schema(): void
    {
        $category = $this->createCategory('led-lighting', 'LED Lighting');
        $saleOnly = $this->createProduct($category, 'Beam Redsun', 'beam-redsun', true, false);
        $both = $this->createProduct($category, 'Par Redsun', 'par-redsun', true, true);

        $saleResponse = $this->get(route('products.show', $saleOnly))
            ->assertOk()
            ->assertSee('Dijual')
            ->assertSee('Harga hubungi kami via WhatsApp')
            ->assertSee('Beli via WhatsApp')
            ->assertSee('href="'.$saleOnly->whatsapp_sale_url.'"', false)
            ->assertDontSee('href="'.$saleOnly->whatsapp_rental_url.'"', false);

        $saleResponse->assertSee('"@type":"Product"', false)
            ->assertDontSee('"offers"', false)
            ->assertDontSee('"price"', false)
            ->assertDontSee('"availability"', false)
            ->assertDontSee('Tersedia untuk dibeli maupun disewa');

        $this->get(route('products.show', $both))
            ->assertOk()
            ->assertSee('Dijual &amp; Disewakan', false)
            ->assertSee('Tersedia untuk dibeli maupun disewa')
            ->assertSee('Beli via WhatsApp')
            ->assertSee('Sewa via WhatsApp')
            ->assertSee('Harga hubungi kami via WhatsApp');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Kami Juga Menjual LED Lighting &amp; Sound System', false)
            ->assertSee($both->name)
            ->assertSee('Beli via WhatsApp');
    }

    public function test_admin_can_create_sale_only_products_but_products_cannot_disable_both_options(): void
    {
        $category = $this->createCategory('led-lighting', 'LED Lighting');
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name' => 'Produk Jual Saja',
                'slug' => 'produk-jual-saja',
                'category_id' => $category->id,
                'images' => [],
                'bisa_dijual' => true,
                'bisa_disewa' => false,
                'status' => true,
                'is_featured' => false,
                'order' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', [
            'slug' => 'produk-jual-saja',
            'bisa_dijual' => true,
            'bisa_disewa' => false,
        ]);

        $this->expectException(ValidationException::class);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Tanpa Pilihan',
            'slug' => 'produk-tanpa-pilihan',
            'bisa_dijual' => false,
            'bisa_disewa' => false,
        ]);
    }

    public function test_sales_listing_filters_only_products_that_can_be_sold_and_shows_coming_soon_for_sound_system(): void
    {
        $ledCategory = $this->createCategory('led-lighting', 'LED Lighting');
        $soundCategory = $this->createCategory('sound-system', 'Sound System');
        $saleProduct = $this->createProduct($ledCategory, 'Beam Redsun 440 BSW', 'beam-redsun-440-bsw', true, true);
        $rentalOnly = $this->createProduct($soundCategory, 'Sound System Rental', 'sound-system-rental', false, true);

        $this->get(route('products.sales'))
            ->assertOk()
            ->assertSee('Jual LED Lighting &amp; Sound System Event', false)
            ->assertSee($saleProduct->name)
            ->assertSee('Sound System — Segera hadir.')
            ->assertDontSee($rentalOnly->name)
            ->assertSee('<link rel="canonical" href="http://localhost/jual">', false);

        $this->get(route('products.sales', ['kategori' => 'sound-system']))
            ->assertOk()
            ->assertSee('Jual Sound System Event Bekasi')
            ->assertSee('Segera hadir')
            ->assertDontSee($saleProduct->name);

        $this->get(route('products.sales', ['kategori' => 'led-lighting']))
            ->assertOk()
            ->assertSee($saleProduct->name)
            ->assertDontSee($rentalOnly->name);
    }

    public function test_sales_seeder_is_idempotent_and_does_not_duplicate_a_matching_product(): void
    {
        $this->seed(CategorySeeder::class);
        $ledCategoryId = Category::where('slug', 'led-lighting')->value('id');
        $existing = Product::create([
            'category_id' => $ledCategoryId,
            'name' => 'Polo Spot 350',
            'slug' => 'polo-spot-350',
            'bisa_disewa' => true,
            'status' => true,
        ]);
        $similarExisting = Product::create([
            'category_id' => $ledCategoryId,
            'name' => 'Beam Redsun 440 BSW LED',
            'slug' => 'beam-redsun-440-bsw-led',
            'bisa_disewa' => true,
            'status' => true,
        ]);

        $this->seed(SaleProductSeeder::class);
        $this->seed(SaleProductSeeder::class);

        $this->assertSame(1, Product::where('slug', 'polo-spot-350')->count());
        $this->assertTrue($existing->fresh()->bisa_dijual);
        $this->assertTrue($similarExisting->fresh()->bisa_dijual);
        $this->assertDatabaseMissing('products', ['slug' => 'beam-redsun-440-bsw']);
        $this->assertSame(11, Product::where('bisa_dijual', true)->count());
        $this->assertSame(11, Product::where('category_id', Category::where('slug', 'led-lighting')->value('id'))->count());
        $this->assertSame(11, Product::where('bisa_dijual', true)->where('bisa_disewa', true)->count());
        $this->assertNull(Product::where('slug', 'beam-redsun-440-bsw')->value('specification'));
        $this->assertNull(Product::where('slug', 'beam-redsun-440-bsw')->value('thumbnail'));
    }

    public function test_sitemap_includes_the_sales_listing_without_changing_rental_urls(): void
    {
        $category = $this->createCategory('sound-system', 'Sound System');
        $this->createProduct($category, 'Sound System Sewa', 'sound-system-sewa', false, true);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee('<loc>http://localhost/jual</loc>', false)
            ->assertSee('<loc>http://localhost/produk</loc>', false)
            ->assertSee('<loc>http://localhost/sewa-sound-system-jabodetabek</loc>', false);
    }

    private function createCategory(string $slug, string $name): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'type' => 'produk',
            'icon' => 'tag',
            'is_active' => true,
        ]);
    }

    private function createProduct(Category $category, string $name, string $slug, bool $sale, bool $rental): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => $slug,
            'bisa_dijual' => $sale,
            'bisa_disewa' => $rental,
            'status' => true,
        ]);
    }
}
