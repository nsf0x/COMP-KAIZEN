<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PortfolioGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_can_have_gallery_images(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Portfolio Test',
            'slug' => 'portfolio-test',
            'type' => 'event',
            'description' => 'Test description',
            'is_featured' => true,
            'order' => 1,
        ]);

        PortfolioImage::create([
            'portfolio_id' => $portfolio->id,
            'image_path' => 'portfolio/test-1.jpg',
            'caption' => 'Preview',
            'order' => 1,
        ]);

        $this->assertCount(1, $portfolio->fresh()->images);
        $this->assertEquals('portfolio/test-1.jpg', $portfolio->fresh()->images->first()->image_path);
    }

    public function test_portfolio_table_has_no_category_column(): void
    {
        $this->assertFalse(Schema::hasColumn('portfolios', 'category_id'));
    }

    public function test_portfolio_supports_custom_types_and_displays_youtube_video(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Dokumentasi Acara',
            'slug' => 'dokumentasi-acara',
            'type' => 'Dokumentasi',
            'video_url' => 'https://youtu.be/abc123xyz_0',
        ]);

        $this->assertSame('Dokumentasi', $portfolio->type);
        $this->assertSame('https://www.youtube-nocookie.com/embed/abc123xyz_0', $portfolio->video_embed_url);

        $this->get(route('portfolio.show', $portfolio))
            ->assertOk()
            ->assertSee('https://www.youtube-nocookie.com/embed/abc123xyz_0')
            ->assertSee('Video Dokumentasi Acara');
    }

    public function test_portfolio_displays_direct_video_files(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Video Acara',
            'slug' => 'video-acara',
            'type' => 'Acara',
            'video_url' => 'https://cdn.example.com/event.mp4',
        ]);

        $this->get(route('portfolio.show', $portfolio))
            ->assertOk()
            ->assertSee('<video', false)
            ->assertSee('https://cdn.example.com/event.mp4');
    }

    public function test_portfolio_displays_uploaded_video_files(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Video Upload',
            'slug' => 'video-upload',
            'type' => 'Dokumentasi',
            'video_url' => 'https://youtu.be/abc123xyz_0',
            'video_path' => 'portfolio/videos/event.mp4',
        ]);

        $this->get(route('portfolio.show', $portfolio))
            ->assertOk()
            ->assertSee('<video', false)
            ->assertSee('storage/portfolio/videos/event.mp4')
            ->assertSee('https://www.youtube-nocookie.com/embed/abc123xyz_0');
    }

    public function test_portfolio_displays_multiple_gallery_images_as_a_slideshow(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Event dengan Slide',
            'slug' => 'event-dengan-slide',
            'type' => 'event',
        ]);

        $portfolio->images()->createMany([
            ['image_path' => 'portfolio/event-1.jpg', 'order' => 1],
            ['image_path' => 'portfolio/event-2.jpg', 'order' => 2],
        ]);

        $this->get(route('portfolio.show', $portfolio))
            ->assertOk()
            ->assertSee('data-bs-ride="false"', false)
            ->assertSee('data-bs-slide="next"', false)
            ->assertSee('data-bs-slide-to="1"', false)
            ->assertSee('storage/portfolio/event-1.jpg')
            ->assertSee('storage/portfolio/event-2.jpg');
    }

    public function test_related_portfolios_have_readable_dark_mode_styles(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Event Utama',
            'slug' => 'event-utama',
            'type' => 'event',
        ]);

        foreach (range(1, 3) as $index) {
            Portfolio::create([
                'title' => "Event Terkait {$index}",
                'slug' => "event-terkait-{$index}",
                'type' => 'event',
            ]);
        }

        $this->get(route('portfolio.show', $portfolio))
            ->assertOk()
            ->assertSee('Event Portofolio Lainnya')
            ->assertSee('portfolio-related-heading', false)
            ->assertSee('portfolio-related-title', false)
            ->assertSee('html.dark .portfolio-related-link', false)
            ->assertSee('color: #f1f2f7', false);
    }
}
