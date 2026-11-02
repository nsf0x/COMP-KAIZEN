<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('category') && !$request->filled('search')) {
            $category = Category::availableForCatalog()
                ->where('slug', $request->query('category'))
                ->first();

            if ($category) {
                $parameters = ['category' => $category->slug];
                if ($request->filled('page')) {
                    $parameters['page'] = $request->query('page');
                }

                return redirect()->route('products.category', $parameters, 301);
            }
        }

        $jsonPath = base_path('produk-kaizen-kreasi.json');
        $infoUmum = [];

        if (is_file($jsonPath)) {
            $decoded = json_decode((string) file_get_contents($jsonPath), true);
            $infoUmum = collect($decoded['info_umum'] ?? [])->filter(fn ($item) => is_string($item) && trim($item) !== '')->values()->all();
        }

        $categories = Category::query()
            ->availableForCatalog()
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $query = Product::where('status', true)
            ->with(['category', 'images']);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery
                    ->where('slug', $request->category)
                    ->where('is_active', true);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('order')->paginate(12)->withQueryString();

        $selectedCategory = $request->category;

        return view('products.index', compact('products', 'categories', 'selectedCategory', 'infoUmum'));
    }

    public function category(Category $category)
    {
        if (!$category->is_active || !$category->products()->where('status', true)->exists()) {
            abort(404);
        }

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('status', true)
            ->with(['category', 'images'])
            ->orderBy('order')
            ->paginate(12);

        return view('products.category', compact('category', 'products'));
    }

    public function show(Product $product)
    {
        if (!$product->status) {
            abort(404);
        }

        $product->load(['category', 'images']);

        $related = Product::where('status', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('order')
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
