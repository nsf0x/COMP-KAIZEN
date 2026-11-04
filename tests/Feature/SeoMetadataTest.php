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
            ->assertSee('Sewa Perlengkapan Event di Jabodetabek')
            ->assertSee('Area Layanan')
            ->assertSee('Cara Memesan Perlengkapan Event');
    }

    public function test_google_analytics_requires_production_environment_and_a_measurement_id(): void
    {
        $this->app['env'] = 'testing';
        config(['services.google_analytics.measurement_id' => 'G-TEST123456']);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('googletagmanager.com/gtag/js', false)
            ->assertDontSee('G-TEST123456', false);

        $this->app['env'] = 'production';

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://www.googletagmanager.com/gtag/js?id=G-TEST123456', false)
            ->assertSee('gtag(\'config\', "G-TEST123456")', false);

        config(['services.google_analytics.measurement_id' => null]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('googletagmanager.com/gtag/js', false);
    }

    public function test_public_pages_render_company_metadata_and_the_configured_canonical_domain(): void
    {
        config([
            'app.url' => 'https://kaizenkreasi.com',
            'company.description' => 'Penyedia penyewaan perlengkapan event untuk wilayah Jabodetabekdan pengiriman ke seluruh Indonesia bagi event skala besar.',
            'company.contact_description' => 'Konsultasi dan pemesanan melalui WhatsApp 24jam setiap hari.',
        ]);

        $this->get('/kontak')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/kontak">', false)
            ->assertSee('<meta property="og:site_name" content="Kaizen Kreasi Indonesia">', false)
            ->assertSee('"@type":"LocalBusiness"', false)
            ->assertSee('"@type":"ContactPoint"', false)
            ->assertSee('"description":"Konsultasi dan pemesanan melalui WhatsApp 24 jam setiap hari."', false)
            ->assertDontSee('"description":"Konsultasi dan pemesanan melalui WhatsApp 24jam setiap hari."', false)
            ->assertSee('<p class="small">Penyedia penyewaan perlengkapan event untuk wilayah Jabodetabek dan pengiriman ke seluruh Indonesia bagi event skala besar.</p>', false)
            ->assertSee('"description":"Penyedia penyewaan perlengkapan event untuk wilayah Jabodetabek dan pengiriman ke seluruh Indonesia bagi event skala besar."', false)
            ->assertDontSee('Jabodetabekdan pengiriman', false)
            ->assertSee('"opens":"08:00","closes":"17:00"', false)
            ->assertSee('"opens":"08:00","closes":"14:00"', false)
            ->assertSee('https://kaizenkreasi.com/#organization', false);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_product_pages_render_product_specific_metadata_and_structured_data(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $thumbnail = 'products/tenda-roder-'.bin2hex(random_bytes(6)).'.jpg';
        $sidecarPath = public_path('storage/'.preg_replace('/\.jpg$/', '.webp', $thumbnail));
        $sidecarDirectory = dirname($sidecarPath);
        $createdSidecarDirectory = ! is_dir($sidecarDirectory);

        if ($createdSidecarDirectory) {
            mkdir($sidecarDirectory, 0777, true);
        }

        file_put_contents($sidecarPath, 'temporary webp fixture');

        try {
            $product = $this->createTendaProduct($thumbnail);

            $response = $this->get(route('products.show', $product))
                ->assertOk()
                ->assertSee('<title>Sewa Tenda Roder | Kaizen Kreasi Indonesia</title>', false)
                ->assertSee('<meta name="description" content="Sewa tenda roder untuk pameran dan event perusahaan di Bekasi.">', false)
                ->assertSee('<meta property="og:description" content="Sewa tenda roder untuk pameran dan event perusahaan di Bekasi.">', false)
                ->assertSee('"description":"Sewa tenda roder untuk pameran dan event perusahaan di Bekasi."', false)
                ->assertDontSee('perusahaandi Bekasi', false)
                ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/produk/sewa-tenda-roder">', false)
                ->assertSee('"@type":"Service"', false)
                ->assertSee('"serviceType":"Tenda"', false)
                ->assertSee('"@type":"BreadcrumbList"', false)
                ->assertSee('"name":"Sewa Tenda Roder"', false)
                ->assertSee('<source srcset="http://localhost/storage/'.str_replace('.jpg', '.webp', $thumbnail).'" type="image/webp">', false)
                ->assertSee('alt="Perlengkapan event: Sewa Tenda Roder"', false)
                ->assertSee('width="900" height="600"', false)
                ->assertSee('loading="eager"', false);

            preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $response->getContent(), $jsonLd);
            $schemas = array_map(fn (string $json) => json_decode($json, true, 512, JSON_THROW_ON_ERROR), $jsonLd[1]);

            $this->assertCount(3, $schemas);
            $this->assertSame('LocalBusiness', $schemas[0]['@type']);
            $this->assertSame('Service', $schemas[1]['@type']);
            $this->assertSame('BreadcrumbList', $schemas[2]['@type']);
            $this->assertArrayNotHasKey('offers', $schemas[1]);
        } finally {
            if (is_file($sidecarPath)) {
                unlink($sidecarPath);
            }

            if ($createdSidecarDirectory && is_dir($sidecarDirectory)) {
                rmdir($sidecarDirectory);
            }
        }
    }

    public function test_product_image_uses_original_fallback_without_a_webp_sidecar(): void
    {
        $thumbnail = 'products/tenda-roder-'.bin2hex(random_bytes(6)).'.jpg';
        $product = $this->createTendaProduct($thumbnail);
        $sidecarPath = public_path('storage/'.preg_replace('/\.jpg$/', '.webp', $thumbnail));
        $expectedSource = '<source srcset="'.asset('storage/'.str_replace('.jpg', '.webp', $thumbnail)).'" type="image/webp">';
        $this->assertFileDoesNotExist($sidecarPath);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee($expectedSource, false)
            ->assertSee('src="'.asset('storage/'.$thumbnail).'"', false)
            ->assertSee('alt="Perlengkapan event: Sewa Tenda Roder"', false);
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

    public function test_category_pages_have_clean_metadata_and_legacy_category_filters_redirect(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $category = Category::create([
            'name' => 'Tenda',
            'slug' => 'tenda',
            'type' => 'produk',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Tenda Roder',
            'slug' => 'tenda-roder',
            'description' => 'Tenda untuk kebutuhan event.',
            'status' => true,
        ]);

        $this->get(route('products.category', ['slug' => $category->slug]))
            ->assertOk()
            ->assertSee('<title>Sewa Tenda | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/sewa-tenda-jabodetabek">', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('Tenda Roder')
            ->assertDontSee('Rp ');

        $this->get('/produk?category=tenda')
            ->assertMovedPermanently()
            ->assertRedirect('/sewa-tenda-jabodetabek');

        $this->get('/produk/kategori/tenda')
            ->assertMovedPermanently()
            ->assertRedirect('/sewa-tenda-jabodetabek');

        $this->get('/produk?search=tenda')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,follow">', false)
            ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/produk">', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>https://kaizenkreasi.com/sewa-tenda-jabodetabek</loc>', false);
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

        $this->assertCount(3, $schemas);
        $article = collect($schemas)->firstWhere('@type', 'BlogPosting');
        $this->assertSame('BreadcrumbList', collect($schemas)->firstWhere('@type', 'BreadcrumbList')['@type']);
        $this->assertSame('https://schema.org', $article['@context']);
        $this->assertSame('Tips Persiapan Event Perusahaan', $article['headline']);
    }

    private function createTendaProduct(string $thumbnail): Product
    {
        $category = Category::create([
            'name' => 'Tenda',
            'slug' => 'tenda',
            'type' => 'produk',
            'is_active' => true,
        ]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Sewa Tenda Roder',
            'slug' => 'sewa-tenda-roder',
            'description' => 'Tenda untuk kebutuhan pameran dan acara perusahaan.',
            'meta_description' => 'Sewa tenda roder untuk pameran dan event perusahaandi Bekasi.',
            'thumbnail' => $thumbnail,
            'status' => true,
        ]);
    }
}
