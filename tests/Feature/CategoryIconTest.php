<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use App\Support\CategoryIcon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryIconTest extends TestCase
{
    use RefreshDatabase;

    public function test_automatic_mapping_manual_override_and_invalid_key_fallback(): void
    {
        $automaticMappings = [
            'tenda' => 'tent',
            'flooring-karpet' => 'layers',
            'toilet-portable' => 'toilet',
            'genset' => 'bolt',
            'led-screen' => 'screen',
            'smart-tv' => 'tv',
            'sound-system-lighting-pendingin' => 'speaker',
            'stage-backdrop' => 'stage',
            'furniture' => 'chair',
            'partisi' => 'partition',
            'perangkat-komunikasi' => 'walkie-talkie',
            'produksi-branding' => 'brush',
        ];

        foreach ($automaticMappings as $slug => $expectedIcon) {
            $category = new Category(['name' => str($slug)->replace('-', ' ')->title()->toString(), 'slug' => $slug]);
            $this->assertSame($expectedIcon, CategoryIcon::keyFor($category), $slug);
        }

        $this->assertCount(39, config('category-icons.icons'));

        $legacyAliases = [
            'display' => ['screen', 'bi bi-display'],
            'lightbulb' => ['lamp', 'bi bi-lightbulb-fill'],
            'booth' => ['shop', 'bi bi-shop-window'],
            'support' => ['headset', 'bi bi-headset'],
        ];

        foreach ($legacyAliases as $legacyKey => [$expectedKey, $expectedClass]) {
            $legacyCategory = new Category(['name' => 'Category', 'slug' => 'unmapped-category', 'icon' => $legacyKey]);
            $this->assertSame($expectedKey, CategoryIcon::keyFor($legacyCategory));
            $this->assertSame($expectedClass, CategoryIcon::classFor($legacyCategory));
        }

        $smartTv = new Category(['name' => 'Smart TV', 'slug' => 'smart-tv', 'icon' => 'auto']);
        $this->assertSame('tv', CategoryIcon::keyFor($smartTv));
        $this->assertSame('bi bi-tv-fill', CategoryIcon::classFor($smartTv));

        $tent = new Category(['name' => 'Tenda', 'slug' => 'tenda', 'icon' => 'tent']);
        $chair = new Category(['name' => 'Furniture', 'slug' => 'furniture', 'icon' => 'chair']);
        $toilet = new Category(['name' => 'Toilet Portable', 'slug' => 'toilet-portable', 'icon' => 'toilet']);
        $this->assertSame('fa-solid fa-tent', CategoryIcon::classFor($tent));
        $this->assertSame('fa-solid fa-chair', CategoryIcon::classFor($chair));
        $this->assertSame('fa-solid fa-toilet', CategoryIcon::classFor($toilet));

        $emptySmartTv = new Category(['name' => 'Smart TV', 'slug' => 'smart-tv', 'icon' => null]);
        $this->assertSame('tv', CategoryIcon::keyFor($emptySmartTv));

        $manualOverride = new Category(['name' => 'Smart TV', 'slug' => 'smart-tv', 'icon' => 'speaker']);
        $this->assertSame('speaker', CategoryIcon::keyFor($manualOverride));
        $this->assertSame('bi bi-speaker-fill', CategoryIcon::classFor($manualOverride));

        $invalidKey = new Category(['name' => 'Produksi & Branding', 'slug' => 'produksi-branding', 'icon' => 'retired-icon']);
        $this->assertSame('brush', CategoryIcon::keyFor($invalidKey));

        $unknownCategory = new Category(['name' => 'Kategori Baru', 'slug' => 'kategori-baru', 'icon' => null]);
        $this->assertSame('package', CategoryIcon::keyFor($unknownCategory));
        $this->assertSame('bi bi-box-seam', CategoryIcon::classFor($unknownCategory));
    }

    public function test_create_and_edit_forms_save_icon_keys_and_keep_list_redirects(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Kategori TV',
                'slug' => 'kategori-tv',
                'icon' => 'tv',
                'type' => 'produk',
                'is_active' => true,
                'order' => 1,
            ])
            ->call('create')
            ->assertRedirect(CategoryResource::getUrl('index'))
            ->assertNotified();

        $category = Category::query()->where('slug', 'kategori-tv')->firstOrFail();
        $this->assertSame('tv', $category->icon);

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'name' => 'Kategori TV',
                'slug' => 'kategori-tv',
                'icon' => 'screen',
                'type' => 'produk',
                'is_active' => true,
                'order' => 1,
            ])
            ->call('save')
            ->assertRedirect(CategoryResource::getUrl('index'))
            ->assertNotified();

        $this->assertSame('screen', $category->fresh()->icon);
    }

    public function test_category_table_renders_a_resolved_icon(): void
    {
        $category = Category::create([
            'name' => 'Tenda',
            'slug' => 'tenda',
            'icon' => null,
            'type' => 'produk',
            'is_active' => true,
            'order' => 1,
        ]);

        Livewire::test(ListCategories::class)
            ->assertSee('fa-solid fa-tent', false)
            ->assertSee('Otomatis: Tenda');
    }

    public function test_homepage_renders_automatic_font_awesome_category_icon(): void
    {
        $category = Category::create([
            'name' => 'Tenda',
            'slug' => 'tenda',
            'icon' => null,
            'type' => 'produk',
            'is_active' => true,
            'order' => 1,
        ]);

        $category->products()->create([
            'name' => 'Tenda Roder',
            'slug' => 'tenda-roder',
            'status' => true,
            'is_featured' => false,
            'order' => 1,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('fa-solid fa-tent', false);
    }

    public function test_font_awesome_stylesheet_is_loaded_in_public_and_admin_views(): void
    {
        $stylesheet = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css';

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($stylesheet, false);

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee($stylesheet, false);
    }
}
