<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Client;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('status', true)
            ->where('is_featured', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $categories = Category::query()
            ->availableForCatalog()
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $hasCatalogProductMatching = static function (array $terms) use ($categories): bool {
            return Product::query()
                ->where('status', true)
                ->whereIn('category_id', $categories->modelKeys())
                ->where(function ($query) use ($terms): void {
                    foreach ($terms as $term) {
                        $like = '%'.mb_strtolower($term).'%';
                        $query->orWhereRaw('LOWER(name) LIKE ?', [$like])
                            ->orWhereRaw('LOWER(description) LIKE ?', [$like]);
                    }
                })
                ->exists();
        };
        $homeRentalKeywords = [
            'rental_alat_event' => $categories->isNotEmpty(),
            'kursi_dan_meja' => $hasCatalogProductMatching(['kursi']) && $hasCatalogProductMatching(['meja']),
            'panggung' => $hasCatalogProductMatching(['panggung', 'stage', 'rigging']),
            'karpet' => $hasCatalogProductMatching(['karpet']),
        ];

        $portfolios = Portfolio::where('is_featured', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_published', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $clients = Client::where('is_published', true)
            ->orderBy('order')
            ->get();

        $latestPosts = BlogPost::published()
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'categories',
            'homeRentalKeywords',
            'portfolios',
            'testimonials',
            'clients',
            'latestPosts'
        ));
    }
}
