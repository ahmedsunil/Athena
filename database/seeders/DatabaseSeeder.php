<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            EventSeeder::class,
            HomePageSeeder::class,
            AboutSeeder::class,
            AcademicsSeeder::class,
            StudentLifeSeeder::class,
            StaffSeeder::class,
            AnnouncementSeeder::class,
            GallerySeeder::class,
            DigitalServicesSeeder::class,
        ]);

        $this->call(AdminUserSeeder::class);
    }
}
