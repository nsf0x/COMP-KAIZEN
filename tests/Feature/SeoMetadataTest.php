<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMetadataTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_the_jabodetabek_search_snippet_and_single_primary_heading(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Sewa Perlengkapan Event Jabodetabek | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<meta property="og:title" content="Sewa Perlengkapan Event Jabodetabek | Kaizen Kreasi Indonesia">', false)
            ->assertSee('Sewa Perlengkapan Event Profesional di Jabodetabek')
            ->assertSee('Area Layanan')
            ->assertSee('Cara Memesan Perlengkapan Event');
    }

    public function test_public_pages_render_company_metadata_and_the_configured_canonical_domain(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $this->get('/kontak')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/kontak">', false)
            ->assertSee('<meta property="og:site_name" content="Kaizen Kreasi Indonesia">', false)
            ->assertSee('"@type":"LocalBusiness"', false)
            ->assertSee('https://kaizenkreasi.com/#organization', false);
    }

    public function test_product_pages_render_product_specific_metadata_and_structured_data(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $category = Category::create([
            'name' => 'Tenda',
            'slug' => 'tenda',
            'type' => 'produk',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sewa Tenda Roder',
            'slug' => 'sewa-tenda-roder',
            'description' => 'Tenda untuk kebutuhan pameran dan acara perusahaan.',
            'meta_description' => 'Sewa tenda roder untuk pameran dan event perusahaan di Bekasi.',
            'status' => true,
        ]);

        $response = $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('<title>Sewa Tenda Roder | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<meta name="description" content="Sewa tenda roder untuk pameran dan event perusahaan di Bekasi.">', false)
            ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/produk/sewa-tenda-roder">', false)
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"name":"Sewa Tenda Roder"', false);

        preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $response->getContent(), $jsonLd);
        $schemas = array_map(fn (string $json) => json_decode($json, true, 512, JSON_THROW_ON_ERROR), $jsonLd[1]);

        $this->assertCount(2, $schemas);
        $this->assertSame('LocalBusiness', $schemas[0]['@type']);
        $this->assertSame('Product', $schemas[1]['@type']);
    }

    public function test_robots_and_sitemap_use_the_configured_public_domain(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: https://kaizenkreasi.com/sitemap.xml');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>https://kaizenkreasi.com/</loc>', false)
            ->assertSee('<loc>https://kaizenkreasi.com/produk</loc>', false);
    }

    public function test_blog_pages_render_article_metadata_and_structured_data(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $post = BlogPost::create([
            'title' => 'Tips Persiapan Event Perusahaan',
            'slug' => 'tips-persiapan-event-perusahaan',
            'content' => 'Panduan persiapan kebutuhan event perusahaan.',
            'meta_description' => 'Panduan persiapan event perusahaan dari Kaizen Kreasi Indonesia.',
            'published_at' => now(),
            'is_published' => true,
        ]);

        $response = $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('"@type":"BlogPosting"', false);

        preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $response->getContent(), $jsonLd);
        $schemas = array_map(fn (string $json) => json_decode($json, true, 512, JSON_THROW_ON_ERROR), $jsonLd[1]);

        $this->assertCount(2, $schemas);
        $this->assertSame('BlogPosting', $schemas[1]['@type']);
        $this->assertSame('Tips Persiapan Event Perusahaan', $schemas[1]['headline']);
    }
}
