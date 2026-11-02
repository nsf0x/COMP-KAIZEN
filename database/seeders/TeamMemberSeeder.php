<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Budi Santoso', 'position' => 'Founder & CEO', 'bio' => 'Berpengalaman 15 tahun di industri event organizer dan party rental.', 'order' => 1],
            ['name' => 'Sari Dewi', 'position' => 'Creative Director', 'bio' => 'Spesialis desain dekorasi pernikahan dan event premium.', 'order' => 2],
            ['name' => 'Andi Pratama', 'position' => 'Operations Manager', 'bio' => 'Memastikan setiap event berjalan tepat waktu dan sempurna.', 'order' => 3],
            ['name' => 'Rini Lestari', 'position' => 'Customer Relations', 'bio' => 'Siap membantu Anda 24/7 melalui WhatsApp dan telepon.', 'order' => 4],
        ];

        foreach ($members as $m) {
            TeamMember::updateOrCreate(
                ['name' => $m['name']],
                [
                    'position'  => $m['position'],
                    'description' => $m['bio'],
                    'is_active' => true,
                    'order'     => $m['order'],
                ],
            );
        }
    }
}
