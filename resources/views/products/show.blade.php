@extends('layouts.app')

@php
    $variantNames = [
        'genset-150-kva', 'genset-100-kva', 'genset-80-kva', 'genset-60-kva', 'genset-5000-watt',
        'smart-tv-75-inch', 'smart-tv-65-inch', 'smart-tv-55-inch',
        'processor-novastar-vx16s', 'processor-novastar-vx600', 'processor-novastar-vx400',
    ];
    $isVariantProduct = in_array($product->slug, $variantNames, true);
    $variantSeoTitle = 'Sewa ' . $product->name . ' Jabodetabek | Kaizen Kreasi';
    $variantHubSlug = match (true) {
        in_array($product->slug, ['genset-150-kva', 'genset-100-kva', 'genset-80-kva', 'genset-60-kva', 'genset-5000-watt'], true) => 'genset-berbagai-kapasitas',
        in_array($product->slug, ['smart-tv-75-inch', 'smart-tv-65-inch', 'smart-tv-55-inch'], true) => 'smart-tv-berbagai-ukuran',
        in_array($product->slug, ['processor-novastar-vx16s', 'processor-novastar-vx600', 'processor-novastar-vx400'], true) => 'processor-novastar',
        default => null,
    };
    $variantHub = $related->firstWhere('slug', $variantHubSlug);
    $productSeoDescription = $isVariantProduct
        ? Str::limit($variantSeoTitle . '. ' . strip_tags($product->description ?: '') . ' Tanyakan ketersediaan sesuai tanggal dan lokasi acara.', 160)
        : Str::limit(trim($product->meta_description ?: strip_tags($product->description ?: ('Sewa ' . $product->name . ' dari ' . config('company.name') . ' di ' . config('company.city') . '.'))), 160);
@endphp

@section('title', $isVariantProduct ? $variantSeoTitle : $product->name)
@section('meta_title', $isVariantProduct ? $variantSeoTitle : $product->name . ' | ' . config('company.name'))
@section('meta_description', $productSeoDescription)
@section('meta_image', $product->thumbnail ? $product->thumbnail_url : rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png')
@section('og_type', 'product')

@push('structured_data')
<script type="application/ld+json">
    {!! json_encode(array_filter([
        '@@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $product->name,
        'description' => trim($product->meta_description ?: strip_tags($product->description ?: ('Sewa ' . $product->name . ' dari ' . config('company.name') . ' di ' . config('company.city') . '.'))),
        'image' => $product->thumbnail ? $product->thumbnail_url : null,
        'serviceType' => $product->category?->name,
        'provider' => [
            '@type' => 'LocalBusiness',
            '@id' => rtrim(config('app.url'), '/') . '/#organization',
            'name' => config('company.name'),
        ],
        'areaServed' => 'Jabodetabek',
        'url' => rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/'),
    ], fn ($value) => $value !== null && $value !== ''), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
<script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_values(array_filter([
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => rtrim(config('app.url'), '/') . '/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Produk', 'item' => route('products.index', [], true)],
            $product->category?->is_active ? ['@type' => 'ListItem', 'position' => 3, 'name' => $product->category->name, 'item' => route('products.category', ['slug' => $product->category->slug], true)] : null,
            ['@type' => 'ListItem', 'position' => $product->category?->is_active ? 4 : 3, 'name' => $product->name, 'item' => rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/')],
        ])),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
@endpush

@push('styles')
<style>
    .product-detail-copy {
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
</style>
@endpush

@section('content')

<div class="container py-5" data-product-name="{{ $product->name }}">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:var(--primary);">Produk</a></li>
            @if($product->category?->is_active)
            <li class="breadcrumb-item">
                <a href="{{ route('products.category', ['slug' => $product->category->slug]) }}" style="color:var(--primary);">
                    {{ $product->category->name }}
                </a>
            </li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Images -->
        <div class="col-lg-6">
            @php $images = $product->images; @endphp
            @if($images->count() > 0)
            <div id="productCarousel" class="carousel slide rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="height:380px;">
                        <div style="width:100%;height:100%;background:#ffeef9;display:flex;align-items:center;justify-content:center;">
                            @if($product->thumbnail)
                            <x-optimized-image src="{{ $product->thumbnail_url }}" alt="Perlengkapan event: {{ $product->name }}"
                                               width="900" height="600" loading="eager" style="width:100%;height:100%;object-fit:cover;" />
                            @else
                            <div style="font-size:8rem;">🎁</div>
                            @endif
                        </div>
                    </div>
                    @foreach($images as $img)
                    <div class="carousel-item" style="height:380px;">
                        <x-optimized-image src="{{ asset('storage/' . $img->image_path) }}" alt="Foto produk {{ $product->name }} nomor {{ $loop->iteration }}"
                                           width="900" height="600" style="width:100%;height:100%;object-fit:cover;" />
                    </div>
                    @endforeach
                </div>
                @if($images->count() > 0)
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                @endif
            </div>
            @else
            <div class="rounded-4 overflow-hidden shadow-sm" style="height:380px; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                @if($product->thumbnail)
                <x-optimized-image src="{{ $product->thumbnail_url }}" alt="Perlengkapan event: {{ $product->name }}"
                                   width="900" height="600" loading="eager" style="width:100%;height:100%;object-fit:cover;" />
                @else
                <div style="font-size:8rem;">🎁</div>
                @endif
            </div>
            @endif
        </div>

        <!-- Details -->
        <div class="col-lg-6 product-detail-copy">
            @if($product->category)
            <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary);">{{ $product->category->name }}</span>
            @endif
            @if($product->is_featured)
            <span class="badge mb-2 ms-1" style="background:var(--primary);">⭐ Produk Unggulan</span>
            @endif

            <h1 class="fw-700 fs-2 mb-2">{{ $isVariantProduct ? $variantSeoTitle : $product->name }}</h1>

            <div class="fw-600 mb-4 text-muted">
                Informasi lebih lanjut via WhatsApp atau telepon
            </div>

            @if($variantHub)
            <p class="mt-4"><a href="{{ route('products.show', $variantHub) }}">Lihat semua pilihan {{ Str::before($product->name, ' ') }} di Jabodetabek</a></p>
            @endif

            @if($product->description)
            <div class="mb-4">
                <h6 class="fw-700">Deskripsi</h6>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
            @endif

            @if($product->specification)
            <div class="mb-4">
                <h6 class="fw-700">Spesifikasi</h6>
                <div class="text-muted" style="white-space:pre-line;">{{ $product->specification }}</div>
            </div>
            @endif

            <!-- CTA Buttons -->
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ $product->whatsapp_url }}" class="btn btn-success btn-lg rounded-pill px-5" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i>Pesan via WhatsApp
                </a>
                <a href="tel:{{ config('company.phone') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                    <i class="bi bi-telephone me-1"></i>Telepon
                </a>
            </div>

            <!-- Info -->
            <div class="mt-4 p-3 rounded-3" style="background:#fdf6fb;">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div style="font-size:1.5rem;">🚚</div>
                        <div class="small text-muted">Antar-Jemput</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.5rem;">✅</div>
                        <div class="small text-muted">Cek Ketersediaan</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.5rem;">💬</div>
                        <div class="small text-muted">WhatsApp 24 Jam</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($variants->isNotEmpty())
    <section class="mt-5" aria-labelledby="product-variants-heading">
        <h2 id="product-variants-heading" class="fw-700 mb-4">Pilih Varian</h2>
        <div class="row g-3">
            @foreach($variants as $variant)
            <div class="col-6 col-md-4">
                <a href="{{ route('products.show', $variant) }}" class="card border-0 shadow-sm h-100 text-decoration-none">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <span class="fw-600 text-dark">{{ $variant->name }}</span>
                        <span class="text-muted" aria-hidden="true">&rarr;</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Related Products -->
    @if($related->count())
    <div class="mt-5">
        <h4 class="fw-700 mb-4">Produk Terkait</h4>
        <div class="row g-4">
            @foreach($related as $item)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden;">
                    <div style="height:160px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                        @if($item->thumbnail)
                        <x-optimized-image src="{{ $item->thumbnail_url }}" alt="Perlengkapan event: {{ $item->name }}"
                                           width="480" height="320" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                        <div style="font-size:3rem;">🎁</div>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-600 small mb-1">{{ $item->name }}</h6>
                        <a href="{{ route('products.show', $item) }}" class="btn btn-sm btn-outline-secondary rounded-pill w-100">Lihat</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection
