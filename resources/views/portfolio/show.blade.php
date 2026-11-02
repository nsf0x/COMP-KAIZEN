@extends('layouts.app')

@section('title', $portfolio->title)
@section('meta_description', Str::limit(trim(strip_tags($portfolio->description ?: ('Dokumentasi event ' . $portfolio->title . ' yang ditangani oleh ' . config('company.name') . '.'))), 160))
@section('meta_image', $portfolio->thumbnail ? $portfolio->thumbnail_url : rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png')

@push('structured_data')
    @include('partials.seo.breadcrumb-schema', ['items' => [
        ['name' => 'Beranda', 'url' => route('home', [], true)],
        ['name' => 'Event Portofolio', 'url' => route('portfolio.index', [], true)],
        ['name' => $portfolio->title, 'url' => route('portfolio.show', $portfolio, true)],
    ]])
@endpush

@push('styles')
<style>
    .portfolio-related-heading { color: #25283a; }
    .portfolio-related-link { color: #25283a; background: #fdf6fb; transition: background-color .2s, color .2s; }
    .portfolio-related-link:hover,
    .portfolio-related-link:focus-visible { color: #1736e8; background: #ffe4f5; }
    .portfolio-related-title { color: inherit; }
    html.dark .portfolio-related-heading { color: #f1f2f7; }
    html.dark .portfolio-related-link { color: #f1f2f7; background: #202230; }
    html.dark .portfolio-related-link:hover,
    html.dark .portfolio-related-link:focus-visible { color: #fff; background: #2c3041; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('portfolio.index') }}" style="color:var(--primary);">Event Portofolio</a></li>
            <li class="breadcrumb-item active">{{ $portfolio->title }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-8">
            @php
                $galleryImages = $portfolio->images->pluck('image_path')->all();
                if (empty($galleryImages) && $portfolio->thumbnail) {
                    $galleryImages = [$portfolio->thumbnail];
                }
            @endphp

            @if(count($galleryImages) === 1)
            <div class="rounded-4 overflow-hidden mb-4 shadow-sm">
                <img src="{{ asset('storage/' . $galleryImages[0]) }}" alt="{{ $portfolio->title }}"
                     style="width:100%; max-height:450px; object-fit:cover;">
            </div>
            @elseif(count($galleryImages) > 1)
            <div id="portfolio-gallery-{{ $portfolio->id }}" class="carousel slide rounded-4 overflow-hidden mb-4 shadow-sm"
                 data-bs-ride="false" aria-label="Galeri foto {{ $portfolio->title }}">
                <div class="carousel-indicators">
                    @foreach($galleryImages as $index => $image)
                    <button type="button" data-bs-target="#portfolio-gallery-{{ $portfolio->id }}"
                            data-bs-slide-to="{{ $index }}" @if($index === 0) class="active" aria-current="true" @endif
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($galleryImages as $index => $image)
                    <div class="carousel-item @if($index === 0) active @endif">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $portfolio->title }} - foto {{ $index + 1 }}"
                             style="width:100%; height:450px; object-fit:cover;">
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#portfolio-gallery-{{ $portfolio->id }}"
                        data-bs-slide="prev" aria-label="Foto sebelumnya">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#portfolio-gallery-{{ $portfolio->id }}"
                        data-bs-slide="next" aria-label="Foto berikutnya">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
            @else
            <div class="rounded-4 mb-4 p-5 text-center text-muted" style="background:#fdf6fb;">
                Dokumentasi foto belum tersedia.
            </div>
            @endif

            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($portfolio->type)
                <span class="badge px-3 py-2" style="background:#ffe4f5; color:var(--primary);">{{ ucfirst($portfolio->type) }}</span>
                @endif
                @if($portfolio->is_featured)
                <span class="badge px-3 py-2" style="background:var(--primary);">⭐ Unggulan</span>
                @endif
            </div>

            <h1 class="fw-700 mb-2">{{ $portfolio->title }}</h1>

            @if($portfolio->event_date)
            <p class="text-muted mb-4"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($portfolio->event_date)->format('d F Y') }}</p>
            @endif

            @if($portfolio->description)
            <div class="text-muted" style="line-height:1.9; white-space:pre-line;">{{ $portfolio->description }}</div>
            @endif

            @if($portfolio->video_file_url)
            <section class="mt-4" aria-label="Video event portofolio">
                <h5 class="fw-700 mb-3">Video Upload</h5>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                    <video controls preload="metadata">
                        <source src="{{ $portfolio->video_file_url }}">
                        Browser Anda tidak mendukung pemutar video.
                    </video>
                </div>
            </section>
            @endif

            @if($portfolio->video_embed_url)
            <section class="mt-4" aria-label="Video event portofolio">
                <h5 class="fw-700 mb-3">Video</h5>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                    <iframe src="{{ $portfolio->video_embed_url }}" title="Video {{ $portfolio->title }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </section>
            @elseif($portfolio->video_is_file)
            <section class="mt-4" aria-label="Video event portofolio">
                <h5 class="fw-700 mb-3">Video</h5>
                <video class="w-100 rounded-3" controls preload="metadata">
                    <source src="{{ $portfolio->video_url }}">
                    Browser Anda tidak mendukung pemutar video.
                </video>
            </section>
            @endif

        </div>

        <div class="col-lg-4">
            <!-- CTA Card -->
            <div class="card border-0 shadow p-4 mb-4" style="border-radius:16px; background:linear-gradient(135deg,#fdf6fb,#fff);">
                <h6 class="fw-700 mb-3">Ingin event seperti ini?</h6>
                <p class="text-muted small mb-4">Hubungi kami untuk konsultasi gratis dan penawaran terbaik!</p>
                <a href="https://wa.me/{{ config('company.whatsapp') }}?text={{ urlencode('Halo, saya tertarik untuk membuat event seperti Event Portofolio "' . $portfolio->title . '". Boleh konsultasi?') }}"
                   class="btn btn-success rounded-pill w-100 mb-2" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i>Chat WhatsApp
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary rounded-pill w-100">
                    <i class="bi bi-envelope me-2"></i>Kirim Pesan
                </a>
            </div>

            <!-- Related -->
            @if($related->count())
            <h6 class="portfolio-related-heading fw-700 mb-3">Event Portofolio Lainnya</h6>
            @foreach($related as $item)
            <a href="{{ route('portfolio.show', $item) }}" class="portfolio-related-link d-flex align-items-center gap-3 text-decoration-none mb-3 p-2 rounded-3">
                <div style="width:64px;height:64px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#ffeef9;display:flex;align-items:center;justify-content:center;">
                    @if($item->thumbnail)
                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <div style="font-size:2rem;">🎉</div>
                    @endif
                </div>
                <div>
                    <div class="portfolio-related-title fw-600 small">{{ $item->title }}</div>
                    @if($item->type)
                    <div class="text-muted" style="font-size:.75rem;">{{ ucfirst($item->type) }}</div>
                    @endif
                </div>
            </a>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
