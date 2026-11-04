<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\BlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryContentLinkingTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_published_article_has_two_or_three_working_catalog_links(): void
    {
        $categorySlugs = [
            'tenda-flooring',
            'furniture-seating',
            'furniture',
            'sound-system-lighting-pendingin',
            'led-screen-processor',
            'smart-tv',
            'genset',
            'genset-power',
            'stage-backdrop',
            'produksi-branding',
            'booth-branding',
            'event-support',
            'perangkat-komunikasi',
            'toilet-portable',
        ];

        foreach ($categorySlugs as $index => $slug) {
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => Str::headline($slug),
                    'type' => 'produk',
                    'icon' => 'tag',
                    'order' => $index + 1,
                    'is_active' => true,
                ],
            );
            Product::firstOrCreate(
                ['slug' => 'fixture-'.$slug],
                [
                    'category_id' => $category->id,
                    'name' => 'Fixture '.$slug,
                    'status' => true,
                ],
            );
        }

        $productSlugs = [
            'sound-system',
            'sound-system-event',
            'flooring',
            'flooring-playwood-18mm',
            'led-screen-p39-indoor',
            'smart-tv-75-inch',
            'genset-150-kva',
            'genset-5000-watt',
            'backdrop',
            'produksi-gate-dan-branding',
            'handy-talkie-baofeng',
            'toilet-portable-toilet-kering',
        ];
        $productCategory = Category::firstOrFail();
        foreach ($productSlugs as $slug) {
            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $productCategory->id,
                    'name' => Str::headline($slug),
                    'status' => true,
                ],
            );
        }

        app(BlogPostSeeder::class)->run();

        foreach (BlogPost::published()->get() as $article) {
            $response = $this->get(route('blog.show', $article))->assertOk();
            preg_match('/id="related-catalog-heading".*?<ul[^>]*>(.*?)<\/ul>/s', $response->getContent(), $list);
            $linkCount = isset($list[1]) ? substr_count($list[1], '<li') : 0;

            $this->assertGreaterThanOrEqual(2, $linkCount, $article->slug);
            $this->assertLessThanOrEqual(3, $linkCount, $article->slug);
        }
    }

    public function test_combined_category_pages_show_each_subtopic_faq_and_relevant_articles(): void
    {
        app(BlogPostSeeder::class)->run();

        foreach ([
            [
                'slug' => 'tenda-flooring',
                'products' => [
                    ['Tenda Roder', 'tenda-roder'],
                    ['Flooring Playwood', 'flooring-playwood'],
                    ['Karpet Event', 'karpet-event'],
                ],
                'topics' => ['Tenda untuk area acara', 'Flooring dan karpet'],
                'questions' => ['Informasi apa yang perlu disiapkan untuk menanyakan tenda?', 'Bagaimana menentukan kebutuhan flooring atau karpet?'],
            ],
            [
                'slug' => 'led-screen-processor',
                'products' => [
                    ['LED Screen P2.6 Indoor', 'led-screen-p26-indoor'],
                    ['Processor Novastar VX16S', 'processor-novastar-vx16s'],
                ],
                'topics' => ['LED screen', 'Video processor'],
                'questions' => ['Informasi apa yang dibutuhkan untuk menanyakan LED screen?', 'Model processor Novastar apa yang tercantum di katalog?'],
            ],
        ] as $fixture) {
            $category = Category::firstOrCreate(
                ['slug' => $fixture['slug']],
                [
                    'name' => $fixture['slug'] === 'tenda-flooring' ? 'Tenda & Flooring' : 'LED Screen & Processor',
                    'type' => 'produk',
                    'icon' => 'tag',
                    'is_active' => true,
                ],
            );

            foreach ($fixture['products'] as [$name, $slug]) {
                Product::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'status' => true,
                    ],
                );
            }

            $response = $this->get(route('products.category', ['slug' => $category->slug]))->assertOk()
                ->assertSee('Artikel terkait')
                ->assertSee('"@type":"FAQPage"', false);

            foreach ([...$fixture['topics'], ...$fixture['questions']] as $text) {
                $response->assertSee($text);
            }
            foreach ($fixture['questions'] as $question) {
                $response->assertSee('"name":"'.$question.'"', false);
            }
            $response->assertDontSee('Rp ');

            preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $response->getContent(), $jsonScripts);
            $faqSchema = null;
            foreach ($jsonScripts[1] as $jsonScript) {
                $structuredData = json_decode($jsonScript, true);
                if (($structuredData['@type'] ?? null) === 'FAQPage') {
                    $faqSchema = $structuredData;
                    break;
                }
            }
            $this->assertNotNull($faqSchema);
            $this->assertSame(
                $fixture['questions'],
                array_column($faqSchema['mainEntity'], 'name'),
            );

            preg_match_all('/<details>\s*<summary[^>]*>(.*?)<\/summary>\s*<p[^>]*>(.*?)<\/p>/s', $response->getContent(), $visibleFaqs, PREG_SET_ORDER);
            $visibleQuestions = array_map(fn (array $faq): string => html_entity_decode(trim(strip_tags($faq[1])), ENT_QUOTES | ENT_HTML5, 'UTF-8'), $visibleFaqs);
            $visibleAnswers = array_map(fn (array $faq): string => html_entity_decode(trim(strip_tags($faq[2])), ENT_QUOTES | ENT_HTML5, 'UTF-8'), $visibleFaqs);
            $schemaAnswers = array_column(array_column($faqSchema['mainEntity'], 'acceptedAnswer'), 'text');
            $this->assertSame($visibleQuestions, array_column($faqSchema['mainEntity'], 'name'));
            $this->assertSame($visibleAnswers, $schemaAnswers);
        }
    }

    public function test_category_without_visible_faqs_does_not_emit_empty_faq_schema(): void
    {
        $category = Category::create([
            'name' => 'Genset',
            'slug' => 'genset',
            'type' => 'produk',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Genset 5000 Watt',
            'slug' => 'fixture-genset-5000-watt',
            'status' => true,
        ]);

        $this->get(route('products.category', ['slug' => 'genset']))
            ->assertOk()
            ->assertDontSee('"@type":"FAQPage"', false);
    }

    public function test_category_pages_link_to_published_articles_for_current_catalog_categories(): void
    {
        app(BlogPostSeeder::class)->run();

        $categoryArticles = [
            'tenda-flooring' => ['perlengkapan-event-pernikahan-checklist-tenda-peralatan'],
            'led-screen-processor' => ['persiapan-display-visual-event'],
            'genset' => ['perencanaan-kebutuhan-daya-event'],
            'genset-power' => ['perencanaan-kebutuhan-daya-event'],
            'smart-tv' => ['persiapan-display-visual-event'],
            'sound-system' => ['perlengkapan-event-pernikahan-checklist-tenda-peralatan'],
            'sound-system-lighting-pendingin' => ['perlengkapan-event-pernikahan-checklist-tenda-peralatan'],
            'lighting-stage' => ['perlengkapan-event-pernikahan-checklist-tenda-peralatan'],
            'stage-backdrop' => ['persiapan-panggung-backdrop-branding'],
            'furniture' => ['perlengkapan-event-ulang-tahun'],
            'furniture-seating' => ['perlengkapan-event-ulang-tahun'],
            'partisi' => ['persiapan-panggung-backdrop-branding'],
            'booth-branding' => ['persiapan-panggung-backdrop-branding'],
            'perangkat-komunikasi' => ['koordinasi-dan-fasilitas-event'],
            'produksi-branding' => ['persiapan-panggung-backdrop-branding'],
            'toilet-portable' => ['koordinasi-dan-fasilitas-event'],
            'event-support' => ['koordinasi-dan-fasilitas-event'],
        ];

        foreach ($categoryArticles as $slug => $articleSlugs) {
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => Str::headline($slug),
                    'type' => 'produk',
                    'icon' => 'tag',
                    'is_active' => true,
                ],
            );
            Product::firstOrCreate(
                ['slug' => 'page-fixture-'.$slug],
                [
                    'category_id' => $category->id,
                    'name' => 'Fixture '.$slug,
                    'status' => true,
                ],
            );

            $response = $this->get(route('products.category', ['slug' => $slug]))
                ->assertOk()
                ->assertSee('Artikel terkait');

            foreach ($articleSlugs as $articleSlug) {
                $response->assertSee(route('blog.show', BlogPost::query()->where('slug', $articleSlug)->firstOrFail()));
            }
        }
    }
}
