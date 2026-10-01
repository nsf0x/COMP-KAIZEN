@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Hero Section --}}
<section class="home-hero">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge rounded-pill px-3 py-2 mb-3 brand-badge" style="font-size:.85rem;">
                    EVENT EQUIPMENT RENTAL
                </span>
                <h1 class="display-4 fw-700 lh-sm mb-3" style="color:#1a1a2e;">
                    Bangun Event<br><span style="color:var(--primary);">Tanpa Batas</span><br>Bersama Kaizen
                </h1>
                <p class="lead text-muted mb-4">
                    {{ config('company.tagline') }}. Kami menyediakan equipment, tim profesional, dan solusi produksi untuk corporate event, pameran, wedding, konser, tournament, hingga festival skala besar.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-grid me-2"></i>Lihat Produk
                    </a>
                    <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-outline-success btn-lg rounded-pill px-4" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>Chat Sekarang
                    </a>
                </div>

                <!-- Stats -->
                <div class="row g-3 mt-4">
                    <div class="col-4 text-center">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ config('company.stats.events') }}</div>
                        <div class="text-muted small">Event Sukses</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ config('company.stats.clients') }}</div>
                        <div class="text-muted small">Klien Puas</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ config('company.stats.years') }}</div>
                        <div class="text-muted small">Tahun Pengalaman</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <img src="{{ asset('images/kaizen-logo-color.png') }}" alt="Logo Kaizen" class="hero-logo">
            </div>
        </div>
    </div>
</section>

{{-- Categories Section --}}
@if($categories->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Kategori Produk</h2>
            <div class="divider-pink mx-auto my-3"></div>
            <p class="section-subtitle">Temukan perlengkapan pesta sesuai kebutuhan Anda</p>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($categories as $category)
            @php
                $categoryIcons = [
                    'tent' => 'bi-house-door-fill',
                    'display' => 'bi-display',
                    'bolt' => 'bi-lightning-charge-fill',
                    'speaker' => 'bi-speaker-fill',
                    'lightbulb' => 'bi-lightbulb-fill',
                    'chair' => 'bi-grid-1x2-fill',
                    'booth' => 'bi-shop-window',
                    'support' => 'bi-headset',
                ];
            @endphp
            <div class="col-6 col-md-3 col-lg-2" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                   class="card category-card border-0 h-100 text-decoration-none shadow-sm">
                    <div class="category-card-icon" aria-hidden="true">
                        <i class="bi {{ $categoryIcons[$category->icon] ?? 'bi-box-seam' }}"></i>
                    </div>
                    <div class="px-2 py-3 text-center fw-600 small" style="color:#25283a;">{{ $category->name }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Featured Products --}}
@if($featuredProducts->count())
<section class="py-5" style="background:var(--light-bg);">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Produk Unggulan</h2>
            <div class="divider-pink mx-auto my-3"></div>
            <p class="section-subtitle">Produk terpopuler pilihan pelanggan kami</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden;">
                    <div style="height:200px; overflow:hidden; background:#ffeef9; display:flex; align-items:center; justify-content:center;">
                        @if($product->thumbnail)
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                             style="width:100%; height:100%; object-fit:cover;">
                        @else
                        <div style="font-size:5rem;">🎁</div>
                        @endif
                    </div>
                    <div class="card-body">
                        <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary);">{{ $product->category->name ?? '' }}</span>
                        <h5 class="fw-600 mb-1">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2">{{ Str::limit($product->description, 80) }}</p>
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
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary-custom">
                Lihat Semua Produk <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Why Us Section --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Mengapa Pilih Kami?</h2>
            <div class="divider-pink mx-auto my-3"></div>
        </div>
        <div class="row g-4">
            @php
            $reasons = [
                ['icon' => '⭐', 'title' => 'Kualitas Terjamin', 'desc' => 'Semua produk kami dirawat dan dijaga kualitasnya agar acara Anda berjalan sempurna.'],
                ['icon' => '💰', 'title' => 'Harga Terjangkau', 'desc' => 'Paket sewa fleksibel dan harga kompetitif, sesuai budget tanpa mengorbankan kualitas.'],
                ['icon' => '🚚', 'title' => 'Antar & Jemput', 'desc' => 'Layanan pengiriman dan pengambilan langsung ke lokasi acara Anda.'],
                ['icon' => '📞', 'title' => 'Respon Cepat', 'desc' => 'Tim kami siap melayani 24 jam melalui WhatsApp untuk konsultasi dan pemesanan.'],
                ['icon' => '✅', 'title' => 'Berpengalaman', 'desc' => 'Lebih dari ' . config('company.stats.years') . ' tahun melayani berbagai event dari skala kecil hingga besar.'],
                ['icon' => '🎯', 'title' => 'One-Stop Solution', 'desc' => 'Dari dekorasi hingga sound system, semua tersedia di satu tempat.'],
            ]
            @endphp
            @foreach($reasons as $r)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="d-flex align-items-start gap-3 p-4 rounded-4 h-100" style="background:#fdf6fb;">
                    <div style="font-size:2rem; flex-shrink:0;">{{ $r['icon'] }}</div>
                    <div>
                        <h6 class="fw-700 mb-1">{{ $r['title'] }}</h6>
                        <p class="text-muted small mb-0">{{ $r['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Portfolio --}}
@if($portfolios->count())
<section class="py-5" style="background:var(--light-bg);">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Portofolio</h2>
            <div class="divider-pink mx-auto my-3"></div>
            <p class="section-subtitle">Galeri momen spesial yang telah kami wujudkan</p>
        </div>
        <div class="row g-3">
            @foreach($portfolios as $portfolio)
            <div class="col-6 col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 60 }}">
                <a href="{{ route('portfolio.show', $portfolio) }}" class="d-block rounded-4 overflow-hidden position-relative" style="height:220px;">
                    @if($portfolio->thumbnail)
                    <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}"
                         style="width:100%; height:100%; object-fit:cover; transition: transform .3s;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform=''">
                    @else
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#ffe4f5,#ffd4ee);display:flex;align-items:center;justify-content:center;font-size:4rem;">🎉</div>
                    @endif
                    <div class="position-absolute bottom-0 start-0 end-0 p-2" style="background:linear-gradient(transparent,rgba(0,0,0,0.6));">
                        <div class="text-white small fw-600">{{ $portfolio->title }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('portfolio.index') }}" class="btn btn-primary-custom">
                Lihat Semua Portofolio <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($testimonials->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Apa Kata Mereka?</h2>
            <div class="divider-pink mx-auto my-3"></div>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius:16px;">
                    <div class="mb-2" style="color:#ffd700; font-size:1.1rem;">
                        @for($i = 0; $i < ($testimonial->rating ?? 5); $i++) ⭐ @endfor
                    </div>
                    <p class="text-muted small mb-3">"{{ $testimonial->message }}"</p>
                    <div class="d-flex align-items-center gap-2 mt-auto">
                        <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">
                            {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-600 small">{{ $testimonial->client_name }}</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $testimonial->company ?? 'Pelanggan' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Clients --}}
@if($clients->count())
<section class="py-4" style="background:#f8f8f8;">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <p class="text-muted small fw-600 text-uppercase letter-spacing-1">Dipercaya oleh</p>
        </div>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4">
            @foreach($clients as $client)
            <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 40 }}" style="opacity:.7; transition: opacity .2s;"
                 onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.7'">
                @if($client->logo)
                <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" style="height:48px; object-fit:contain; filter:grayscale(50%);">
                @else
                <span class="fw-600 text-muted">{{ $client->name }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Latest Blog --}}
@if($latestPosts->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Artikel Terbaru</h2>
            <div class="divider-pink mx-auto my-3"></div>
        </div>
        <div class="row g-4">
            @foreach($latestPosts as $post)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden;">
                    <div style="height:180px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                        @if($post->thumbnail)
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <div style="font-size:4rem;">📝</div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="text-muted small mb-2">{{ $post->published_at->format('d M Y') }}</div>
                        <h6 class="fw-700 mb-2">{{ $post->title }}</h6>
                        <p class="text-muted small mb-3">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('blog.index') }}" class="btn btn-primary-custom">
                Lihat Semua Artikel <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="text-white fw-700 mb-3">Siap Membuat Acara Impian Anda?</h2>
        <p class="text-white opacity-75 mb-4">Hubungi kami sekarang dan dapatkan penawaran terbaik!</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-light btn-lg rounded-pill px-5" target="_blank">
                <i class="bi bi-whatsapp me-2 text-success"></i>Chat WhatsApp
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg rounded-pill px-5">
                <i class="bi bi-envelope me-2"></i>Kirim Pesan
            </a>
        </div>
    </div>
</section>

@endsection
