<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
