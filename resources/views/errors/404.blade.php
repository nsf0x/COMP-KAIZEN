@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')
@section('meta_description', 'Halaman yang Anda cari tidak tersedia. Kunjungi katalog produk atau hubungi Kaizen Kreasi Indonesia.')
@section('meta_robots', 'noindex,nofollow')

@section('content')
<main class="container py-5 text-center" style="min-height:50vh;">
    <p class="display-1 fw-bold mb-0" style="color:var(--primary);">404</p>
    <h1 class="h3 fw-bold">Halaman tidak ditemukan</h1>
    <p class="text-muted mb-4">Alamat mungkin berubah atau halaman yang dicari tidak tersedia.</p>
    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4">Kembali ke Beranda</a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Lihat Produk</a>
    </div>
</main>
@endsection
