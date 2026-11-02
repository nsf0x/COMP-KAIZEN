<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            'PT Maju Bersama', 'Hotel Grand Nusantara', 'Yayasan Pendidikan Harapan',
            'PT Karya Indah', 'Bank Nusantara', 'RS Sehat Sejahtera',
            'Universitas Maju Indonesia', 'PT Fashion House',
        ];

        foreach ($clients as $i => $name) {
            Client::updateOrCreate(
                ['name' => $name],
                [
                    'is_active' => true,
                    'order'     => $i + 1,
                ],
            );
        }
    }
}
