@extends('layouts.app')

@php
    $salePageTitle = match ($selectedCategory) {
        'led-lighting' => 'Jual LED Lighting Event Bekasi | Kaizen Kreasi Indonesia',
        'sound-system' => 'Jual Sound System Event Bekasi | Kaizen Kreasi Indonesia',
        default => 'Jual & Sewa LED Lighting dan Sound System Bekasi | Kaizen Kreasi Indonesia',
    };
    $salePageDescription = match ($selectedCategory) {
        'led-lighting' => 'Jual dan sewa perlengkapan LED Lighting event di Bekasi. Kaizen Kreasi Indonesia melayani Jabodetabek, informasi melalui WhatsApp.',
        'sound-system' => 'Informasi penjualan Sound System event di Bekasi. Kaizen Kreasi Indonesia melayani Jabodetabek; produk menyusul segera.',
        default => 'Jual dan sewa perlengkapan LED Lighting serta Sound System event di Bekasi. Kaizen Kreasi Indonesia melayani Jabodetabek.',
    };
@endphp

@section('title', $salePageTitle)
@section('meta_title', $salePageTitle)
@section('meta_description', $salePageDescription)

@push('structured_data')
    @include('partials.seo.breadcrumb-schema', ['items' => [
        ['name' => 'Beranda', 'url' => route('home', [], true)],
        ['name' => 'Jual', 'url' => route('products.sales', [], true)],
    ]])
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jual</li>
        </ol>
    </nav>

    <header class="mb-4">
        <h1 class="section-title">Jual LED Lighting &amp; Sound System Event</h1>
        <p class="text-muted">Produk LED Lighting dapat dibeli maupun disewa. Kami melayani Bekasi dan Jabodetabek; informasi harga dan konfirmasi ketersediaan melalui WhatsApp.</p>
    </header>

    <nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Filter kategori produk jual">
        <a href="{{ route('products.sales') }}"
           class="btn {{ !$selectedCategory ? 'btn-primary-custom' : 'btn-outline-secondary' }} rounded-pill">Semua Kategori</a>
        @foreach($categories as $category)
        <a href="{{ route('products.sales', ['kategori' => $category->slug]) }}"
           class="btn {{ $selectedCategory === $category->slug ? 'btn-primary-custom' : 'btn-outline-secondary' }} rounded-pill">
            {{ $category->display_name }}
        </a>
        @endforeach
    </nav>

    @if(!$soundSystemHasProducts && (!$selectedCategory || $selectedCategory === 'sound-system'))
    <div class="alert alert-info" role="status">
        <strong>Sound System — Segera hadir.</strong> Informasi produk Sound System untuk dijual akan ditambahkan.
    </div>
    @endif

    @if($products->isEmpty())
    <div class="text-center py-5">
        <h2 class="h5">Produk kategori ini segera hadir.</h2>
        <a href="{{ route('products.sales') }}" class="btn btn-primary-custom rounded-pill mt-2">Lihat Semua Produk Jual</a>
    </div>
    @else
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-sm-6 col-lg-4">
            <article class="card border-0 shadow-sm h-100" data-product-name="{{ $product->name }}" style="border-radius:16px; overflow:hidden;">
                <div class="product-card-media">
                    @if($product->thumbnail)
                    <x-optimized-image src="{{ $product->thumbnail_url }}" alt="Perlengkapan event: {{ $product->name }}"
                                       width="600" height="400" style="width:100%;height:100%;object-fit:cover;" />
                    @else
                    <img src="{{ asset('images/product-placeholder.svg') }}" alt="Placeholder foto produk: {{ $product->name }}"
                         width="600" height="400" style="width:100%;height:100%;object-fit:cover;" />
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    @include('products.partials.availability-badge', ['product' => $product])
                    <span class="badge mb-2" style="background:#e8eeff; color:var(--primary); width:fit-content;">
                        {{ $product->category->display_name ?? 'Umum' }}
                    </span>
                    <h2 class="product-card-title h5 fw-700">{{ $product->name }}</h2>
                    @if($product->description)
                    <p class="product-card-description text-muted small">{{ $product->description }}</p>
                    @endif
                    <p class="text-muted small mb-3">Harga hubungi kami via WhatsApp</p>
                    @include('products.partials.whatsapp-actions', ['product' => $product, 'compact' => true])
                </div>
            </article>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
