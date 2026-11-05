<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\BlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategorySeoOverridesTest extends TestCase
{
    use RefreshDatabase;

    public function test_led_screen_category_uses_its_landing_content_faq_schema_and_existing_url(): void
    {
        $response = $this->renderCategory('led-screen-processor', 'LED Screen & Processor', 'LED Screen P3.9');
        $content = $response->getContent();

        $response->assertSee('<title>Sewa LED Screen Jakarta | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('Cara Memilih LED Screen untuk Acara Anda')
            ->assertSee('<a href="/sewa-genset-power-jabodetabek">genset</a>', false)
            ->assertSee('<a href="/blog/perencanaan-kebutuhan-daya-event">artikel perencanaan kebutuhan daya event</a>', false)
            ->assertSee('Pertanyaan tentang LED screen')
            ->assertSee('/blog/perencanaan-kebutuhan-daya-event', false);

        $this->assertLandingMetadata(
            $content,
            'Sewa LED Screen Jakarta dan Jabodetabek',
            'Sewa LED screen Jakarta dan Jabodetabek: pilihan P3.9 dan P2.6 indoor/outdoor, LED cube, processor Novastar. Minta penawaran via WhatsApp.',
            ['sewa led screen jakarta' => 3],
        );
        $this->assertFaqsMatchVisibleContent($content, [
            'Apakah Kaizen Kreasi melayani sewa LED screen Jakarta?' =>
                'Ya. Kami berkantor di Bekasi dan melayani Jakarta, Depok, Bogor, dan Tangerang dengan layanan antar-jemput ke lokasi acara. Untuk acara di luar Jabodetabek, konsultasikan lokasi dan jadwalnya lewat WhatsApp.',
            'Apa perbedaan LED screen indoor dan outdoor?' =>
                'Tipe indoor dipakai di dalam ruangan seperti hall atau ruang pameran, sedangkan tipe outdoor untuk acara di area terbuka. Sampaikan lokasi acara agar tipe yang sesuai dapat dikonfirmasi.',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>'.route('products.category', ['slug' => 'led-screen-processor']).'</loc>', false);
    }

    public function test_genset_category_uses_its_landing_content_faq_schema_and_existing_url(): void
    {
        $response = $this->renderCategory('genset-power', 'Genset & Power', 'Genset 5000 Watt');
        $content = $response->getContent();

        $response->assertSee('<title>Sewa Genset Jakarta dan Jabodetabek | Kaizen Kreasi Indonesia</title>', false)
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('Cara Menentukan Kapasitas Genset untuk Event')
            ->assertSee('<a href="/blog/perencanaan-kebutuhan-daya-event">kebutuhan daya</a>', false)
            ->assertSee('<a href="/sewa-led-screen-processor-jabodetabek">LED screen</a>', false)
            ->assertSee('Pertanyaan tentang genset')
            ->assertSee('/blog/perencanaan-kebutuhan-daya-event', false);

        $this->assertLandingMetadata(
            $content,
            'Sewa Genset Jakarta dan Jabodetabek',
            'Sewa genset Jakarta dan Jabodetabek untuk kebutuhan daya event. Lihat juga sewa led screen jakarta. Antar-jemput. Minta penawaran via WhatsApp.',
            ['sewa genset jakarta' => 3, 'sewa genset jabodetabek' => 1],
        );
        preg_match('/<meta name="description" content="([^"]*)"/', $content, $descriptionMatch);
        $this->assertArrayHasKey(1, $descriptionMatch);
        $description = html_entity_decode($descriptionMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertLessThanOrEqual(150, mb_strlen($description));
        $this->assertStringContainsString('sewa led screen jakarta', mb_strtolower($description));
        $this->assertFaqsMatchVisibleContent($content, [
            'Apakah Kaizen Kreasi melayani sewa genset Jakarta?' =>
                'Ya. Kami berkantor di Bekasi dan melayani Jakarta, Depok, Bogor, dan Tangerang dengan layanan antar-jemput ke lokasi acara. Untuk acara di luar Jabodetabek, konsultasikan lokasi dan jadwalnya lewat WhatsApp.',
            'Kapasitas genset apa saja yang tersedia?' =>
                'Katalog mencantumkan genset 5000 watt, 60 KVA, 80 KVA, 100 KVA, dan 150 KVA. Ketersediaan tiap kapasitas dikonfirmasi sesuai tanggal acara.',
            'Informasi apa yang dibutuhkan untuk meminta penawaran genset?' =>
                'Sampaikan tanggal dan durasi acara, lokasi, daftar peralatan yang akan memakai listrik beserta dayanya, dan kondisi area penempatan genset.',
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>'.route('products.category', ['slug' => 'genset-power']).'</loc>', false);
    }

    private function renderCategory(string $slug, string $name, string $productName): \Illuminate\Testing\TestResponse
    {
        $category = Category::updateOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'type' => 'produk', 'is_active' => true],
        );
        Product::firstOrCreate(
            ['slug' => 'seo-fixture-'.$slug],
            ['category_id' => $category->id, 'name' => $productName, 'status' => true],
        );
        app(BlogPostSeeder::class)->run();

        return $this->get(route('products.category', ['slug' => $slug]))->assertOk();
    }

    /**
     * @param  array<string, int>  $keywordMinimums
     */
    private function assertLandingMetadata(string $content, string $heading, string $description, array $keywordMinimums): void
    {
        preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/is', $content, $headings);
        $this->assertCount(1, $headings[1]);
        $this->assertSame($heading, html_entity_decode(trim(strip_tags($headings[1][0])), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        preg_match('/<meta name="description" content="([^"]*)"/', $content, $descriptionMatch);
        $this->assertArrayHasKey(1, $descriptionMatch);
        $renderedDescription = html_entity_decode($descriptionMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertSame($description, $renderedDescription);
        $this->assertLessThanOrEqual(160, mb_strlen($renderedDescription));
        $this->assertFalse(str_ends_with($renderedDescription, '...'));

        preg_match_all('/<title>.*?<\/title>|<meta\s+(?:property="(?:og:title)"|name="twitter:title")[^>]*>/is', $content, $titleMetadata);
        $this->assertDoesNotMatchRegularExpression('/&amp;amp;/i', implode(' ', $titleMetadata[0]));

        $lowerContent = mb_strtolower($content);
        foreach ($keywordMinimums as $phrase => $minimum) {
            $this->assertGreaterThanOrEqual($minimum, substr_count($lowerContent, mb_strtolower($phrase)), $phrase);
        }
    }

    /**
     * @param  array<string, string>  $expectedFaqs
     */
    private function assertFaqsMatchVisibleContent(string $content, array $expectedFaqs): void
    {
        preg_match_all('/<article class="mb-3">\s*<h3[^>]*>(.*?)<\/h3>\s*<p[^>]*>(.*?)<\/p>\s*<\/article>/s', $content, $visibleFaqMatches, PREG_SET_ORDER);
        $visibleFaqs = [];
        foreach ($visibleFaqMatches as $faq) {
            $question = html_entity_decode(trim(strip_tags($faq[1])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $answer = html_entity_decode(trim(strip_tags($faq[2])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $visibleFaqs[$question] = $answer;
        }
        $this->assertSame($expectedFaqs, $visibleFaqs);

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
        $schemaFaqs = [];
        foreach ($faqPage['mainEntity'] as $faq) {
            $schemaFaqs[$faq['name']] = $faq['acceptedAnswer']['text'];
        }
        $this->assertSame($expectedFaqs, $schemaFaqs);
    }
}
