<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_category_creation_uses_new_category_when_no_existing_choice_is_selected(): void
    {
        $page = new CreateProduct();

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
}
