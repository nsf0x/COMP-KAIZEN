<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class OptimizedImageComponentTest extends TestCase
{
    public function test_it_uses_the_original_image_when_a_webp_sidecar_is_missing(): void
    {
        $source = 'https://example.test/images/missing-sidecar-'.bin2hex(random_bytes(8)).'.png';

        $html = Blade::render(
            '<x-optimized-image src="'.$source.'" alt="Fallback image" width="10" height="10" />',
        );

        $this->assertStringContainsString('src="'.$source.'"', $html);
        $this->assertStringNotContainsString('<source', $html);
    }
}
