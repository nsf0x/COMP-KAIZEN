<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'perlengkapan-event-pernikahan-checklist-tenda-peralatan',
                'title' => 'Perlengkapan Event Pernikahan: Checklist Tenda dan Peralatan',
                'meta_description' => 'Checklist perlengkapan event pernikahan, dari tenda dan flooring hingga kursi serta sound system.',
                'content' => "Menyusun kebutuhan perlengkapan pernikahan dimulai dari konsep acara, lokasi, jumlah tamu, dan fasilitas yang tersedia di venue. Daftar berikut membantu Anda menyiapkan hal-hal yang perlu dibicarakan dengan penyedia perlengkapan.\n\n1. Tenda dan area acara\nUntuk acara di luar ruangan, tentukan area yang perlu dinaungi serta periksa ukuran dan akses lokasi bersama pengelola venue.\n\n2. Flooring dan karpet\nPertimbangkan kondisi permukaan area dan kebutuhan alur tamu. Pastikan jenis serta ukuran flooring sesuai dengan lokasi acara.\n\n3. Kursi dan meja\nPerkirakan kebutuhan berdasarkan susunan acara dan jumlah tamu. Konfirmasi model, jumlah, dan penempatan sebelum meminta penawaran.\n\n4. Sound system dan pencahayaan\nSampaikan susunan acara, kebutuhan mikrofon, serta kondisi ruang kepada penyedia agar kebutuhan peralatan dapat diperiksa.\n\n5. Jadwal dan lokasi\nCantumkan tanggal acara, alamat, waktu akses venue, dan informasi penting tentang proses bongkar-muat.\n\nKaizen Kreasi Indonesia menyediakan berbagai perlengkapan event. Hubungi kami melalui WhatsApp untuk menyampaikan kebutuhan dan meminta konfirmasi ketersediaan serta penawaran.",
                'is_published' => true,
            ],
            [
                'slug' => 'faktor-biaya-sewa-tenda-pernikahan',
                'title' => 'Faktor yang Menentukan Penawaran Sewa Tenda Pernikahan',
                'meta_description' => 'Kenali informasi yang diperlukan untuk meminta penawaran sewa tenda pernikahan tanpa perkiraan harga yang tidak terverifikasi.',
                'content' => "Penawaran sewa tenda perlu disesuaikan dengan kebutuhan dan kondisi lokasi acara. Karena setiap permintaan dapat berbeda, rincian harga sebaiknya dikonfirmasi langsung berdasarkan informasi acara.\n\nInformasi yang membantu saat meminta penawaran:\n\n1. Jenis dan ukuran area yang akan digunakan.\n2. Tanggal serta durasi penggunaan.\n3. Alamat lokasi dan akses menuju area pemasangan.\n4. Kondisi permukaan tanah dan aturan dari pengelola venue.\n5. Kebutuhan perlengkapan pendukung, seperti flooring, kursi, atau meja.\n\nSertakan foto lokasi atau denah jika tersedia. Tim dapat memeriksa informasi tersebut dan mengonfirmasi ketersediaan serta rincian penawaran melalui WhatsApp.\n\nKaizen Kreasi Indonesia berbasis di Bekasi, melayani wilayah Jabodetabek, serta menerima permintaan pengiriman luar kota untuk event skala besar.",
                'is_published' => true,
            ],
            [
                'slug' => 'perlengkapan-event-ulang-tahun',
                'title' => 'Perlengkapan Event untuk Acara Ulang Tahun',
                'meta_description' => 'Panduan menyiapkan tenda, kursi, meja, dan sound system untuk kebutuhan acara ulang tahun.',
                'content' => "Perencanaan perlengkapan acara ulang tahun dapat dimulai dengan menentukan lokasi, jumlah tamu, dan rangkaian acara. Setelah itu, susun daftar kebutuhan yang sesuai dengan kondisi venue.\n\nTenda dapat dipertimbangkan untuk acara luar ruangan. Kursi dan meja membantu menyesuaikan area dengan susunan acara dan kebutuhan tamu. Jika acara memiliki sambutan atau hiburan, sampaikan kebutuhan audio kepada penyedia perlengkapan.\n\nSebelum meminta penawaran, siapkan tanggal, alamat, perkiraan jumlah tamu, waktu akses lokasi, dan daftar perlengkapan. Konfirmasi juga aturan venue serta ruang untuk pengiriman.\n\nKaizen Kreasi Indonesia menyediakan perlengkapan event seperti tenda, furniture, dan sound system. Hubungi kami melalui WhatsApp untuk menanyakan ketersediaan dan meminta penawaran sesuai kebutuhan acara.",
                'is_published' => true,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    ...$post,
                    'author' => '',
                    'published_at' => null,
                ],
            );
        }
    }
}
