<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\CreateProduct as CreateProductPage;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductCategoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_category_creation_uses_new_category_when_no_existing_choice_is_selected(): void
    {
        $page = new CreateProduct;

        $method = new \ReflectionMethod($page, 'mutateFormDataBeforeCreate');
        $method->setAccessible(true);

        $data = [
            'name' => 'Produk Uji Baru',
            'slug' => 'produk-uji-baru',
            'category_id' => null,
            'custom_category' => 'Kategori Baru Manual',
            'price' => 'Rp 1.500.000',
            'status' => true,
            'is_featured' => false,
            'order' => 1,
        ];

        $result = $method->invoke($page, $data);

        $this->assertNotNull($result['category_id']);
        $this->assertDatabaseHas('categories', [
            'name' => 'Kategori Baru Manual',
            'slug' => 'kategori-baru-manual',
        ]);
        $this->assertSame(Category::query()->where('slug', 'kategori-baru-manual')->value('id'), $result['category_id']);
    }

    public function test_create_and_edit_pages_redirect_to_their_resource_index_after_saving(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $category = Category::create([
            'name' => 'Kategori Awal',
            'slug' => 'kategori-awal',
            'type' => 'produk',
            'is_active' => true,
            'order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Awal',
            'slug' => 'produk-awal',
            'status' => true,
            'is_featured' => false,
            'order' => 1,
        ]);

        Livewire::test(CreateCategory::class)
            ->assertActionExists('backToIndex')
            ->fillForm([
                'name' => 'Kategori Baru',
                'slug' => 'kategori-baru',
                'type' => 'produk',
                'is_active' => true,
                'order' => 2,
            ])
            ->call('create')
            ->assertRedirect(CategoryResource::getUrl('index'))
            ->assertNotified();

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->assertActionExists('backToIndex')
            ->fillForm([
                'name' => 'Kategori Diperbarui',
                'slug' => 'kategori-diperbarui',
                'type' => 'produk',
                'is_active' => true,
                'order' => 1,
            ])
            ->call('save')
            ->assertRedirect(CategoryResource::getUrl('index'))
            ->assertNotified();

        Livewire::test(CreateProductPage::class)
            ->assertActionExists('backToIndex')
            ->fillForm([
                'name' => 'Produk Baru',
                'slug' => 'produk-baru',
                'category_id' => $category->id,
                'images' => [],
                'price' => null,
                'status' => true,
                'is_featured' => false,
                'order' => 2,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect(ProductResource::getUrl('index'))
            ->assertNotified();

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertActionExists('backToIndex')
            ->fillForm([
                'name' => 'Produk Diperbarui',
                'slug' => 'produk-diperbarui',
                'category_id' => $category->id,
                'price' => null,
                'status' => true,
                'is_featured' => false,
                'order' => 1,
            ])
            ->call('save')
            ->assertRedirect(ProductResource::getUrl('index'))
            ->assertNotified();
    }

    public function test_create_another_still_saves_and_keeps_the_form_open(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Kategori Pertama',
                'slug' => 'kategori-pertama',
                'type' => 'produk',
                'is_active' => true,
                'order' => 1,
            ])
            ->call('createAnother')
            ->assertNoRedirect()
            ->assertSet('record', null)
            ->assertNotified();

        $this->assertDatabaseHas('categories', ['slug' => 'kategori-pertama']);
    }

    public function test_back_action_redirects_when_clean_and_confirms_when_form_is_dirty(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $cleanCreatePage = Livewire::test(CreateCategory::class);

        $this->assertNotNull($cleanCreatePage->instance()->initialFormStateHash);
        $hasUnsavedFormChanges = new \ReflectionMethod($cleanCreatePage->instance(), 'hasUnsavedFormChanges');
        $hasUnsavedFormChanges->setAccessible(true);
        $this->assertFalse($hasUnsavedFormChanges->invoke($cleanCreatePage->instance()));

        $cleanCreatePage
            ->mountAction('backToIndex')
            ->assertRedirect(CategoryResource::getUrl('index'));

        $dirtyPage = Livewire::test(CreateCategory::class)
            ->set('data.name', 'Kategori Belum Disimpan')
            ->mountAction('backToIndex')
            ->assertActionMounted('backToIndex')
            ->assertSee('Perubahan belum disimpan. Yakin ingin kembali?')
            ->assertSee('Tetap di sini')
            ->assertSee('Kembali tanpa menyimpan');

        $dirtyPage
            ->call('unmountAction')
            ->assertNoRedirect();

        Livewire::test(CreateCategory::class)
            ->set('data.name', 'Kategori Tidak Disimpan')
            ->mountAction('backToIndex')
            ->callMountedAction()
            ->assertRedirect(CategoryResource::getUrl('index'));

        $this->assertDatabaseMissing('categories', ['slug' => 'kategori-tidak-disimpan']);
    }
}
