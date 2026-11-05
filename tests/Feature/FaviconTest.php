<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layout_renders_absolute_favicon_links_in_the_server_html_head(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();
        $head = substr($html, 0, (int) strpos($html, '</head>') + strlen('</head>'));

        $this->assertSame(2, substr_count($head, 'rel="icon"'));
        $this->assertSame(2, substr_count($head, 'rel="icon" type='));
        $this->assertSame(1, substr_count($head, 'rel="apple-touch-icon"'));
        $this->assertStringContainsString('<link rel="icon" type="image/x-icon" href="/favicon.ico">', $head);
        $this->assertStringContainsString('<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">', $head);
        $this->assertStringContainsString('<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">', $head);
        $this->assertStringNotContainsString('?v=', $head);
    }

    public function test_public_favicon_files_are_valid_square_images_at_the_declared_sizes(): void
    {
        $pngFiles = [
            'favicon-96x96.png' => 96,
            'apple-touch-icon.png' => 180,
        ];

        foreach ($pngFiles as $filename => $expectedSize) {
            $path = public_path($filename);
            $this->assertFileExists($path);
            $image = getimagesize($path);

            $this->assertNotFalse($image, $filename);
            $this->assertSame([$expectedSize, $expectedSize], [$image[0], $image[1]], $filename);
            $this->assertSame(IMAGETYPE_PNG, $image[2], $filename);
        }

        $icoPath = public_path('favicon.ico');
        $this->assertFileExists($icoPath);
        $ico = file_get_contents($icoPath);
        $this->assertNotFalse($ico);
        $header = unpack('vreserved/vtype/vcount', substr($ico, 0, 6));
        $this->assertSame(['reserved' => 0, 'type' => 1, 'count' => 2], $header);

        $sizes = [];
        for ($index = 0; $index < $header['count']; $index++) {
            $entry = unpack('Cwidth/Cheight', substr($ico, 6 + ($index * 16), 2));
            $sizes[] = [$entry['width'] ?: 256, $entry['height'] ?: 256];
        }

        $this->assertEqualsCanonicalizing([[32, 32], [48, 48]], $sizes);
    }
}
