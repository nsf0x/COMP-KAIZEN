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

    public function test_public_blog_listing_renders_published_posts_without_published_at(): void
    {
        BlogPost::create([
            'title' => 'Artikel Tanpa Tanggal',
            'slug' => 'artikel-tanpa-tanggal',
            'content' => 'Isi artikel',
            'is_published' => true,
        ]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('Artikel Tanpa Tanggal');

        $this->get(route('blog.show', 'artikel-tanpa-tanggal'))
            ->assertOk()
            ->assertSee('Artikel Tanpa Tanggal');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Artikel Tanpa Tanggal');
    }

    public function test_published_posts_with_future_publish_time_are_visible(): void
    {
        BlogPost::create([
            'title' => 'Artikel Publish WIB',
            'slug' => 'artikel-publish-wib',
            'content' => 'Isi artikel',
            'published_at' => now()->addHours(7),
            'is_published' => true,
        ]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('Artikel Publish WIB');

        $this->get(route('blog.show', 'artikel-publish-wib'))
            ->assertOk()
            ->assertSee('Artikel Publish WIB');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Artikel Publish WIB');
    }
}
