@extends('layouts.app')

@section('title', 'Syarat Layanan')
@section('meta_description', 'Syarat layanan penggunaan jasa ' . config('company.name') . '.')

@section('content')
<section class="py-5">
    <div class="container" style="max-width:860px;">
        <h1 class="section-title mb-3">Syarat Layanan</h1>
        <div class="divider-pink my-3"></div>
        <h2 class="h5 fw-bold mt-4">Pemesanan</h2>
        <p class="text-muted">Ketersediaan produk, jadwal, biaya pengiriman, dan detail layanan dikonfirmasi oleh tim kami sebelum pemesanan disepakati.</p>
        <h2 class="h5 fw-bold mt-4">Penggunaan perlengkapan</h2>
        <p class="text-muted">Pelanggan bertanggung jawab menggunakan perlengkapan sesuai fungsi dan mengembalikannya dalam kondisi yang disepakati. Ketentuan biaya kerusakan atau kehilangan akan dijelaskan sebelum konfirmasi.</p>
        <h2 class="h5 fw-bold mt-4">Perubahan dan pembatalan</h2>
        <p class="text-muted">Perubahan jadwal atau pembatalan mengikuti kesepakatan yang diberikan tim kami berdasarkan jenis layanan dan waktu pelaksanaan.</p>
        <h2 class="h5 fw-bold mt-4">Pertanyaan</h2>
        <p class="text-muted">Hubungi kami melalui WhatsApp untuk mendapatkan penjelasan lengkap sebelum melakukan pemesanan.</p>
    </div>
</section>
@endsection
