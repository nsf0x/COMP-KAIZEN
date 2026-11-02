<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Ibu Ratna', 'event_type' => 'Pernikahan', 'rating' => 5,
             'content' => 'Alhamdulillah, pernikahan anak kami berjalan lancar dan indah. Dekorasi yang disiapkan sangat cantik melebihi ekspektasi. Terima kasih tim yang sangat profesional dan ramah!'],
            ['name' => 'Pak Hendra', 'event_type' => 'Ulang Tahun', 'rating' => 5,
             'content' => 'Pesta ulang tahun putri kami jadi sangat meriah berkat dekorasi balon dan backdrop fotonya. Harga sangat terjangkau, pelayanan memuaskan. Pasti akan order lagi!'],
            ['name' => 'Ibu Susi', 'event_type' => 'Sunatan', 'rating' => 5,
             'content' => 'Tenda dan kursinya banyak, kualitas bagus, dan timnya datang tepat waktu. Sangat membantu untuk acara sunatan anak saya. Recommended!'],
            ['name' => 'Bapak Fajar', 'event_type' => 'Corporate Event', 'rating' => 5,
             'content' => 'Sound system dan lighting-nya top banget! Event launching produk kami jadi sangat berkesan. Tim sangat sigap dan profesional.'],
            ['name' => 'Mbak Dina', 'event_type' => 'Pernikahan', 'rating' => 5,
             'content' => 'Pelaminannya cantik banget, sesuai dengan referensi yang kami minta. Bunga segarnya harum dan indah. Tamu-tamu kami banyak yang memuji dekorasinya!'],
            ['name' => 'Pak Rizky', 'event_type' => 'Reuni', 'rating' => 4,
             'content' => 'Kapasitas tenda besar, kursi banyak dan bersih. Koordinasi dengan panitia juga lancar. Sangat cocok untuk event reuni akbar kami.'],
        ];

        foreach ($testimonials as $i => $t) {
            Testimonial::updateOrCreate(
                ['client_name' => $t['name'], 'company' => $t['event_type']],
                [
                    'message'   => $t['content'],
                    'rating'    => $t['rating'],
                    'is_active' => true,
                    'order'     => $i + 1,
                ],
            );
        }
    }
}
