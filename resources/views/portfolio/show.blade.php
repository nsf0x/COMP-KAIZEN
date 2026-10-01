@extends('layouts.app')

@section('title', $portfolio->title)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('portfolio.index') }}" style="color:var(--primary);">Portofolio</a></li>
            <li class="breadcrumb-item active">{{ $portfolio->title }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-8">
            @if($portfolio->thumbnail)
            <div class="rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height:450px;">
                <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}"
                     style="width:100%; height:450px; object-fit:cover;">
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
            <div class="text-muted" style="line-height:1.9;">{{ $portfolio->description }}</div>
            @endif

            @if($portfolio->video_embed_url)
            <section class="mt-4" aria-label="Video portofolio">
                <h5 class="fw-700 mb-3">Video</h5>
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                    <iframe src="{{ $portfolio->video_embed_url }}" title="Video {{ $portfolio->title }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </section>
            @elseif($portfolio->video_is_file)
            <section class="mt-4" aria-label="Video portofolio">
                <h5 class="fw-700 mb-3">Video</h5>
                <video class="w-100 rounded-3" controls preload="metadata">
                    <source src="{{ $portfolio->video_url }}">
                    Browser Anda tidak mendukung pemutar video.
                </video>
            </section>
            @endif

            @php
                $galleryImages = [];
                if ($portfolio->images && $portfolio->images->isNotEmpty()) {
                    $galleryImages = $portfolio->images->pluck('image_path')->all();
                } elseif (!empty($portfolio->gallery)) {
                    $galleryImages = is_array($portfolio->gallery) ? $portfolio->gallery : json_decode($portfolio->gallery, true);
                }
            @endphp

            @if(!empty($galleryImages))
            <h5 class="fw-700 mt-5 mb-3">Galeri Foto</h5>
            <div class="row g-3">
                @foreach($galleryImages as $img)
                <div class="col-6 col-md-4">
                    <div class="rounded-3 overflow-hidden" style="height:180px;">
                        <img src="{{ asset('storage/' . $img) }}" alt="Gallery" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- CTA Card -->
            <div class="card border-0 shadow p-4 mb-4" style="border-radius:16px; background:linear-gradient(135deg,#fdf6fb,#fff);">
                <h6 class="fw-700 mb-3">Ingin event seperti ini?</h6>
                <p class="text-muted small mb-4">Hubungi kami untuk konsultasi gratis dan penawaran terbaik!</p>
                <a href="https://wa.me/{{ config('company.whatsapp') }}?text={{ urlencode('Halo, saya tertarik untuk membuat event seperti portofolio "' . $portfolio->title . '". Boleh konsultasi?') }}"
                   class="btn btn-success rounded-pill w-100 mb-2" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i>Chat WhatsApp
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary rounded-pill w-100">
                    <i class="bi bi-envelope me-2"></i>Kirim Pesan
                </a>
            </div>

            <!-- Related -->
            @if($related->count())
            <h6 class="fw-700 mb-3">Portofolio Lainnya</h6>
            @foreach($related as $item)
            <a href="{{ route('portfolio.show', $item) }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark mb-3 p-2 rounded-3"
               style="background:#fdf6fb; transition:background .2s;"
               onmouseover="this.style.background='#ffe4f5'" onmouseout="this.style.background='#fdf6fb'">
                <div style="width:64px;height:64px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#ffeef9;display:flex;align-items:center;justify-content:center;">
                    @if($item->thumbnail)
                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <div style="font-size:2rem;">🎉</div>
                    @endif
                </div>
                <div>
                    <div class="fw-600 small">{{ $item->title }}</div>
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
