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

    public function test_published_scope_includes_posts_without_published_at(): void
    {
        BlogPost::create([
            'title' => 'Draft-like Blog',
            'slug' => 'draft-like-blog',
            'content' => 'Isi artikel',
            'author' => 'Admin',
            'is_published' => true,
        ]);

        $this->assertCount(1, BlogPost::published()->get());
    }
}
