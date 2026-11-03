<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Database\Seeders\BlogPostSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeoContentAccuracyTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_do_not_publish_placeholder_clients_or_testimonials(): void
    {
        $this->seed([ClientSeeder::class, TestimonialSeeder::class]);

        $this->assertDatabaseCount('clients', 0);
        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_blog_seeder_uses_unattributed_content_without_unverified_prices_or_dates(): void
    {
        $this->seed(BlogPostSeeder::class);

        $this->assertDatabaseCount('blog_posts', 7);
        $this->assertDatabaseHas('blog_posts', [
            'slug' => 'faktor-biaya-sewa-tenda-pernikahan',
            'author' => '',
            'published_at' => null,
        ]);
        foreach (BlogPost::query()->pluck('content') as $content) {
            $this->assertStringNotContainsString('Rp ', $content);
        }

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertDontSee('Admin')
            ->assertDontSee('Rp ');
    }

    public function test_blog_article_omits_unverified_author_and_publication_date_from_page_and_schema(): void
    {
        $post = BlogPost::create([
            'title' => 'Perlengkapan Event Pernikahan',
            'slug' => 'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
            'content' => 'Checklist perlengkapan acara.',
            'author' => '',
            'published_at' => null,
            'is_published' => true,
        ]);

        $response = $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertDontSee('Admin');

        preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $response->getContent(), $jsonLd);
        $schemas = array_map(fn (string $json) => json_decode($json, true, 512, JSON_THROW_ON_ERROR), $jsonLd[1]);
        $articleSchema = collect($schemas)->firstWhere('@type', 'BlogPosting');

        $this->assertArrayNotHasKey('author', $articleSchema);
        $this->assertArrayNotHasKey('datePublished', $articleSchema);
    }

    public function test_previous_blog_urls_redirect_to_the_rewritten_articles(): void
    {
        $this->get('/blog/10-tips-memilih-dekorasi-pernikahan-yang-berkesan')
            ->assertMovedPermanently()
            ->assertRedirect('/blog/perlengkapan-event-pernikahan-checklist-tenda-peralatan');

        $this->get('/blog/berapa-biaya-sewa-tenda-pernikahan-ini-panduannya')
            ->assertMovedPermanently()
            ->assertRedirect('/blog/faktor-biaya-sewa-tenda-pernikahan');

        $this->get('/blog/ide-dekorasi-ulang-tahun-anak-yang-kreatif-dan-murah')
            ->assertMovedPermanently()
            ->assertRedirect('/blog/perlengkapan-event-ulang-tahun');
    }

    public function test_migration_rewrites_existing_seeded_content_and_hides_placeholder_social_proof(): void
    {
        DB::table('blog_posts')->insert([
            'title' => 'Berapa Biaya Sewa Tenda Pernikahan? Ini Panduannya!',
            'slug' => 'berapa-biaya-sewa-tenda-pernikahan-ini-panduannya',
            'content' => 'Harga sewa tenda Rp 1.500.000.',
            'author' => 'Budi Santoso',
            'published_at' => now(),
            'is_published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('clients')->insert([
            'name' => 'PT Maju Bersama',
            'is_active' => true,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('testimonials')->insert([
            'client_name' => 'Ibu Ratna',
            'company' => 'Pernikahan',
            'message' => 'Testimoni contoh.',
            'rating' => 5,
            'is_active' => true,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migration = require database_path('migrations/2026_11_02_000001_clean_unverified_seeded_seo_content.php');
        $migration->up();

        $post = BlogPost::query()->where('slug', 'faktor-biaya-sewa-tenda-pernikahan')->firstOrFail();
        $this->assertSame('', $post->author);
        $this->assertNull($post->published_at);
        $this->assertStringNotContainsString('Rp ', $post->content);
        $this->assertDatabaseHas('clients', ['name' => 'PT Maju Bersama', 'is_active' => false]);
        $this->assertDatabaseHas('testimonials', ['client_name' => 'Ibu Ratna', 'is_active' => false]);
    }

    public function test_contact_page_distinguishes_whatsapp_availability_from_office_hours(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Konsultasi &amp; pemesanan via WhatsApp 24 jam setiap hari', false)
            ->assertSee('Jam kantor');
    }
}
