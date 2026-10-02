@extends('layouts.app')

@section('title', 'Blog & Artikel')
@section('meta_description', 'Tips dan inspirasi seputar dekorasi pesta, pernikahan, dan event dari Party Rental Pro.')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <nav aria-label="breadcrumb" class="justify-content-center d-flex mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
                <li class="breadcrumb-item active">Blog</li>
            </ol>
        </nav>
        <h1 class="section-title">Blog & Artikel</h1>
        <div class="divider-pink mx-auto my-3"></div>
        <p class="section-subtitle">Tips, inspirasi, dan informasi seputar pesta dan event</p>
    </div>

    <!-- Search -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <form action="{{ route('blog.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari artikel..."
                           value="{{ request('search') }}">
                    <button class="btn btn-primary-custom rounded-end-pill" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($posts->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">📝</div>
        <h5 class="mt-3">Tidak ada artikel ditemukan</h5>
        @if(request('search'))
        <a href="{{ route('blog.index') }}" class="btn btn-primary-custom mt-2 rounded-pill">Lihat Semua Artikel</a>
        @endif
    </div>
    @else
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden;">
                <div style="height:200px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                    @if($post->thumbnail)
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}"
                         style="width:100%;height:100%;object-fit:cover; transition:transform .3s;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform=''">
                    @else
                    <div style="font-size:4rem;">📝</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="text-muted small mb-2">
                        <i class="bi bi-calendar3 me-1"></i>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}
                        <span class="mx-2">·</span>
                        <i class="bi bi-person me-1"></i>{{ $post->author ?? 'Admin' }}
                    </div>
                    <h5 class="fw-700 mb-2">{{ $post->title }}</h5>
                    <p class="text-muted small mb-3 flex-grow-1">{{ $post->excerpt }}</p>
                    <a href="{{ route('blog.show', $post) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
