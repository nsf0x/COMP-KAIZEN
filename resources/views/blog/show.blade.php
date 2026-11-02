@extends('layouts.app')

@section('title', $blogPost->title)
@section('meta_description', $blogPost->meta_description ?? $blogPost->excerpt)
@section('meta_image', $blogPost->thumbnail ? $blogPost->thumbnail_url : rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png')
@section('og_type', 'article')

@push('structured_data')
<script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $blogPost->title,
        'description' => $blogPost->meta_description ?? $blogPost->excerpt,
        'image' => $blogPost->thumbnail ? $blogPost->thumbnail_url : null,
        'datePublished' => $blogPost->published_at?->toAtomString(),
        'dateModified' => $blogPost->updated_at->toAtomString(),
        'author' => filled($blogPost->author) && strtolower($blogPost->author) !== 'admin' ? [
            '@type' => 'Person',
            'name' => $blogPost->author,
        ] : null,
        'publisher' => [
            '@type' => 'Organization',
            'name' => config('company.name'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => rtrim(config('app.url'), '/') . '/images/kaizen-logo-color.png',
            ],
        ],
        'mainEntityOfPage' => rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/'),
    ], fn ($value) => $value !== null && $value !== ''), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-5 justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" style="color:var(--primary);">Blog</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($blogPost->title, 30) }}</li>
                </ol>
            </nav>

            @if($blogPost->thumbnail)
            <div class="rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height:420px;">
                <img src="{{ $blogPost->thumbnail_url }}" alt="{{ $blogPost->title }}"
                     style="width:100%; height:420px; object-fit:cover;">
            </div>
            @endif

            <h1 class="fw-700 fs-2 mb-3">{{ $blogPost->title }}</h1>
            <div class="d-flex align-items-center gap-3 text-muted small mb-4">
                @if($blogPost->published_at)
                <span><i class="bi bi-calendar3 me-1"></i>{{ $blogPost->published_at->format('d F Y') }}</span>
                @endif
                @if(filled($blogPost->author) && strtolower($blogPost->author) !== 'admin')
                <span><i class="bi bi-person me-1"></i>{{ $blogPost->author }}</span>
                @endif
            </div>

            <div class="prose lh-lg text-muted" style="font-size:1.05rem;">
                {!! nl2br(e($blogPost->content)) !!}
            </div>

            @if($relatedProducts->isNotEmpty())
            <section class="mt-5" aria-labelledby="related-products-heading">
                <h2 id="related-products-heading" class="h5 fw-700 mb-3">Perlengkapan terkait</h2>
                <ul class="list-unstyled">
                    @foreach($relatedProducts as $product)
                    <li class="mb-2"><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></li>
                    @endforeach
                </ul>
            </section>
            @endif

            @php
                $galleryImages = [];
                if ($blogPost->images && $blogPost->images->isNotEmpty()) {
                    $galleryImages = $blogPost->images->pluck('image_path')->all();
                } elseif (!empty($blogPost->gallery)) {
                    $galleryImages = is_array($blogPost->gallery) ? $blogPost->gallery : json_decode($blogPost->gallery, true);
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

            <!-- Share -->
            <div class="mt-5 p-4 rounded-4" style="background:#fdf6fb;">
                <h6 class="fw-700 mb-3">Bagikan Artikel Ini</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="https://wa.me/{{ config('company.whatsapp') }}?text={{ urlencode($blogPost->title . ' - ' . url()->current()) }}"
                       class="btn btn-success btn-sm rounded-pill" target="_blank">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       class="btn btn-primary btn-sm rounded-pill" target="_blank">
                        <i class="bi bi-facebook me-1"></i>Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($blogPost->title) }}&url={{ urlencode(url()->current()) }}"
                       class="btn btn-info btn-sm rounded-pill text-white" target="_blank">
                        <i class="bi bi-twitter-x me-1"></i>Twitter
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- CTA -->
            <div class="card border-0 shadow p-4 mb-4" style="border-radius:16px; background:linear-gradient(135deg,#fdf6fb,#fff);">
                <div style="font-size:3rem; text-align:center;">🎊</div>
                <h6 class="fw-700 mt-2 mb-2 text-center">Butuh Perlengkapan Pesta?</h6>
                <p class="text-muted small text-center mb-3">Konsultasi gratis dengan tim kami sekarang!</p>
                <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-success rounded-pill w-100" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i>Chat Sekarang
                </a>
            </div>

            <!-- Related Posts -->
            @if($related->count())
            <h6 class="fw-700 mb-3">Artikel Terkait</h6>
            @foreach($related as $item)
            <a href="{{ route('blog.show', $item) }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark mb-3 p-2 rounded-3"
               style="background:#fdf6fb; transition:background .2s;"
               onmouseover="this.style.background='#ffe4f5'" onmouseout="this.style.background='#fdf6fb'">
                <div style="width:64px;height:64px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#ffeef9;display:flex;align-items:center;justify-content:center;">
                    @if($item->thumbnail)
                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <div style="font-size:2rem;">📝</div>
                    @endif
                </div>
                <div>
                    <div class="fw-600 small">{{ Str::limit($item->title, 50) }}</div>
                    @if($item->published_at)
                    <div class="text-muted" style="font-size:.75rem;">{{ $item->published_at->format('d M Y') }}</div>
                    @endif
                </div>
            </a>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
