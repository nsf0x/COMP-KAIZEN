<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Product;
use Illuminate\Http\Response;

class StaticPageController extends Controller
{
    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function robots(): Response
    {
        $sitemapUrl = rtrim(config('app.url'), '/').route('sitemap', absolute: false);

        return response("User-agent: *\nDisallow:\nSitemap: {$sitemapUrl}\n")
            ->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $urls = collect([
            ['loc' => $baseUrl.'/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $baseUrl.'/produk', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $baseUrl.'/portofolio', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $baseUrl.'/blog', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $baseUrl.'/tentang-kami', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => $baseUrl.'/kontak', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => $baseUrl.'/kebijakan-privasi', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => $baseUrl.'/syarat-layanan', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ]);

        foreach (Product::where('status', true)->get() as $product) {
            if (filled($product->slug)) {
                $urls->push(['loc' => $baseUrl.'/produk/'.rawurlencode($product->slug), 'changefreq' => 'weekly', 'priority' => '0.7']);
            }
        }

        foreach (Portfolio::all() as $portfolio) {
            if (filled($portfolio->slug)) {
                $urls->push(['loc' => $baseUrl.'/portofolio/'.rawurlencode($portfolio->slug), 'changefreq' => 'monthly', 'priority' => '0.6']);
            }
        }

        foreach (BlogPost::published()->get() as $post) {
            if (filled($post->slug)) {
                $urls->push(['loc' => $baseUrl.'/blog/'.rawurlencode($post->slug), 'changefreq' => 'monthly', 'priority' => '0.6']);
            }
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }
}
