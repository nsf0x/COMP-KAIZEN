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
            [
                'slug' => 'persiapan-display-visual-event',
                'title' => 'Persiapan Display Visual untuk Event',
                'meta_description' => 'Informasi yang perlu disiapkan saat memilih LED screen, processor, atau Smart TV untuk kebutuhan visual event.',
                'content' => "Perencanaan tampilan visual dimulai dengan menjelaskan tujuan penggunaan, lokasi, ukuran area, serta konten yang akan ditampilkan. Informasi tersebut membantu tim memeriksa perangkat yang sesuai dan ketersediaannya.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan LED screen P2.6 dan P3.9 dengan pilihan indoor dan outdoor, processor Novastar VX16S, VX600, dan VX400, serta Smart TV ukuran 55, 65, dan 75 inch. Model dan kebutuhan teknis perlu dikonfirmasi untuk setiap acara.\n\nSebelum berkonsultasi, siapkan tanggal, lokasi penggunaan, ukuran tampilan yang dibutuhkan, jenis konten, dan perangkat yang sudah tersedia di venue.",
                'is_published' => true,
            ],
            [
                'slug' => 'perencanaan-kebutuhan-daya-event',
                'title' => 'Informasi untuk Merencanakan Kebutuhan Daya Event',
                'meta_description' => 'Daftar informasi acara yang membantu saat berkonsultasi mengenai pilihan genset dan kebutuhan daya.',
                'content' => "Kebutuhan daya perlu dibahas berdasarkan peralatan yang digunakan, lokasi, dan susunan acara. Siapkan daftar perangkat serta informasi kelistrikan venue yang tersedia sebelum meminta rekomendasi.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan genset 150 KVA, 100 KVA, 80 KVA, 60 KVA, dan 5000 watt. Kapasitas, kecocokan untuk kebutuhan acara, dan ketersediaan perlu dikonfirmasi secara langsung.\n\nSampaikan tanggal, lokasi, daftar peralatan, serta informasi sumber daya venue agar tim dapat memeriksa kebutuhan dan ketersediaan.",
                'is_published' => true,
            ],
            [
                'slug' => 'persiapan-panggung-backdrop-branding',
                'title' => 'Persiapan Panggung, Backdrop, dan Branding Event',
                'meta_description' => 'Hal yang perlu dikoordinasikan untuk kebutuhan panggung, backdrop, partisi, dan branding acara.',
                'content' => "Sebelum merencanakan panggung, backdrop, partisi, atau branding, kumpulkan informasi tentang ukuran area, tata letak venue, jadwal acara, dan aturan pemasangan. Denah atau gambar lokasi dapat membantu menjelaskan kebutuhan.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan perlengkapan seperti stage, backdrop, Partisi R8, serta produksi gate dan branding. Bentuk, ukuran, materi, dan ketersediaan perlu dikonfirmasi sesuai kebutuhan acara.\n\nSampaikan tanggal, lokasi, ukuran area, konsep visual, dan informasi akses pemasangan saat berkonsultasi.",
                'is_published' => true,
            ],
            [
                'slug' => 'koordinasi-dan-fasilitas-event',
                'title' => 'Koordinasi dan Fasilitas Pendukung Event',
                'meta_description' => 'Daftar informasi lokasi dan operasional yang perlu disiapkan saat merencanakan fasilitas event.',
                'content' => "Susun kebutuhan operasional acara dengan mencatat lokasi, jumlah area yang digunakan, jadwal, akses bongkar-muat, dan kebutuhan koordinasi tim. Informasi ini membantu penyedia memeriksa perlengkapan yang tersedia.\n\nKatalog Kaizen Kreasi Indonesia mencantumkan toilet portable, standing AC, dan handy talkie. Kebutuhan, kecocokan lokasi, serta ketersediaan setiap perlengkapan perlu dikonfirmasi untuk tanggal acara.\n\nSertakan tanggal, alamat venue, aturan akses, dan daftar kebutuhan saat meminta informasi.",
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
