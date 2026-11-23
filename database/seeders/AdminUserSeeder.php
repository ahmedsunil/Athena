<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Create or update the admin account from ADMIN_EMAIL / ADMIN_PASSWORD in .env.
     *
     * Run on its own with: php artisan db:seed --class=AdminUserSeeder --force
     */
    public function run(): void
    {
        $email = config('app.admin.email');
        $password = config('app.admin.password');

        if (blank($email) || blank($password)) {
            throw new RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD in .env before seeding the admin user.');
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Developer',
                'password' => Hash::make($password),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);
    }
}
