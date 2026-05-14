<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'sort_order'  => 1,
                'title'       => 'Graduation Ceremony 2024',
                'category'    => 'Graduation',
                'date'        => '2024-11-15',
                'photo_count' => 148,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 2,
                'title'       => 'Inter-House Sports Competition 2024',
                'category'    => 'Sports',
                'date'        => '2024-10-04',
                'photo_count' => 212,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 3,
                'title'       => 'Cultural Day & Heritage Festival 2024',
                'category'    => 'Cultural',
                'date'        => '2024-09-20',
                'photo_count' => 93,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 4,
                'title'       => 'Annual Science Fair 2024',
                'category'    => 'Academic',
                'date'        => '2024-09-06',
                'photo_count' => 67,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 5,
                'title'       => "Founder's Day Celebration",
                'category'    => 'Events',
                'date'        => '2024-07-14',
                'photo_count' => 104,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 6,
                'title'       => 'FCT Educational Trip — Abuja',
                'category'    => 'Trips',
                'date'        => '2024-06-21',
                'photo_count' => 178,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 7,
                'title'       => 'Prize Giving & Speech Day 2024',
                'category'    => 'Events',
                'date'        => '2024-06-07',
                'photo_count' => 89,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 8,
                'title'       => 'State Schools Football Tournament',
                'category'    => 'Sports',
                'date'        => '2024-04-18',
                'photo_count' => 134,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 9,
                'title'       => 'National Debate Championship — Lagos',
                'category'    => 'Academic',
                'date'        => '2024-03-29',
                'photo_count' => 41,
                'facebook_url'=> null,
                'is_active'   => true,
            ],
            [
                'sort_order'  => 10,
                'title'       => 'End-of-Term Drama Production',
                'category'    => 'Cultural',
                'date'        => '2024-03-15',
                'photo_count' => 76,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 11,
                'title'       => 'Graduation Ceremony 2023',
                'category'    => 'Graduation',
                'date'        => '2023-11-17',
                'photo_count' => 162,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
            [
                'sort_order'  => 12,
                'title'       => 'Annual Sports Day 2023',
                'category'    => 'Sports',
                'date'        => '2023-10-13',
                'photo_count' => 198,
                'facebook_url'=> 'https://facebook.com',
                'is_active'   => true,
            ],
        ];

        foreach ($albums as $album) {
            GalleryAlbum::updateOrCreate(['title' => $album['title']], $album);
        }
    }
}
