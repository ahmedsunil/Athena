<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'icon_key' => 'Briefcase',
                'category' => 'Job Opening',
                'title' => 'Teacher Recruitment for Key Stage Classes',
                'slug' => 'teacher-recruitment-for-key-stage-classes',
                'description' => 'Applications are open for qualified teachers to join Hulhudhuffaaru School for the upcoming academic term.',
                'deadline' => '2026-06-15',
                'attachments' => [
                    [
                        'label' => 'Job information and application form',
                        'url' => 'https://drive.google.com/file/d/teacher-recruitment-sample/view',
                    ],
                ],
                'sort_order' => 1,
            ],
            [
                'icon_key' => 'File',
                'category' => 'Bid',
                'title' => 'Invitation for Bids: Classroom Furniture Supply',
                'slug' => 'invitation-for-bids-classroom-furniture-supply',
                'description' => 'Registered vendors are invited to submit quotations for classroom desks, chairs, and storage cabinets.',
                'deadline' => '2026-05-30',
                'attachments' => [
                    [
                        'label' => 'Bid document',
                        'url' => 'https://drive.google.com/file/d/classroom-furniture-bid-sample/view',
                    ],
                    [
                        'label' => 'Vendor quotation template',
                        'url' => 'https://docs.google.com/spreadsheets/d/vendor-quotation-template-sample/edit',
                    ],
                ],
                'sort_order' => 2,
            ],
            [
                'icon_key' => 'ClipboardList',
                'category' => 'Competition',
                'title' => 'Inter-School Coding Challenge Registration',
                'slug' => 'inter-school-coding-challenge-registration',
                'description' => 'Students may register through their class teachers for the upcoming inter-school coding challenge.',
                'deadline' => null,
                'attachments' => [
                    [
                        'label' => 'Competition guidelines',
                        'url' => 'https://drive.google.com/file/d/coding-challenge-guidelines-sample/view',
                    ],
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                ['title' => $announcement['title']],
                array_merge($announcement, [
                    'slug' => $announcement['slug'] ?? Str::slug($announcement['title']),
                    'attachments' => $announcement['attachments'] ?? null,
                    'is_active' => true,
                ])
            );
        }
    }
}
