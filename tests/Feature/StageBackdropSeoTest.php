<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\BlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageBackdropSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_stage_backdrop_page_uses_its_seo_content_faq_schema_and_existing_url(): void
    {
        config(['app.url' => 'https://kaizenkreasi.com']);

        $category = Category::create([
            'name' => 'Stage & Backdrop',
            'slug' => 'stage-backdrop',
            'type' => 'produk',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Stage triplek',
            'slug' => 'fixture-stage-triplek',
            'status' => true,
        ]);
        app(BlogPostSeeder::class)->run();

        $response = $this->get(route('products.category', ['slug' => $category->slug]))->assertOk()
            ->assertSee('<title>Sewa Panggung Event Jakarta | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<link rel="canonical" href="https://kaizenkreasi.com/sewa-stage-backdrop-jabodetabek">', false)
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('Cara Menyiapkan Panggung untuk Acara Anda')
            ->assertSee('sound system dan lighting</a>', false)
            ->assertSee('LED screen</a>', false)
            ->assertSee('branding</a>', false)
            ->assertSee('href="/blog/persiapan-panggung-backdrop-branding"', false)
            ->assertSee('<a href="/sewa-sound-system-lighting-pendingin-jabodetabek">sound system dan lighting</a>', false)
            ->assertSee('<a href="/sewa-led-screen-processor-jabodetabek">LED screen</a>', false)
            ->assertSee('<a href="/sewa-produksi-branding-jabodetabek">branding</a>', false)
            ->assertSee('/sewa-stage-backdrop-jabodetabek', false)
            ->assertSee('"@type":"FAQPage"', false);

        $content = $response->getContent();
        preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/is', $content, $headings);
        $this->assertCount(1, $headings[1]);
        $this->assertSame(
            'Sewa Panggung Event Jakarta dan Jabodetabek',
            html_entity_decode(trim(strip_tags($headings[1][0])), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        );

        $keywordOccurrences = substr_count(strtolower($content), 'sewa panggung event jakarta');
        $this->assertGreaterThanOrEqual(3, $keywordOccurrences);

        preg_match('/<meta name="description" content="([^"]*)"/', $content, $descriptionMatch);
        $this->assertArrayHasKey(1, $descriptionMatch);
        $description = html_entity_decode($descriptionMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertSame(
            'Sewa panggung event Jakarta dan Jabodetabek: stage triplek, stage melaminto, backdrop, grandstand. Antar-jemput. Minta penawaran via WhatsApp.',
            $description,
        );
        $this->assertLessThanOrEqual(160, mb_strlen($description));
        $this->assertFalse(str_ends_with($description, '...'));

        foreach ([
            'Apakah Kaizen Kreasi melayani sewa panggung event Jakarta?',
            'Jenis panggung apa saja yang tersedia?',
            'Informasi apa yang dibutuhkan untuk meminta penawaran panggung?',
        ] as $question) {
            $response->assertSee($question)
                ->assertSee('"name":"'.$question.'"', false);
        }

        preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $content, $jsonScripts);
        $faqPage = null;
        foreach ($jsonScripts[1] as $jsonScript) {
            $structuredData = json_decode($jsonScript, true);
            if (($structuredData['@type'] ?? null) === 'FAQPage') {
                $faqPage = $structuredData;
                break;
            }
        }
        $this->assertNotNull($faqPage);
        $this->assertCount(3, $faqPage['mainEntity']);

        $this->assertLessThan(
            strpos($content, '<div class="row g-4">'),
            strpos($content, 'Cara Menyiapkan Panggung untuk Acara Anda'),
        );
        $this->assertLessThan(
            strpos($content, 'Pertanyaan tentang panggung dan backdrop'),
            strpos($content, '<div class="row g-4">'),
        );
        $this->assertLessThan(
            strpos($content, 'Artikel terkait'),
            strpos($content, 'Pertanyaan tentang panggung dan backdrop'),
        );

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>https://kaizenkreasi.com/sewa-stage-backdrop-jabodetabek</loc>', false);
    }
}
