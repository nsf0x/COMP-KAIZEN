@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160))

@section('content')

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:var(--primary);">Produk</a></li>
            @if($product->category)
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="color:var(--primary);">
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
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                            <div style="font-size:8rem;">🎁</div>
                            @endif
                        </div>
                    </div>
                    @foreach($images as $img)
                    <div class="carousel-item" style="height:380px;">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $product->name }}"
                             style="width:100%;height:100%;object-fit:cover;">
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
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                <div style="font-size:8rem;">🎁</div>
                @endif
            </div>
            @endif
        </div>

        <!-- Details -->
        <div class="col-lg-6">
            @if($product->category)
            <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary);">{{ $product->category->name }}</span>
            @endif
            @if($product->is_featured)
            <span class="badge mb-2 ms-1" style="background:var(--primary);">⭐ Produk Unggulan</span>
            @endif

            <h1 class="fw-700 fs-2 mb-2">{{ $product->name }}</h1>

            <div class="fw-600 mb-4 text-muted">
                Informasi lebih lanjut via WhatsApp atau telepon
            </div>

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
                        <div class="small text-muted">Kualitas Terjamin</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.5rem;">💬</div>
                        <div class="small text-muted">Respon Cepat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->name }}" style="width:100%;height:100%;object-fit:cover;">
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
