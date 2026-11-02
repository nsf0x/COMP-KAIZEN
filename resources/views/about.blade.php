@extends('layouts.app')

@section('title', 'Tentang Kami')
@section('meta_description', 'Kenali ' . config('company.name') . ', penyedia perlengkapan event berbasis di Bekasi untuk wilayah Jabodetabek dan pengiriman luar kota bagi event skala besar.')

@section('content')

<!-- Hero -->
<section style="background: linear-gradient(135deg, #fdf6fb, #fff0fa); padding: 80px 0;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="section-title mb-3">Tentang Kami</h1>
        <div class="divider-pink mx-auto my-3"></div>
        <p class="lead text-muted mx-auto" style="max-width:600px;">{{ config('company.description') }}</p>
    </div>
</section>

<!-- Story Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div style="font-size:12rem; text-align:center; line-height:1; filter:drop-shadow(0 20px 40px rgba(233,30,140,0.2));">🎊</div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="section-title mb-4">Cerita Kami</h2>
                <p class="text-muted lh-lg">
                    {{ config('company.name') }} menyediakan perlengkapan untuk berbagai kebutuhan acara, mulai dari tenda, flooring, genset, LED screen, sound system, hingga furniture. Kami berbasis di Bekasi, melayani wilayah Jabodetabek, serta menerima permintaan pengiriman luar kota untuk event skala besar.
                </p>
                <p class="text-muted lh-lg">
                    Hubungi kami melalui WhatsApp untuk menyampaikan jenis acara, lokasi, tanggal, dan perlengkapan yang dibutuhkan. Ketersediaan serta rincian penawaran akan dikonfirmasi berdasarkan kebutuhan acara.
                </p>
                @if(config('company.stats.events') || config('company.stats.clients') || config('company.stats.years'))
                <div class="row g-3 mt-2">
                    @if(config('company.stats.events'))
                    <div class="col-4 text-center p-3 rounded-3" style="background:#fdf6fb;">
                        <div class="fw-700 fs-2" style="color:var(--primary);">{{ config('company.stats.events') }}</div>
                        <div class="text-muted small">Event</div>
                    </div>
                    @endif
                    @if(config('company.stats.clients'))
                    <div class="col-4 text-center p-3 rounded-3" style="background:#fdf6fb;">
                        <div class="fw-700 fs-2" style="color:var(--primary);">{{ config('company.stats.clients') }}</div>
                        <div class="text-muted small">Klien</div>
                    </div>
                    @endif
                    @if(config('company.stats.years'))
                    <div class="col-4 text-center p-3 rounded-3" style="background:#fdf6fb;">
                        <div class="fw-700 fs-2" style="color:var(--primary);">{{ config('company.stats.years') }}</div>
                        <div class="text-muted small">Tahun Pengalaman</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Team -->
@if($team->count())
<section class="py-5" style="background:var(--light-bg);">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Tim Kami</h2>
            <div class="divider-pink mx-auto my-3"></div>
            <p class="section-subtitle">Orang-orang hebat di balik layanan kami</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($team as $member)
            <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="card border-0 shadow-sm text-center p-4 h-100" style="border-radius:16px;">
                    @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"
                         class="rounded-circle mx-auto mb-3" style="width:96px;height:96px;object-fit:cover;">
                    @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-700 fs-2 text-white"
                         style="width:96px;height:96px;background:linear-gradient(135deg,var(--primary),var(--secondary));">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    @endif
                    <h6 class="fw-700 mb-1">{{ $member->name }}</h6>
                    <div class="text-muted small mb-2" style="color:var(--primary) !important; font-weight:600;">{{ $member->position }}</div>
                    @if($member->description)
                    <p class="text-muted" style="font-size:.8rem;">{{ Str::limit($member->description, 80) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials -->
@if($testimonials->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Testimoni Pelanggan</h2>
            <div class="divider-pink mx-auto my-3"></div>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="card border-0 shadow-sm h-100 p-4" style="border-radius:16px;">
                    <div class="mb-2" style="color:#ffd700;">
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

<!-- Clients -->
@if($clients->count())
<section class="py-4" style="background:#f8f8f8;">
    <div class="container">
        <div class="text-center mb-4">
            <p class="text-muted small fw-600 text-uppercase">Klien yang Telah Mempercayai Kami</p>
        </div>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-5">
            @foreach($clients as $client)
            <div style="opacity:.7;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.7'">
                @if($client->logo)
                <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" style="height:50px;object-fit:contain;filter:grayscale(40%);">
                @else
                <span class="fw-600 text-muted fs-5">{{ $client->name }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="text-white fw-700 mb-3">Yuk, Wujudkan Event Impian Anda!</h2>
        <p class="text-white opacity-75 mb-4">{{ config('company.hours.whatsapp') }}.</p>
        <a href="https://wa.me/{{ config('company.whatsapp') }}" class="btn btn-light btn-lg rounded-pill px-5" target="_blank">
            <i class="bi bi-whatsapp me-2 text-success"></i>Hubungi Kami
        </a>
    </div>
</section>

@endsection
