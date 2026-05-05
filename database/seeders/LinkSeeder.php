<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['name' => 'Home',             'route' => '/'],
            ['name' => 'About',            'route' => '/about'],
            ['name' => 'Academics',        'route' => '/academics'],
            ['name' => 'Admissions',       'route' => '/admissions'],
            ['name' => 'Events',           'route' => '/events'],
            ['name' => 'Student Life',     'route' => '/student-life'],
            ['name' => 'Gallery',          'route' => '/gallery'],
            ['name' => 'Downloads',        'route' => '/downloads'],
            ['name' => 'Digital Services', 'route' => '/digital-services'],
            ['name' => 'Search',           'route' => '/search'],
            ['name' => 'Contact',          'route' => '/#contact'],
        ];

        foreach ($links as $link) {
            Link::firstOrCreate(['route' => $link['route']], $link);
        }
    }
}
