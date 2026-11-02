@extends('layouts.app')

@section('title', 'Sewa ' . $category->name)
@section('meta_description', Str::limit('Jelajahi perlengkapan ' . $category->name . ' untuk kebutuhan event dari ' . config('company.name') . '. Melayani Jabodetabek dari Bekasi. Tanyakan ketersediaan dan penawaran via WhatsApp.', 160))

@push('structured_data')
<script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => rtrim(config('app.url'), '/') . '/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Produk', 'item' => route('products.index', [], true)],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $category->name, 'item' => rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/')],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
<script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'Produk apa saja yang tersedia dalam kategori ' . $category->name . '?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Produk yang tercantum di kategori ini adalah: ' . $products->getCollection()->pluck('name')->implode(', ') . '. Ketersediaan produk dikonfirmasi untuk tanggal dan lokasi acara.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Bagaimana cara meminta penawaran perlengkapan ' . $category->name . '?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Hubungi Kaizen Kreasi Indonesia melalui WhatsApp dan sampaikan tanggal acara, lokasi, serta perlengkapan yang dibutuhkan. Ketersediaan dan rincian penawaran dikonfirmasi berdasarkan kebutuhan acara.',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:var(--primary);">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <header class="mb-4">
        <h1 class="section-title mb-3">Perlengkapan {{ $category->name }} untuk Event</h1>
        <p class="text-muted">
            Jelajahi produk kategori {{ $category->name }} untuk kebutuhan acara. Hubungi {{ config('company.name') }}
            untuk menanyakan ketersediaan berdasarkan tanggal, lokasi, dan kebutuhan event.
        </p>
    </header>

    @if($products->isEmpty())
    <div class="text-center py-5">
        <h2 class="h5">Produk kategori ini belum tersedia.</h2>
        <a href="{{ route('products.index') }}" class="btn btn-primary-custom rounded-pill mt-2">Lihat Semua Produk</a>
    </div>
    @else
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-sm-6 col-lg-4">
            <article class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden;">
                <div style="height:220px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                    @if($product->thumbnail)
                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
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

    <div class="mt-5 p-4 rounded-4 text-center" style="background:#fdf6fb;">
        <h2 class="h5 fw-700">Butuh penawaran untuk event Anda?</h2>
        <p class="text-muted mb-3">Sampaikan tanggal, lokasi, dan perlengkapan yang dibutuhkan melalui WhatsApp.</p>
        <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-success rounded-pill" target="_blank" rel="noopener noreferrer">
            <i class="bi bi-whatsapp me-2"></i>Tanyakan Ketersediaan
        </a>
    </div>
</div>
@endsection
