<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OptimizePublicImagesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_idempotent_webp_sidecars_without_replacing_uploaded_source_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/tenda-roder.jpg', $this->createPngImage());

        $this->artisan('kaizen:optimize-images')
            ->assertSuccessful()
            ->expectsOutput('Created 1 WebP image(s); original files were preserved.');

        Storage::disk('public')->assertExists('products/tenda-roder.jpg');
        Storage::disk('public')->assertExists('products/tenda-roder.webp');

        $this->artisan('kaizen:optimize-images')
            ->assertSuccessful()
            ->expectsOutput('Created 0 WebP image(s); original files were preserved.');
    }

    private function createPngImage(): string
    {
        $image = imagecreatetruecolor(2, 2);
        ob_start();
        imagepng($image);
        $contents = ob_get_clean();
        imagedestroy($image);

        return $contents;
    }
}
