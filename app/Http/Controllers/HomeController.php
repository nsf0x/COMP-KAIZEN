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

        $portfolios = Portfolio::where('is_featured', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        $clients = Client::where('is_active', true)
            ->orderBy('order')
            ->get();

        $latestPosts = BlogPost::published()
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'categories',
            'portfolios',
            'testimonials',
            'clients',
            'latestPosts'
        ));
    }
}
