<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SchoolProfileSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('password'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);
    }
}
