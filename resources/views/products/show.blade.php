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
    $saleSeoTitle = ($product->bisa_disewa ? 'Jual & Sewa ' : 'Jual ') . $product->name . ' Bekasi | Kaizen Kreasi Indonesia';
    $productSeoTitle = $product->bisa_dijual
        ? $saleSeoTitle
        : ($isVariantProduct ? $variantSeoTitle : $product->name . ' | ' . config('company.name'));
    $productSeoDescription = $product->bisa_dijual
        ? Str::limit(trim($product->meta_description ?: 'Jual ' . $product->name . ' di Bekasi. Kaizen Kreasi Indonesia melayani Jabodetabek; informasi harga melalui WhatsApp.'), 160)
        : ($isVariantProduct
            ? Str::limit($variantSeoTitle . '. ' . strip_tags($product->description ?: '') . ' Tanyakan ketersediaan sesuai tanggal dan lokasi acara.', 160)
            : Str::limit(trim($product->meta_description ?: strip_tags($product->description ?: ('Sewa ' . $product->name . ' dari ' . config('company.name') . ' di ' . config('company.city') . '.'))), 160));
@endphp

@section('title', $productSeoTitle)
@section('meta_title', $productSeoTitle)
@section('meta_description', $productSeoDescription)
@section('meta_image', $product->thumbnail ? $product->thumbnail_url : rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png')
@section('og_type', 'product')

@push('structured_data')
<script type="application/ld+json">
    {!! json_encode(array_filter([
        '@@context' => 'https://schema.org',
        '@type' => $product->bisa_dijual ? 'Product' : 'Service',
        'name' => $product->name,
        'description' => \App\Support\PublicText::normalize(trim($productSeoDescription)),
        'image' => $product->thumbnail ? $product->thumbnail_url : null,
        'category' => $product->category?->name,
        'serviceType' => $product->bisa_dijual ? null : $product->category?->name,
        'provider' => $product->bisa_dijual ? null : [
            '@type' => 'LocalBusiness',
            '@id' => rtrim(config('app.url'), '/') . '/#organization',
            'name' => config('company.name'),
        ],
        'areaServed' => $product->bisa_dijual ? null : 'Jabodetabek',
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
            $product->category?->is_active ? ['@type' => 'ListItem', 'position' => 3, 'name' => $product->category->display_name, 'item' => route('products.category', ['slug' => $product->category->slug], true)] : null,
            ['@type' => 'ListItem', 'position' => $product->category?->is_active ? 4 : 3, 'name' => $product->name, 'item' => rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/')],
        ])),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
@endpush

@push('styles')
<style>
    .product-detail-navigation {
        display:flex;
        flex-direction:column;
        align-items:flex-start;
        gap:8px;
        margin-bottom:24px;
    }
    .product-detail-navigation .breadcrumb { margin-bottom:0; }
    .product-detail-breadcrumb .breadcrumb-item a {
        color:#535b6b;
        text-decoration:none;
        transition:color .18s ease;
    }
    .product-detail-breadcrumb .breadcrumb-item a:hover { color:#087443; }
    .product-detail-breadcrumb .breadcrumb-item a:focus-visible,
    .product-back-link:focus-visible {
        outline:3px solid #ffbf47;
        outline-offset:2px;
        border-radius:4px;
    }
    .product-detail-breadcrumb .breadcrumb-item::before { color:#6b7280; }
    .product-detail-breadcrumb .breadcrumb-item.active { color:#535b6b; }
    .product-back-link {
        display:inline-flex;
        align-items:center;
        gap:8px;
        min-height:44px;
        padding:0 12px;
        border-radius:12px;
        color:#535b6b;
        font-size:14px;
        font-weight:600;
        line-height:1;
        text-decoration:none;
        white-space:nowrap;
        transition:color .18s ease, background-color .18s ease;
    }
    .product-back-link svg {
        flex:none;
        width:18px;
        height:18px;
        transition:transform .18s ease;
    }
    .product-back-link:hover { color:#087443; }
    .product-back-link:hover svg { transform:translateX(-3px); }
    html.dark .product-detail-breadcrumb .breadcrumb-item a,
    html.dark .product-detail-breadcrumb .breadcrumb-item.active { color:#c0c2cc; }
    html.dark .product-detail-breadcrumb .breadcrumb-item a:hover,
    html.dark .product-back-link:hover { color:#57d991; }
    html.dark .product-detail-breadcrumb .breadcrumb-item::before { color:#9a9daa; }
    html.dark .product-back-link { color:#c0c2cc; }
    @media (min-width:576px) {
        .product-detail-navigation {
            flex-direction:row;
            align-items:center;
            gap:12px;
        }
    }
    @media (prefers-reduced-motion:reduce) {
        .product-detail-breadcrumb .breadcrumb-item a,
        .product-back-link,
        .product-back-link svg { transition:none; }
    }
    .product-detail-copy {
        min-width: 0;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
</style>
@endpush

@section('content')

<div class="container py-5" data-product-name="{{ $product->name }}">
    <div class="product-detail-navigation">
        <a href="{{ $product->category?->is_active ? route('products.category', ['slug' => $product->category->slug]) : route('products.index') }}"
           class="product-back-link"
           aria-label="Kembali ke daftar produk"
           data-product-back-link>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M19 12H5"></path>
                <path d="m12 19-7-7 7-7"></path>
            </svg>
            <span class="d-sm-none">Kembali</span>
            <span class="d-none d-sm-inline">{{ $product->category?->is_active ? 'Kembali ke ' . $product->category->display_name : 'Kembali ke Produk' }}</span>
        </a>
        <nav aria-label="breadcrumb" class="product-detail-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                @if($product->category?->is_active)
                <li class="breadcrumb-item">
                    <a href="{{ route('products.category', ['slug' => $product->category->slug]) }}">
                        {{ $product->category->display_name }}
                    </a>
                </li>
                @endif
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

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
                            <img src="{{ asset('images/product-placeholder.svg') }}" alt="Placeholder foto produk: {{ $product->name }}"
                                 width="900" height="600" style="width:100%;height:100%;object-fit:cover;" />
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
                <img src="{{ asset('images/product-placeholder.svg') }}" alt="Placeholder foto produk: {{ $product->name }}"
                     width="900" height="600" style="width:100%;height:100%;object-fit:cover;" />
                @endif
            </div>
            @endif
        </div>

        <!-- Details -->
        <div class="col-lg-6 product-detail-copy">
            @if($product->category)
            <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary);">{{ $product->category->display_name }}</span>
            @endif
            @include('products.partials.availability-badge', ['product' => $product])
            @if($product->is_featured)
            <span class="badge mb-2 ms-1" style="background:var(--primary);">⭐ Produk Unggulan</span>
            @endif

            <h1 class="fw-700 fs-2 mb-2">{{ $isVariantProduct && !$product->bisa_dijual ? $variantSeoTitle : $product->name }}</h1>

            @if($product->bisa_dijual)
            <p class="fw-600 mb-4 text-muted">Harga hubungi kami via WhatsApp</p>
            @else
            <div class="fw-600 mb-4 text-muted">Informasi lebih lanjut via WhatsApp atau telepon</div>
            @endif

            @if($product->bisa_dijual && $product->bisa_disewa)
            <p class="small text-muted">Tersedia untuk dibeli maupun disewa</p>
            @endif

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
                @if($product->bisa_dijual)
                <table class="table table-sm table-bordered">
                    <tbody>
                        @foreach(preg_split('/\r\n|\r|\n/', $product->specification) as $specificationLine)
                        @if(trim($specificationLine) !== '')
                        <tr><td class="text-muted">{{ $specificationLine }}</td></tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-muted" style="white-space:pre-line;">{{ $product->specification }}</div>
                @endif
            </div>
            @endif

            <!-- CTA Buttons -->
            <div class="d-flex gap-3 flex-wrap align-items-center">
                @include('products.partials.whatsapp-actions', ['product' => $product])
                <a href="tel:{{ preg_replace('/\D+/', '', config('company.phone')) }}" class="btn btn-outline-secondary btn-lg rounded-pill">
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
                    <div class="product-card-media">
                        @if($item->thumbnail)
                        <x-optimized-image src="{{ $item->thumbnail_url }}" alt="Perlengkapan event: {{ $item->name }}"
                                           width="480" height="320" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                        <img src="{{ asset('images/product-placeholder.svg') }}" alt="Placeholder foto produk: {{ $item->name }}"
                             width="480" height="320" style="width:100%;height:100%;object-fit:cover;" />
                        @endif
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="product-card-title fw-600 small mb-1">{{ $item->name }}</h6>
                        @include('products.partials.availability-badge', ['product' => $item])
                        @include('products.partials.whatsapp-actions', ['product' => $item, 'compact' => true])
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    (() => {
        const backLink = document.querySelector('[data-product-back-link]');
        if (!backLink || !document.referrer || window.history.length < 2) return;

        const currentHost = window.location.hostname.replace(/^www\./i, '');
        const referrerHost = new URL(document.referrer).hostname.replace(/^www\./i, '');
        const isInternalReferrer = referrerHost === currentHost;

        if (isInternalReferrer) {
            backLink.addEventListener('click', (event) => {
                event.preventDefault();
                window.history.back();
            });
        }
    })();
</script>
@endpush
