@extends('layouts.app')

@section('title', $areaPage->title)
@section('meta_description', Str::limit($areaPage->unique_content, 160))

@push('structured_data')
    @include('partials.seo.breadcrumb-schema', ['items' => [
        ['name' => 'Beranda', 'url' => route('home', [], true)],
        ['name' => $areaPage->title, 'url' => url()->current()],
    ]])
@endpush

@section('content')
<main class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $areaPage->title }}</li>
        </ol>
    </nav>

    <article>
        <h1 class="section-title mb-4">{{ $areaPage->title }}</h1>
        <div class="text-muted lh-lg">{!! nl2br(e($areaPage->unique_content)) !!}</div>

        @foreach([
            'delivery_time' => 'Informasi pengiriman',
            'venue_details' => 'Informasi lokasi dan venue',
            'portfolio_details' => 'Dokumentasi area',
        ] as $field => $heading)
            @if(filled($areaPage->{$field}))
            <section class="mt-4">
                <h2 class="h5 fw-700">{{ $heading }}</h2>
                <div class="text-muted lh-lg">{!! nl2br(e($areaPage->{$field})) !!}</div>
            </section>
            @endif
        @endforeach

        <div class="mt-5 p-4 rounded-4 text-center" style="background:#fdf6fb;">
            <h2 class="h5 fw-700">Tanyakan kebutuhan event Anda</h2>
            <p class="text-muted">Ketersediaan dan detail penawaran dikonfirmasi melalui WhatsApp.</p>
            <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-success rounded-pill" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-whatsapp me-2"></i>Hubungi Kaizen
            </a>
        </div>
    </article>
</main>
@endsection
