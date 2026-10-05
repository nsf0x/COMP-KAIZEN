@extends('layouts.app')

@section('title', 'Portofolio')
@section('meta_description', 'Galeri portofolio event dan pesta yang telah kami tangani. Lihat karya terbaik kami.')

@section('content')

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <nav aria-label="breadcrumb" class="justify-content-center d-flex mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
                <li class="breadcrumb-item active">Portofolio</li>
            </ol>
        </nav>
        <h1 class="section-title">Portofolio Kami</h1>
        <div class="divider-pink mx-auto my-3"></div>
        <p class="section-subtitle">Momen-momen spesial yang telah kami wujudkan bersama pelanggan</p>
    </div>

    @if($portfolios->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:4rem;">📷</div>
        <h5 class="mt-3">Belum ada portofolio</h5>
    </div>
    @else
    <div class="row g-4">
        @foreach($portfolios as $portfolio)
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden;">
                <div style="height:240px; overflow:hidden; background:#ffeef9; display:flex;align-items:center;justify-content:center;">
                    @if($portfolio->thumbnail)
                    <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}"
                         style="width:100%;height:100%;object-fit:cover; transition:transform .3s;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform=''">
                    @else
                    <div style="font-size:5rem;">🎉</div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    @if($portfolio->type)
                    <span class="badge mb-2" style="background:#ffe4f5; color:var(--primary);">{{ ucfirst($portfolio->type) }}</span>
                    @endif
                    <h5 class="fw-700 mb-1">{{ $portfolio->title }}</h5>
                    @if($portfolio->event_date)
                    <div class="text-muted small mb-2"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($portfolio->event_date)->format('d M Y') }}</div>
                    @endif
                    <p class="text-muted small mb-3">{{ Str::limit($portfolio->description, 100) }}</p>
                    <a href="{{ route('portfolio.show', $portfolio) }}" class="btn btn-sm btn-primary-custom rounded-pill mt-auto align-self-start">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $portfolios->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
