@extends('layouts.app')

@section('title', 'Kebijakan Privasi')
@section('meta_description', 'Kebijakan privasi ' . config('company.name') . '.')

@section('content')
<section class="py-5">
    <div class="container" style="max-width:860px;">
        <h1 class="section-title mb-3">Kebijakan Privasi</h1>
        <div class="divider-pink my-3"></div>
        <p class="text-muted">Kami menghormati privasi Anda. Data yang dikirim melalui formulir kontak digunakan untuk menanggapi pertanyaan, menyiapkan penawaran, dan memberikan layanan yang Anda minta.</p>
        <h2 class="h5 fw-bold mt-4">Data yang kami kumpulkan</h2>
        <p class="text-muted">Kami dapat menerima nama, email, nomor telepon, subjek, dan isi pesan yang Anda masukkan sendiri melalui formulir kontak.</p>
        <h2 class="h5 fw-bold mt-4">Penggunaan data</h2>
        <p class="text-muted">Data digunakan untuk komunikasi layanan dan tidak dijual kepada pihak lain. Kami menyimpan data selama diperlukan untuk tujuan layanan dan kewajiban hukum.</p>
        <h2 class="h5 fw-bold mt-4">Kontak</h2>
        <p class="text-muted">Untuk pertanyaan terkait data pribadi, hubungi {{ config('company.email') }}.</p>
    </div>
</section>
@endsection
