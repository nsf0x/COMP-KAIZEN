<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $articles = [
            [
                'title' => 'Persiapan Display Visual untuk Event',
                'slug' => 'persiapan-display-visual-event',
                'thumbnail' => null,
                'content' => "Perencanaan tampilan visual dimulai dengan menjelaskan tujuan penggunaan, lokasi, ukuran area, serta konten yang akan ditampilkan. Informasi tersebut membantu tim memeriksa perangkat yang sesuai dan ketersediaannya.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan LED screen P2.6 dan P3.9 dengan pilihan indoor dan outdoor, processor Novastar VX16S, VX600, dan VX400, serta Smart TV ukuran 55, 65, dan 75 inch. Model dan kebutuhan teknis perlu dikonfirmasi untuk setiap acara.\n\nSebelum berkonsultasi, siapkan tanggal, lokasi penggunaan, ukuran tampilan yang dibutuhkan, jenis konten, dan perangkat yang sudah tersedia di venue.",
                'meta_description' => 'Informasi yang perlu disiapkan saat memilih LED screen, processor, atau Smart TV untuk kebutuhan visual event.',
                'author' => '',
                'published_at' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Informasi untuk Merencanakan Kebutuhan Daya Event',
                'slug' => 'perencanaan-kebutuhan-daya-event',
                'thumbnail' => null,
                'content' => "Kebutuhan daya perlu dibahas berdasarkan peralatan yang digunakan, lokasi, dan susunan acara. Siapkan daftar perangkat serta informasi kelistrikan venue yang tersedia sebelum meminta rekomendasi.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan genset 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 watt. Kapasitas, kecocokan untuk kebutuhan acara, dan ketersediaan perlu dikonfirmasi secara langsung.\n\nSampaikan tanggal, lokasi, daftar peralatan, serta informasi sumber daya venue agar tim dapat memeriksa kebutuhan dan ketersediaan.",
                'meta_description' => 'Daftar informasi acara yang membantu saat berkonsultasi mengenai pilihan genset dan kebutuhan daya.',
                'author' => '',
                'published_at' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Persiapan Panggung, Backdrop, dan Branding Event',
                'slug' => 'persiapan-panggung-backdrop-branding',
                'thumbnail' => null,
                'content' => "Sebelum merencanakan panggung, backdrop, partisi, atau branding, kumpulkan informasi tentang ukuran area, tata letak venue, jadwal acara, dan aturan pemasangan. Denah atau gambar lokasi dapat membantu menjelaskan kebutuhan.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan perlengkapan seperti stage, backdrop, Partisi R8, serta produksi gate dan branding. Bentuk, ukuran, materi, dan ketersediaan perlu dikonfirmasi sesuai kebutuhan acara.\n\nSampaikan tanggal, lokasi, ukuran area, konsep visual, dan informasi akses pemasangan saat berkonsultasi.",
                'meta_description' => 'Hal yang perlu dikoordinasikan untuk kebutuhan panggung, backdrop, partisi, dan branding acara.',
                'author' => '',
                'published_at' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Koordinasi dan Fasilitas Pendukung Event',
                'slug' => 'koordinasi-dan-fasilitas-event',
                'thumbnail' => null,
                'content' => "Susun kebutuhan operasional acara dengan mencatat lokasi, jumlah area yang digunakan, jadwal, akses bongkar-muat, dan kebutuhan koordinasi tim. Informasi ini membantu penyedia memeriksa perlengkapan yang tersedia.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan toilet portable, standing AC, dan handy talkie. Kebutuhan, kecocokan lokasi, serta ketersediaan setiap perlengkapan perlu dikonfirmasi untuk tanggal acara.\n\nSertakan tanggal, alamat venue, aturan akses, dan daftar kebutuhan saat meminta informasi.",
                'meta_description' => 'Daftar informasi lokasi dan operasional yang perlu disiapkan saat merencanakan fasilitas event.',
                'author' => '',
                'published_at' => null,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('blog_posts')->insertOrIgnore($articles);
    }

    public function down(): void
    {
    }
};
