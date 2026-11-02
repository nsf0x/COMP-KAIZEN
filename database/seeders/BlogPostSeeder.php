<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title'        => '10 Tips Memilih Dekorasi Pernikahan yang Berkesan',
                'author'       => 'Sari Dewi',
                'published_at' => now()->subDays(5),
                'content'      => "Pernikahan adalah momen sekali seumur hidup yang ingin dikenang selamanya. Salah satu hal yang paling berpengaruh terhadap keindahan pernikahan adalah dekorasi.\n\nBerikut 10 tips dari kami untuk memilih dekorasi pernikahan yang berkesan:\n\n1. Tentukan tema dahulu\nSebelum memilih dekorasi apapun, tentukan dulu tema pernikahan Anda. Apakah garden party, rustic, modern minimalis, atau tradisional?\n\n2. Sesuaikan dengan venue\nDekorasi harus selaras dengan venue. Tenda outdoor membutuhkan pendekatan yang berbeda dengan ballroom indoor.\n\n3. Pilih palet warna yang harmonis\nGunakan maksimal 3 warna utama untuk menjaga keselarasan visual.\n\n4. Prioritaskan area focal point\nInvestasikan lebih banyak di area pelaminan, pintu masuk, dan meja utama karena inilah yang paling banyak difoto.\n\n5. Pertimbangkan pencahayaan\nLighting yang tepat bisa mengubah suasana drastis. Fairy lights, lampu gantung, atau LED warna hangat sangat direkomendasikan.\n\n6. Gunakan bunga segar dengan bijak\nBunga segar memberikan kesan mewah namun biaya bisa tinggi. Kombinasikan dengan bunga artifisial berkualitas untuk menghemat budget.\n\n7. Jangan lupakan detail kecil\nRunner meja, tempat lilin, dan nomor meja yang cantik bisa meningkatkan estetika keseluruhan.\n\n8. Konsultasikan dengan vendor\nVendor dekorasi berpengalaman bisa memberikan insight berharga berdasarkan pengalaman mereka.\n\n9. Book jauh-jauh hari\nVendor dekorasi populer biasanya sudah fully booked 6-12 bulan sebelumnya, terutama untuk peak season.\n\n10. Tetapkan budget yang realistis\nAlokasikan 15-25% dari total budget pernikahan untuk dekorasi.",
                'meta_description' => 'Tips memilih dekorasi pernikahan yang berkesan dan sesuai budget dari para ahli party rental.',
                'is_published' => true,
            ],
            [
                'title'        => 'Berapa Biaya Sewa Tenda Pernikahan? Ini Panduannya!',
                'author'       => 'Budi Santoso',
                'published_at' => now()->subDays(12),
                'content'      => "Salah satu pertanyaan paling sering kami terima adalah: berapa biaya sewa tenda untuk pernikahan?\n\nJawabannya bergantung pada banyak faktor. Mari kami jelaskan secara detail.\n\nFaktor yang Mempengaruhi Harga Sewa Tenda:\n\n1. Ukuran tenda\nSemakin besar tenda, tentu semakin mahal. Harga berkisar dari Rp 1.500.000 untuk ukuran 6x12m hingga Rp 15.000.000 untuk tenda 20x40m.\n\n2. Jenis tenda\n- Tenda roder: Paling populer, kokoh, harga menengah\n- Tenda sarnafil: Premium, waterproof, lebih mahal\n- Tenda dome: Unik, cocok untuk outdoor festival\n\n3. Lama sewa\nKebanyakan vendor menghitung per hari. Namun untuk pernikahan, biasanya minimal 2-3 hari (termasuk setup dan pembongkaran).\n\n4. Lokasi\nJarak pengiriman berpengaruh pada ongkos kirim.\n\n5. Aksesori tambahan\nLantai portable, dinding transparan, dan AC tent bisa menambah biaya.\n\nEstimasi Biaya:\n- Tenda 10x20m: Rp 2.500.000 - 4.000.000/hari\n- Tenda 15x30m: Rp 4.500.000 - 7.000.000/hari\n- Tenda 20x40m: Rp 8.000.000 - 15.000.000/hari\n\nTips Hemat:\n- Book di luar peak season (Januari-Februari, Agustus-September)\n- Paket bundling tenda + kursi + meja biasanya lebih hemat\n- Bandingkan minimal 3 vendor sebelum memutuskan",
                'meta_description' => 'Panduan lengkap biaya sewa tenda pernikahan 2024. Mulai dari Rp 1,5 juta hingga Rp 15 juta tergantung ukuran.',
                'is_published' => true,
            ],
            [
                'title'        => 'Ide Dekorasi Ulang Tahun Anak yang Kreatif dan Murah',
                'author'       => 'Rini Lestari',
                'published_at' => now()->subDays(20),
                'content'      => "Merayakan ulang tahun anak tidak harus mahal! Dengan kreativitas dan pilihan vendor yang tepat, Anda bisa menciptakan pesta yang meriah dengan budget terbatas.\n\nIde Tema Populer 2024:\n\n🦁 Safari & Jungle\nDekorasi dengan balon hijau, cokelat, dan kuning. Tambahkan topper balon berbentuk hewan. Sangat instagramable!\n\n🌈 Rainbow & Unicorn\nFavorit anak perempuan. Balon pelangi, backdrop unicorn, dan cake bertema unicorn menjadi paket lengkap.\n\n🚀 Space & Astronaut\nCocok untuk anak laki-laki. Dekorasi galaksi dengan balon hitam, biru tua, dan silver.\n\n🍭 Candy Land\nWarna-warni cerah dengan permen raksasa dari styrofoam dan lollipop dekoratif.\n\nTips Budget-Friendly:\n\n1. DIY backdrop sederhana\nBuat balloon arch sendiri dengan kit yang dijual online. Hemat hingga 60% dibanding jasa profesional.\n\n2. Fokus pada 1 spot foto utama\nDaripada mendekorasi seluruh ruangan, fokuskan budget pada 1 backdrop foto yang impresif.\n\n3. Manfaatkan tableware disposable bertema\nPiring, gelas, dan sendok bertema karakter favorit anak mudah ditemukan di marketplace.\n\n4. Sewa, jangan beli\nBanyak aksesori pesta yang bisa disewa dengan harga jauh lebih murah dari beli baru.\n\nEstimasi Budget:\n- Dekorasi balon + backdrop: Rp 500.000 - 1.500.000\n- Perlengkapan meja: Rp 200.000 - 500.000\n- Sewa meja + kursi: Rp 300.000 - 800.000\nTotal: Rp 1.000.000 - 2.800.000 untuk pesta 30-50 orang",
                'meta_description' => 'Ide dekorasi ulang tahun anak yang kreatif dan murah. Tips hemat budget dengan hasil maksimal.',
                'is_published' => true,
            ],
        ];

        foreach ($posts as $p) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($p['title'])],
                [
                    'title'            => $p['title'],
                    'content'          => $p['content'],
                    'meta_description' => $p['meta_description'],
                    'author'           => $p['author'],
                    'published_at'     => $p['published_at'],
                    'is_published'     => $p['is_published'],
                ],
            );
        }
    }
}
