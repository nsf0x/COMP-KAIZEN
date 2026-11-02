<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@partyrentalpro.com');
        $password = env('ADMIN_DEFAULT_PASSWORD', env('ADMIN_PASSWORD'));

        if (!$password) {
            $this->command?->warn('Set ADMIN_EMAIL dan ADMIN_DEFAULT_PASSWORD (atau ADMIN_PASSWORD) sebelum membuat akun admin.');
            return;
        }

        $admin = User::firstOrNew(['email' => $email]);
        if (! $admin->exists) {
            $admin->name = 'Admin';
            $admin->password = Hash::make($password);
        }
        $admin->role = 'admin';
        $admin->save();
    }
}
