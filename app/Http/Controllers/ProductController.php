<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('category') && ! $request->filled('search')) {
            $category = $this->findAvailableCategory((string) $request->query('category'));

            if ($category) {
                $parameters = ['slug' => $category->slug];
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
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $products = $query->orderBy('order')->paginate(12)->withQueryString();

        $selectedCategory = $request->category;

        return view('products.index', compact('products', 'categories', 'selectedCategory', 'infoUmum'));
    }

    public function legacyCategory(string $legacySlug)
    {
        $category = $this->findAvailableCategory($legacySlug);

        if (! $category) {
            abort(404);
        }

        return redirect()->route('products.category', ['slug' => $category->slug], 301);
    }

    public function categoryBySeoSlug(string $slug)
    {
        $category = $this->findAvailableCategory($slug);

        if (! $category) {
            abort(404);
        }

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('status', true)
            ->with(['category', 'images'])
            ->orderBy('order')
            ->paginate(12);

        $categoryArticleMap = [
            'tenda-flooring' => [
                'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'faktor-biaya-sewa-tenda-pernikahan',
                'perlengkapan-event-ulang-tahun',
            ],
            'led-screen-processor' => ['persiapan-display-visual-event'],
            'smart-tv' => ['persiapan-display-visual-event'],
            'genset' => ['perencanaan-kebutuhan-daya-event'],
            'genset-power' => ['perencanaan-kebutuhan-daya-event'],
            'sound-system' => [
                'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'perlengkapan-event-ulang-tahun',
            ],
            'sound-system-lighting-pendingin' => [
                'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'perlengkapan-event-ulang-tahun',
            ],
            'lighting-stage' => [
                'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'persiapan-panggung-backdrop-branding',
            ],
            'stage-backdrop' => ['persiapan-panggung-backdrop-branding'],
            'partisi' => ['persiapan-panggung-backdrop-branding'],
            'booth-branding' => ['persiapan-panggung-backdrop-branding'],
            'produksi-branding' => ['persiapan-panggung-backdrop-branding'],
            'furniture' => [
                'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'perlengkapan-event-ulang-tahun',
            ],
            'furniture-seating' => [
                'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'perlengkapan-event-ulang-tahun',
            ],
            'event-support' => ['koordinasi-dan-fasilitas-event'],
            'toilet-portable' => ['koordinasi-dan-fasilitas-event'],
            'perangkat-komunikasi' => ['koordinasi-dan-fasilitas-event'],
        ];
        $relatedArticles = BlogPost::published()
            ->whereIn('slug', $categoryArticleMap[$category->slug] ?? [])
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->get();
        $categoryTopics = collect($this->categoryTopics($category->slug))
            ->map(function (array $topic) use ($category): array {
                $topic['products'] = Product::query()
                    ->where('category_id', $category->id)
                    ->where('status', true)
                    ->where(function ($query) use ($topic): void {
                        foreach ($topic['terms'] as $term) {
                            $query->orWhere('name', 'like', '%'.$term.'%');
                        }
                    })
                    ->orderBy('order')
                    ->get();

                return $topic;
            })
            ->values();

        return view('products.category', compact('category', 'products', 'relatedArticles', 'categoryTopics'));
    }

    private function findAvailableCategory(string $slug): ?Category
    {
        $canonicalSlug = [
            'tenda' => 'tenda-flooring',
            'flooring-karpet' => 'tenda-flooring',
            'led-screen' => 'led-screen-processor',
        ][$slug] ?? $slug;

        $category = Category::availableForCatalog()
            ->where('slug', $canonicalSlug)
            ->first();

        if (! $category && $canonicalSlug !== $slug) {
            $category = Category::availableForCatalog()->where('slug', $slug)->first();
        }

        return $category;
    }

    public function show(Product $product)
    {
        if (! $product->status) {
            abort(404);
        }

        $product->load(['category', 'images']);

        $related = Product::where('status', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('order')
            ->take(4)
            ->get();

        $variantSlugs = match ($product->slug) {
            'genset-berbagai-kapasitas' => [
                'genset-150-kva',
                'genset-100-kva',
                'genset-80-kva',
                'genset-60-kva',
                'genset-5000-watt',
            ],
            'smart-tv-berbagai-ukuran' => [
                'smart-tv-75-inch',
                'smart-tv-65-inch',
                'smart-tv-55-inch',
            ],
            'processor-novastar' => [
                'processor-novastar-vx16s',
                'processor-novastar-vx600',
                'processor-novastar-vx400',
            ],
            default => [],
        };
        $variants = Product::query()
            ->whereIn('slug', $variantSlugs)
            ->where('status', true)
            ->orderBy('order')
            ->get();
        return view('products.show', compact('product', 'related', 'variants'));
    }

    private function categoryTopics(string $slug): array
    {
        return match ($slug) {
            'tenda-flooring' => [
                [
                    'title' => 'Tenda untuk area acara',
                    'description' => 'Pilih jenis dan ukuran tenda berdasarkan area yang digunakan, lokasi, serta kebutuhan acara. Konfirmasikan akses pemasangan dengan pengelola venue.',
                    'question' => 'Informasi apa yang perlu disiapkan untuk menanyakan tenda?',
                    'answer' => 'Sampaikan tanggal acara, lokasi, ukuran area, dan jenis tenda yang dibutuhkan agar ketersediaan dapat diperiksa.',
                    'terms' => ['tenda'],
                ],
                [
                    'title' => 'Flooring dan karpet',
                    'description' => 'Pertimbangkan kondisi permukaan, area yang akan digunakan, dan alur tamu sebelum menentukan kebutuhan flooring atau karpet.',
                    'question' => 'Bagaimana menentukan kebutuhan flooring atau karpet?',
                    'answer' => 'Siapkan ukuran dan kondisi area serta informasi venue. Tim dapat membantu memeriksa pilihan yang tersedia untuk kebutuhan tersebut.',
                    'terms' => ['flooring', 'karpet'],
                ],
            ],
            'led-screen-processor' => [
                [
                    'title' => 'LED screen',
                    'description' => 'Katalog mencantumkan LED screen P2.6 dan P3.9 dengan pilihan indoor dan outdoor. Sampaikan kebutuhan lokasi acara agar jenis yang tersedia dapat dikonfirmasi.',
                    'question' => 'Informasi apa yang dibutuhkan untuk menanyakan LED screen?',
                    'answer' => 'Sampaikan tanggal acara, lokasi penggunaan indoor atau outdoor, ukuran tampilan yang dibutuhkan, dan kebutuhan teknis yang sudah diketahui.',
                    'terms' => ['led screen', 'led cube'],
                ],
                [
                    'title' => 'Video processor',
                    'description' => 'Katalog mencantumkan processor Novastar VX16S, VX600, dan VX400. Konfirmasikan kesesuaian model dan ketersediaannya untuk kebutuhan acara.',
                    'question' => 'Model processor Novastar apa yang tercantum di katalog?',
                    'answer' => 'Model yang tercantum adalah VX16S, VX600, dan VX400. Kesesuaian perangkat serta ketersediaannya perlu dikonfirmasi untuk kebutuhan acara.',
                    'terms' => ['processor'],
                ],
            ],
            default => [],
        };
    }
}
