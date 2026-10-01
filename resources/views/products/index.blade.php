@extends('layouts.app')

@section('title', 'Semua Produk')
@section('meta_description', 'Temukan berbagai perlengkapan pesta berkualitas untuk disewa. Dekorasi, sound system, tenda, kursi, dan masih banyak lagi.')

@section('content')

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item active">Produk</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4" style="border-radius:16px; position:sticky; top:80px;">
                <h6 class="fw-700 mb-3">Filter Produk</h6>
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Search -->
                    <div class="mb-4">
                        <label class="form-label small fw-600">Cari Produk</label>
                        <input type="text" name="search" class="form-control rounded-pill" placeholder="Nama produk..."
                               value="{{ request('search') }}">
                    </div>
                    <!-- Categories -->
                    <div class="mb-4">
                        <label class="form-label small fw-600">Kategori</label>
                        <div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="category" value="" id="cat-all"
                                       {{ !$selectedCategory ? 'checked' : '' }}>
                                <label class="form-check-label small" for="cat-all">Semua Kategori</label>
                            </div>
                            @foreach($categories as $cat)
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}"
                                       id="cat-{{ $cat->slug }}" {{ $selectedCategory === $cat->slug ? 'checked' : '' }}>
                                <label class="form-check-label small" for="cat-{{ $cat->slug }}">{{ $cat->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Terapkan Filter</button>
                    @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 rounded-pill mt-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if(!empty($infoUmum))
            <div class="card border-0 shadow-sm mb-4" style="border-radius:16px; background:linear-gradient(120deg, #f5f7ff 0%, #fff 100%);">
                <div class="card-body">
                    <div class="fw-700 mb-3" style="color:var(--primary);">Informasi Umum</div>
                    <ul class="mb-0 ps-3 text-muted small">
                        @foreach($infoUmum as $item)
                        <li class="mb-1">{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-700 mb-0">
                    @if($selectedCategory)
                        Kategori: {{ $categories->firstWhere('slug', $selectedCategory)?->name }}
                    @else
                        Semua Produk
                    @endif
                    <span class="text-muted fw-400 fs-6">({{ $products->total() }} produk)</span>
                </h5>
            </div>

            @if($products->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:4rem;">😅</div>
                <h5 class="mt-3">Produk tidak ditemukan</h5>
                <p class="text-muted">Coba ubah kata kunci atau filter kategori</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary-custom rounded-pill">Lihat Semua</a>
            </div>
            @else
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-sm-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden; transition: transform .2s, box-shadow .2s;"
                         onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 15px 40px rgba(233,30,140,0.15)'"
                         onmouseout="this.style.transform='';this.style.boxShadow=''">
                        <div style="height:200px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                            @if($product->thumbnail)
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                            <div style="font-size:5rem;">🎁</div>
                            @endif
                        </div>
                        @if($product->is_featured)
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge" style="background:var(--primary);">⭐ Unggulan</span>
                        </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary); width:fit-content;">
                                {{ $product->category->name ?? 'Umum' }}
                            </span>
                            <h6 class="fw-700 mb-1">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2 flex-grow-1">{{ Str::limit($product->description, 70) }}</p>
                            <div class="fw-600 mb-3 text-muted small">
                                Informasi lebih lanjut
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary rounded-pill flex-fill">Detail</a>
                                <a href="{{ $product->whatsapp_url }}" class="btn btn-sm btn-success rounded-pill flex-fill" target="_blank">
                                    <i class="bi bi-whatsapp me-1"></i>Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
