<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Product;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()->orderByRaw('COALESCE(published_at, created_at) DESC');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(9)->withQueryString();

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $blogPost)
    {
        if (!$blogPost->is_published) {
            abort(404);
        }

        $related = BlogPost::published()
            ->where('id', '!=', $blogPost->id)
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->take(3)
            ->get();

        $productTerms = match ($blogPost->slug) {
            'perlengkapan-event-pernikahan-checklist-tenda-peralatan' => ['Tenda', 'Kursi', 'Sound'],
            'faktor-biaya-sewa-tenda-pernikahan' => ['Tenda', 'Flooring', 'Genset'],
            'perlengkapan-event-ulang-tahun' => ['Tenda', 'Kursi', 'Sound'],
            default => [],
        };
        $relatedProducts = collect();
        if ($productTerms !== []) {
            $relatedProducts = Product::query()
                ->where('status', true)
                ->where(function ($query) use ($productTerms) {
                    foreach ($productTerms as $term) {
                        $query->orWhere('name', 'like', '%' . $term . '%');
                    }
                })
                ->orderBy('order')
                ->take(3)
                ->get();
        }

        return view('blog.show', compact('blogPost', 'related', 'relatedProducts'));
    }
}
