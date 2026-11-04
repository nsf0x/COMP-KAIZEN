@extends('layouts.app')

@section('title', 'Sewa ' . $category->display_name)
@section('meta_description', Str::limit('Jelajahi perlengkapan ' . $category->display_name . ' untuk kebutuhan event dari ' . config('company.name') . '. Melayani Jabodetabek dari Bekasi. Tanyakan ketersediaan dan penawaran via WhatsApp.', 160))

@php
    $topicFaqs = $categoryTopics->map(fn ($topic) => [
        '@type' => 'Question',
        'name' => $topic['question'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $topic['answer'],
        ],
    ])->all();
@endphp

@push('structured_data')
<script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => rtrim(config('app.url'), '/') . '/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Produk', 'item' => route('products.index', [], true)],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $category->display_name, 'item' => rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/')],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
<script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_merge(
            [
                [
                    '@type' => 'Question',
                    'name' => 'Produk apa saja yang tersedia dalam kategori ' . $category->display_name . '?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Produk yang tercantum di kategori ini adalah: ' . $products->getCollection()->pluck('name')->implode(', ') . '. Ketersediaan produk dikonfirmasi untuk tanggal dan lokasi acara.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Bagaimana cara meminta penawaran perlengkapan ' . $category->display_name . '?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Hubungi Kaizen Kreasi Indonesia melalui WhatsApp dan sampaikan tanggal acara, lokasi, serta perlengkapan yang dibutuhkan. Ketersediaan dan rincian penawaran dikonfirmasi berdasarkan kebutuhan acara.',
                    ],
                ],
            ],
            $topicFaqs,
        ),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:var(--primary);">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->display_name }}</li>
        </ol>
    </nav>

    <header class="mb-4">
        <h1 class="section-title mb-3">Perlengkapan {{ $category->display_name }} untuk Event</h1>
        <p class="text-muted">
            Jelajahi produk kategori {{ $category->display_name }} untuk kebutuhan acara. Hubungi {{ config('company.name') }}
            untuk menanyakan ketersediaan berdasarkan tanggal, lokasi, dan kebutuhan event.
        </p>
    </header>

    @if($categoryTopics->isNotEmpty())
    <section class="mb-5" aria-labelledby="category-subtopics-heading">
        <h2 id="category-subtopics-heading" class="h4 fw-700 mb-3">{{ $category->display_name }}: topik perlengkapan</h2>
        <div class="row g-4">
            @foreach($categoryTopics as $topic)
            <article class="col-md-6">
                <div class="h-100 p-4 rounded-4 shadow-sm" style="background:#fdf6fb;">
                    <h3 class="h5 fw-700">{{ $topic['title'] }}</h3>
                    <p class="text-muted">{{ $topic['description'] }}</p>
                    @if($topic['products']->isNotEmpty())
                    <ul class="mb-3">
                        @foreach($topic['products']->take(5) as $topicProduct)
                        <li><a href="{{ route('products.show', $topicProduct) }}">{{ $topicProduct->name }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                    <details>
                        <summary class="fw-600">{{ $topic['question'] }}</summary>
                        <p class="text-muted small mt-2 mb-0">{{ $topic['answer'] }}</p>
                    </details>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    @if($products->isEmpty())
    <div class="text-center py-5">
        <h2 class="h5">Produk kategori ini belum tersedia.</h2>
        <a href="{{ route('products.index') }}" class="btn btn-primary-custom rounded-pill mt-2">Lihat Semua Produk</a>
    </div>
    @else
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-sm-6 col-lg-4">
            <article class="card border-0 shadow-sm h-100" data-product-name="{{ $product->name }}" style="border-radius:16px; overflow:hidden;">
                <div style="height:220px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                    @if($product->thumbnail)
                    <x-optimized-image src="{{ $product->thumbnail_url }}" alt="Perlengkapan event: {{ $product->name }}"
                                       width="600" height="400" style="width:100%;height:100%;object-fit:cover;" />
                    @else
                    <div style="font-size:5rem;" aria-hidden="true">🎁</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h2 class="h6 fw-700">{{ $product->name }}</h2>
                    @if($product->description)
                    <p class="text-muted small flex-grow-1">{{ Str::limit($product->description, 110) }}</p>
                    @endif
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary rounded-pill mt-auto">Lihat Detail</a>
                </div>
            </article>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif

    <section class="mt-5" aria-labelledby="related-articles-heading">
        <h2 id="related-articles-heading" class="h4 fw-700 mb-3">Artikel terkait</h2>
        @if($relatedArticles->isNotEmpty())
        <ul class="list-unstyled">
            @foreach($relatedArticles as $article)
            <li class="mb-2"><a href="{{ route('blog.show', $article) }}">{{ $article->title }}</a></li>
            @endforeach
        </ul>
        @else
        <p class="text-muted">Belum ada artikel khusus untuk kategori ini. <a href="{{ route('blog.index') }}">Jelajahi panduan perlengkapan event</a>.</p>
        @endif
    </section>

    <div class="mt-5 p-4 rounded-4 text-center" style="background:#fdf6fb;">
        <h2 class="h5 fw-700">Butuh penawaran untuk event Anda?</h2>
        <p class="text-muted mb-3">Sampaikan tanggal, lokasi, dan perlengkapan yang dibutuhkan melalui WhatsApp.</p>
        <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-success rounded-pill" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-whatsapp me-2"></i>Tanyakan Ketersediaan
        </a>
    </div>
</div>
@endsection
