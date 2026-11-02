<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_seeder_uses_railway_admin_env_values(): void
    {
        putenv('ADMIN_EMAIL=admin@kaizen.com');
        putenv('ADMIN_DEFAULT_PASSWORD=kaizen@jaya1234');
        putenv('ADMIN_PASSWORD=');

        $this->artisan('db:seed', ['--class' => AdminUserSeeder::class])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@kaizen.com',
            'role' => 'admin',
        ]);

        $user = User::query()->where('email', 'admin@kaizen.com')->firstOrFail();

        $this->assertTrue(Hash::check('kaizen@jaya1234', $user->password));
    }

    public function test_admin_seeder_does_not_reset_an_existing_users_password(): void
    {
        putenv('ADMIN_EMAIL=admin@kaizen.com');
        putenv('ADMIN_DEFAULT_PASSWORD=new-password');
        putenv('ADMIN_PASSWORD=');

        $user = User::factory()->create([
            'email' => 'admin@kaizen.com',
            'password' => Hash::make('original-password'),
        ]);

        $this->artisan('db:seed', ['--class' => AdminUserSeeder::class])
            ->assertSuccessful();

        $user->refresh();

        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('original-password', $user->password));
        $this->assertFalse(Hash::check('new-password', $user->password));
    }
}
