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
        $sitemapUrl = rtrim(config('app.url'), '/') . route('sitemap', absolute: false);

        return response("User-agent: *\nDisallow:\nSitemap: {$sitemapUrl}\n")
            ->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $absoluteUrl = fn (string $name, mixed $parameters = []) => rtrim(config('app.url'), '/') . route($name, $parameters, false);
        $urls = collect([
            ['loc' => $absoluteUrl('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $absoluteUrl('products.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $absoluteUrl('portfolio.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $absoluteUrl('blog.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $absoluteUrl('about'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => $absoluteUrl('contact'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => $absoluteUrl('privacy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => $absoluteUrl('terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ]);

        foreach (Product::where('status', true)->get() as $product) {
            $urls->push(['loc' => $absoluteUrl('products.show', $product), 'changefreq' => 'weekly', 'priority' => '0.7']);
        }

        foreach (Portfolio::all() as $portfolio) {
            $urls->push(['loc' => $absoluteUrl('portfolio.show', $portfolio), 'changefreq' => 'monthly', 'priority' => '0.6']);
        }

        foreach (BlogPost::published()->get() as $post) {
            $urls->push(['loc' => $absoluteUrl('blog.show', $post), 'changefreq' => 'monthly', 'priority' => '0.6']);
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }
}
