<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
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

        $internalLinkMap = [
            'perlengkapan-event-pernikahan-checklist-tenda-peralatan' => [
                'categories' => ['tenda-flooring', 'furniture-seating', 'furniture', 'sound-system-lighting-pendingin'],
                'products' => ['sound-system', 'sound-system-event'],
            ],
            'faktor-biaya-sewa-tenda-pernikahan' => [
                'categories' => ['tenda-flooring', 'furniture-seating', 'furniture'],
                'products' => ['flooring', 'flooring-playwood-18mm'],
            ],
            'perlengkapan-event-ulang-tahun' => [
                'categories' => ['tenda-flooring', 'furniture-seating', 'furniture', 'sound-system-lighting-pendingin'],
                'products' => ['sound-system', 'sound-system-event'],
            ],
            'persiapan-display-visual-event' => [
                'categories' => ['led-screen-processor', 'smart-tv'],
                'products' => ['led-screen-p39-indoor', 'smart-tv-75-inch'],
            ],
            'perencanaan-kebutuhan-daya-event' => [
                'categories' => ['genset', 'genset-power'],
                'products' => ['genset-150-kva', 'genset-5000-watt'],
            ],
            'persiapan-panggung-backdrop-branding' => [
                'categories' => ['stage-backdrop', 'produksi-branding', 'booth-branding'],
                'products' => ['backdrop', 'produksi-gate-dan-branding'],
            ],
            'koordinasi-dan-fasilitas-event' => [
                'categories' => ['event-support', 'perangkat-komunikasi', 'toilet-portable'],
                'products' => ['handy-talkie-baofeng', 'toilet-portable-toilet-kering'],
            ],
        ];
        $internalLinkConfig = $internalLinkMap[$blogPost->slug] ?? [
            'categories' => [],
            'products' => [],
        ];
        $relatedCategories = Category::availableForCatalog()
            ->whereIn('slug', $internalLinkConfig['categories'])
            ->orderBy('order')
            ->take(3)
            ->get();
        $relatedProducts = Product::query()
            ->where('status', true)
            ->whereIn('slug', $internalLinkConfig['products'])
            ->orderBy('order')
            ->get()
            ->take(max(0, 3 - $relatedCategories->count()));

        return view('blog.show', compact('blogPost', 'related', 'relatedCategories', 'relatedProducts'));
    }
}
