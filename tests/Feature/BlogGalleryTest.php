<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\BlogPostImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_post_can_have_gallery_images(): void
    {
        $post = BlogPost::create([
            'title' => 'Blog Test',
            'slug' => 'blog-test',
            'content' => 'Isi artikel test',
            'author' => 'Admin',
            'published_at' => now(),
            'is_published' => true,
        ]);

        BlogPostImage::create([
            'blog_post_id' => $post->id,
            'image_path' => 'blog/test-1.jpg',
            'caption' => 'Preview',
            'order' => 1,
        ]);

        $this->assertCount(1, $post->fresh()->images);
        $this->assertEquals('blog/test-1.jpg', $post->fresh()->images->first()->image_path);
    }
}
