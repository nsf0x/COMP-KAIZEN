<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryMetadataTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_titles_escape_encoded_ampersands_once_in_all_title_metadata(): void
    {
        $category = Category::create([
            'name' => 'Furniture &amp; Seating',
            'slug' => 'encoded-title-fixture',
            'type' => 'produk',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Fixture product',
            'slug' => 'encoded-title-fixture-product',
            'status' => true,
        ]);

        $this->get(route('products.category', ['slug' => $category->slug]))
            ->assertOk()
            ->assertSee('<title>Sewa Furniture &amp; Seating | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<meta property="og:title" content="Sewa Furniture &amp; Seating | Kaizen Kreasi Indonesia">', false)
            ->assertSee('<meta name="twitter:title" content="Sewa Furniture &amp; Seating | Kaizen Kreasi Indonesia">', false);
    }

    public function test_default_category_description_ends_at_a_complete_sentence_within_160_characters(): void
    {
        $category = Category::create([
            'name' => 'Category with a long display name for description truncation',
            'slug' => 'long-description-fixture',
            'type' => 'produk',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Fixture product',
            'slug' => 'long-description-fixture-product',
            'status' => true,
        ]);

        $content = $this->get(route('products.category', ['slug' => $category->slug]))
            ->assertOk()
            ->getContent();

        preg_match('/<meta name="description" content="([^"]*)"/', $content, $matches);
        $this->assertArrayHasKey(1, $matches);
        $description = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertLessThanOrEqual(160, mb_strlen($description));
        $this->assertStringEndsWith('.', $description);
        $this->assertFalse(str_ends_with($description, '...'));
    }
}
