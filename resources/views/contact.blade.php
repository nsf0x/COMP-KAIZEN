@extends('layouts.app')

@section('title', 'Kontak Kami')
@section('meta_description', 'Hubungi ' . config('company.name') . ' untuk konsultasi dan pemesanan perlengkapan pesta.')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <nav aria-label="breadcrumb" class="justify-content-center d-flex mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--primary);">Beranda</a></li>
                <li class="breadcrumb-item active">Kontak</li>
            </ol>
        </nav>
        <h1 class="section-title">Hubungi Kami</h1>
        <div class="divider-pink mx-auto my-3"></div>
        <p class="section-subtitle">Kami siap membantu Anda mewujudkan acara impian</p>
    </div>

    <div class="row g-5 justify-content-center">
        <!-- Contact Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius:16px;">
                <h5 class="fw-700 mb-4">Informasi Kontak</h5>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div style="width:48px;height:48px;border-radius:12px;background:#ffe4f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem;">📍</div>
                    <div>
                        <div class="fw-600 small mb-1">Alamat</div>
                        <div class="text-muted small">{{ config('company.address') }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div style="width:48px;height:48px;border-radius:12px;background:#ffe4f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem;">📞</div>
                    <div>
                        <div class="fw-600 small mb-1">Telepon / WhatsApp</div>
                        @foreach (array_merge([config('company.phone')], config('company.additional_phones', [])) as $phone)
                            <a href="tel:{{ $phone }}" class="text-muted small d-block text-decoration-none">{{ $phone }}</a>
                        @endforeach
                        <a href="https://wa.me/{{ config('company.whatsapp') }}" class="text-muted small text-decoration-none" target="_blank">WhatsApp</a>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div style="width:48px;height:48px;border-radius:12px;background:#ffe4f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem;">✉️</div>
                    <div>
                        <div class="fw-600 small mb-1">Email</div>
                        <a href="mailto:{{ config('company.email') }}" class="text-muted small text-decoration-none">{{ config('company.email') }}</a>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div style="width:48px;height:48px;border-radius:12px;background:#ffe4f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem;">🕐</div>
                    <div>
                        <div class="fw-600 small mb-1">Jam Operasional</div>
                        <div class="text-muted small">{{ config('company.hours.weekday') }}</div>
                        <div class="text-muted small">{{ config('company.hours.weekend') }}</div>
                        <div class="text-muted small">{{ config('company.hours.holiday') }}</div>
                    </div>
                </div>

                <hr>

                <!-- Direct WA Button -->
                <a href="https://wa.me/{{ config('company.whatsapp') }}?text={{ urlencode('Halo ' . config('company.name') . ', saya ingin berkonsultasi tentang kebutuhan acara saya.') }}" class="btn btn-success rounded-pill w-100" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i>Chat WhatsApp Langsung
                </a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 p-lg-5" style="border-radius:16px;">
                <h5 class="fw-700 mb-4">Kirim Pesan</h5>

                @if(session('success'))
                <div class="alert alert-success rounded-3 d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-600">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                   placeholder="Nama Anda" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-600">No. WhatsApp / Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                   placeholder="08xx-xxxx-xxxx" value="{{ old('phone') }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-600">Email (opsional)</label>
                            <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   placeholder="email@anda.com" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-600">Subjek / Jenis Acara <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control rounded-3 @error('subject') is-invalid @enderror"
                                   placeholder="misal: Sewa tenda pernikahan 300 orang" value="{{ old('subject') }}" required>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-600">Pesan <span class="text-danger">*</span></label>
                            <textarea name="message" rows="5" class="form-control rounded-3 @error('message') is-invalid @enderror"
                                      placeholder="Ceritakan kebutuhan event Anda..." required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-custom btn-lg rounded-pill px-5">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Map -->
    @if(config('company.maps_embed'))
    <div class="mt-5 rounded-4 overflow-hidden shadow-sm" style="height:350px;" data-aos="fade-up">
        <iframe src="{{ config('company.maps_embed') }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    @endif
</div>
@endsection
