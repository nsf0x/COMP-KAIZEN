@extends('layouts.app')

@section('title', 'Sewa Perlengkapan Event Jabodetabek | Kaizen Kreasi Indonesia')
@section('meta_description', 'Sewa tenda, LED screen, genset, sound system dan perlengkapan event Jabodetabek. Antar-jemput tersedia, pengiriman luar kota. Minta penawaran via WhatsApp.')

@push('styles')
<style>
    .order-step-card {
        background: #fff;
        color: #30323d;
    }

    html.dark .order-step-card {
        border: 1px solid #343642;
        background: #202230;
        color: #e5e6ec;
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="home-hero">
    <div class="container py-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge rounded-pill px-3 py-2 mb-3 brand-badge" style="font-size:.85rem;">
                    EVENT EQUIPMENT RENTAL
                </span>
                <h1 class="display-4 fw-700 lh-sm mb-3" style="color:#1a1a2e;">
                    Sewa Perlengkapan Event di Jabodetabek
                </h1>
                <h2 class="h4 fw-600 mb-3" style="color:var(--primary);">Bangun Event Tanpa Batas Bersama Kaizen</h2>
                <p class="lead text-muted mb-4">
                    Kami menyediakan perlengkapan event untuk berbagai acara. Sampaikan jenis acara serta kebutuhan Anda untuk memeriksa ketersediaan dan meminta penawaran.
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
                @if(config('company.stats.events') || config('company.stats.clients') || config('company.stats.years'))
                <div class="row g-3 mt-4">
                    @if(config('company.stats.events'))
                    <div class="col-4 text-center">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ config('company.stats.events') }}</div>
                        <div class="text-muted small">Event Sukses</div>
                    </div>
                    @endif
                    @if(config('company.stats.clients'))
                    <div class="col-4 text-center">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ config('company.stats.clients') }}</div>
                        <div class="text-muted small">Klien Puas</div>
                    </div>
                    @endif
                    @if(config('company.stats.years'))
                    <div class="col-4 text-center">
                        <div class="fw-700 fs-3" style="color:var(--primary);">{{ config('company.stats.years') }}</div>
                        <div class="text-muted small">Tahun Pengalaman</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <x-optimized-image src="{{ asset('images/kaizen-logo-color.png') }}" alt="Logo Kaizen Kreasi Indonesia"
                                   width="512" height="512" loading="eager" fetchpriority="high" class="hero-logo" />
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <h2 class="section-title">Mitra Sewa Perlengkapan Event Jabodetabek untuk Berbagai Acara</h2>
                <p class="text-muted lh-lg">Kaizen Kreasi Indonesia adalah penyedia sewa perlengkapan event Jabodetabek yang berkantor di Bekasi. Kami membantu penyelenggara pameran, acara perusahaan, pernikahan, konser, turnamen, dan festival menyiapkan kebutuhan lapangan dengan lebih mudah. Sebagai rental alat event Jakarta dan sekitarnya, kami menyediakan satu katalog yang mencakup tenda, flooring dan karpet, LED screen, genset, sound system, lighting, panggung, furniture, hingga toilet portable. Anda tidak perlu mencari banyak vendor terpisah, cukup sampaikan kebutuhan acara melalui WhatsApp dan tim kami akan membantu menyiapkan penawaran.</p>
                <p class="text-muted lh-lg">Untuk kebutuhan ruang, sewa tenda event Jabodetabek tersedia dalam berbagai jenis, mulai dari tenda konvensional, sarnafil, hingga roder. Bila acara Anda berupa expo atau bazar, sewa tenda pameran Jakarta dapat dipadukan dengan sewa karpet event Jabodetabek serta flooring agar area tampil rapi dan nyaman bagi pengunjung. Tamu pun lebih nyaman dengan sewa kursi meja event Jabodetabek, mulai dari kursi futura, kursi barstool, sofa, hingga set meja kantin dan dining.</p>
                <p class="text-muted lh-lg">Dari sisi teknis, kebutuhan listrik sering menentukan kelancaran acara. Layanan sewa genset Jakarta kami mencakup kapasitas dari 5000 watt hingga 150 KVA, dan sewa genset Jabodetabek dapat disesuaikan dengan total daya peralatan di lokasi. Untuk tampilan visual, sewa LED screen Jakarta tersedia dalam berbagai tipe indoor maupun outdoor, lengkap dengan processor dan smart TV. Suasana acara didukung oleh sewa sound system event Jabodetabek, sewa lighting event Jakarta, serta <a href="{{ route('products.category', ['slug' => 'stage-backdrop']) }}">sewa panggung event Jakarta</a> beserta backdrop. Fasilitas pendukung seperti sewa toilet portable Jabodetabek juga siap dikirim ke lokasi.</p>
                <p class="text-muted lh-lg">Karena berpusat di Bekasi, sewa perlengkapan event Bekasi dapat kami layani langsung, begitu pula untuk Depok, Bogor, Tangerang, dan Jakarta dengan layanan antar-jemput ke lokasi acara. Untuk event skala besar di luar Jabodetabek, kami juga mengatur pengiriman sebagai bagian dari layanan event equipment rental Indonesia. Sampaikan jenis acara, tanggal, lokasi, dan jumlah kebutuhan Anda melalui WhatsApp, lalu dapatkan penawaran sesuai detail acara dari Kaizen Kreasi Indonesia.</p>
            </div>
            <div class="col-lg-4">
                <h2 class="h4 fw-700 mb-3">Area Layanan</h2>
                <p class="text-muted">Jakarta, Bekasi, Depok, Bogor, dan Tangerang. Pengiriman ke seluruh Indonesia dapat dikonsultasikan untuk event skala besar.</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $category)
                    <a class="btn btn-sm btn-outline-secondary rounded-pill"
                       href="{{ route('products.category', ['slug' => $category->slug]) }}">{{ $category->display_name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background:var(--light-bg);">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Cara Memesan Perlengkapan Event</h2>
            <p class="section-subtitle">Minta penawaran sesuai kebutuhan acara Anda melalui WhatsApp.</p>
        </div>
        <ol class="row g-3 list-unstyled mb-0">
            @foreach([
                'Chat tim Kaizen melalui WhatsApp.',
                'Sampaikan jenis perlengkapan, tanggal, lokasi, dan kebutuhan event.',
                'Tim kami meninjau kebutuhan dan mengirimkan penawaran.',
                'Konfirmasi pesanan dan koordinasikan pengiriman untuk acara Anda.',
            ] as $step)
            <li class="col-md-6 col-lg-3">
                <div class="order-step-card h-100 p-4 rounded-4 shadow-sm">
                    <span class="badge rounded-pill mb-3" style="background:var(--primary);">{{ $loop->iteration }}</span>
                    <p class="mb-0">{{ $step }}</p>
                </div>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Categories Section --}}
@if($categories->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Kategori Produk</h2>
            <div class="divider-pink mx-auto my-3"></div>
            <p class="section-subtitle">Temukan perlengkapan acara sesuai kebutuhan Anda</p>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($categories as $category)
            <div class="col-6 col-md-3 col-lg-2" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('products.category', ['slug' => $category->slug]) }}"
                   class="card category-card border-0 h-100 text-decoration-none shadow-sm">
                    <div class="category-card-icon" aria-hidden="true">
                        <i class="{{ \App\Support\CategoryIcon::classFor($category) }}"></i>
                    </div>
                    <div class="px-2 py-3 text-center fw-600 small" style="color:#25283a;">{{ $category->display_name }}</div>
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
                        <x-optimized-image src="{{ $product->thumbnail_url }}" alt="Perlengkapan event: {{ $product->name }}"
                                           width="600" height="400" style="width:100%; height:100%; object-fit:cover;" />
                        @else
                        <div style="font-size:5rem;">🎁</div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary);">{{ $product->category->display_name ?? '' }}</span>
                        <h5 class="fw-600 mb-1">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2">{{ Str::limit($product->description, 80) }}</p>
                        <div class="fw-600 mb-3 text-muted small">
                            Informasi lebih lanjut
                        </div>
                        <div class="d-flex gap-2 mt-auto">
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
                ['icon' => '⭐', 'title' => 'Katalog Perlengkapan', 'desc' => 'Lihat kategori dan detail perlengkapan yang tersedia di katalog.'],
                ['icon' => '💬', 'title' => 'Penawaran Transparan', 'desc' => 'Dapatkan penawaran sesuai kebutuhan acara Anda langsung melalui WhatsApp.'],
                ['icon' => '🚚', 'title' => 'Antar & Jemput', 'desc' => 'Layanan pengiriman dan pengambilan langsung ke lokasi acara Anda.'],
                ['icon' => '📞', 'title' => 'WhatsApp 24 Jam', 'desc' => 'Konsultasi dan pemesanan dapat disampaikan melalui WhatsApp setiap hari.'],
                ['icon' => '✅', 'title' => 'Dukungan Kebutuhan Event', 'desc' => 'Konsultasikan perlengkapan dan kebutuhan produksi sesuai jenis acara Anda.'],
                ['icon' => '🎯', 'title' => 'Pilihan Perlengkapan', 'desc' => 'Jelajahi berbagai kategori perlengkapan event dalam satu katalog.'],
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
            <h2 class="section-title">Event Portofolio</h2>
            <div class="divider-pink mx-auto my-3"></div>
            <p class="section-subtitle">Galeri momen spesial yang telah kami wujudkan</p>
        </div>
        <div class="row g-3">
            @foreach($portfolios as $portfolio)
            <div class="col-6 col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 60 }}">
                <a href="{{ route('portfolio.show', $portfolio) }}" class="d-block rounded-4 overflow-hidden position-relative" style="height:220px;">
                    @if($portfolio->thumbnail)
                    <x-optimized-image src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="Dokumentasi event: {{ $portfolio->title }}"
                                       width="600" height="340" style="width:100%; height:100%; object-fit:cover; transition: transform .3s;"
                                       onmouseover="this.style.transform='scale(1.05)'"
                                       onmouseout="this.style.transform=''" />
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
                Lihat Semua Event Portofolio <i class="bi bi-arrow-right ms-1"></i>
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
                            @if($testimonial->event_type || $testimonial->rented_product || $testimonial->location || $testimonial->event_date)
                            <div class="text-muted" style="font-size:.75rem;">
                                @if($testimonial->event_type){{ $testimonial->event_type }}@endif
                                @if($testimonial->rented_product){{ $testimonial->event_type ? ' · ' : '' }}{{ $testimonial->rented_product }}@endif
                                @if($testimonial->location){{ ' · ' . $testimonial->location }}@endif
                                @if($testimonial->event_date){{ ' · ' . $testimonial->event_date->format('d M Y') }}@endif
                            </div>
                            @endif
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
                @if($client->logo_path)
                <x-optimized-image src="{{ $client->logo_url }}" alt="Logo {{ $client->name }}" width="180" height="48"
                                   style="height:48px; object-fit:contain; filter:grayscale(50%);" />
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
                        <x-optimized-image src="{{ $post->thumbnail_url }}" alt="Gambar artikel: {{ $post->title }}"
                                           width="600" height="400" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                        <div style="font-size:4rem;">📝</div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($post->published_at)
                        <div class="text-muted small mb-2">{{ $post->published_at->format('d M Y') }}</div>
                        @endif
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
