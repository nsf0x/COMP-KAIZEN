<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioCatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_all_source_events_and_the_available_local_photos(): void
    {
        $this->seed(PortfolioSeeder::class);

        $this->assertSame(21, Portfolio::query()->count());
        $this->assertSame(13, PortfolioImage::query()->count());
        $this->assertDatabaseHas('portfolios', [
            'title' => 'JAKARTA INTERNASIONAL CHAMPIONSHIP',
            'slug' => 'jakarta-internasional-championship',
            'description' => "International Gol Tournament\nVenue : Damai Indah Golf PIK Course",
            'thumbnail' => null,
        ]);
        $this->assertDatabaseHas('portfolios', [
            'title' => 'INFACT EXPO (HUT KOTA DEPOK)',
            'thumbnail' => 'portfolio/events/infact-expo-hut-kota-depok/01.png',
        ]);
        $this->assertDatabaseHas('portfolio_images', [
            'image_path' => 'portfolio/events/infact-expo-hut-kota-depok/01.png',
            'order' => 1,
        ]);
        foreach (PortfolioImage::query()->get() as $image) {
            $this->assertFileExists(storage_path('app/public/'.$image->image_path));
        }
        $this->assertDatabaseMissing('portfolios', [
            'title' => 'Pernikahan Mewah di Ballroom Grand Hyatt',
        ]);
    }

    public function test_it_is_idempotent_when_seeded_more_than_once(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->seed(PortfolioSeeder::class);

        $this->assertSame(21, Portfolio::query()->count());
        $this->assertSame(13, PortfolioImage::query()->count());
    }
}
