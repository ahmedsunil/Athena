<?php

namespace Database\Seeders;

use App\Models\HomeTestimonial;
use Illuminate\Database\Seeder;

class HomeTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Aishath Sameera',
                'previous_designation' => 'Parent',
                'current_designation' => 'Parent of Grade 9 Student',
                'message' => 'The teachers have helped my daughter become more confident in class and more responsible at home. We see steady progress every term.',
            ],
            [
                'name' => 'Ahmed Rasheed',
                'previous_designation' => 'Student, Class of 2018',
                'current_designation' => 'Civil Engineering Student',
                'message' => 'The discipline, project work, and support from my teachers prepared me well for college. I still use the study habits I built here.',
            ],
            [
                'name' => 'Fathimath Naila',
                'previous_designation' => 'Student, Class of 2020',
                'current_designation' => 'Medical Student',
                'message' => 'Science lessons were practical and engaging, and the school encouraged me to aim higher. That encouragement made a real difference.',
            ],
            [
                'name' => 'Hassan Latheef',
                'previous_designation' => 'Parent',
                'current_designation' => 'Parent of Grade 5 Student',
                'message' => 'Communication from the school is clear and timely. As a parent, I appreciate knowing what is happening and how my child is doing.',
            ],
            [
                'name' => 'Mariyam Shifana',
                'previous_designation' => 'Student, Class of 2021',
                'current_designation' => 'Business Management Student',
                'message' => 'Clubs, assemblies, and group activities helped me become comfortable speaking in front of people. The school gave me room to grow.',
            ],
            [
                'name' => 'Ibrahim Waheed',
                'previous_designation' => 'Parent',
                'current_designation' => 'Parent of Grade 7 Student',
                'message' => 'My son enjoys coming to school because his teachers know him well. The balance of academics, sports, and values is strong.',
            ],
            [
                'name' => 'Aminath Liyana',
                'previous_designation' => 'Student, Class of 2019',
                'current_designation' => 'Primary Teacher Trainee',
                'message' => 'I chose education because of the teachers who guided me here. Their patience and care showed me the kind of teacher I want to become.',
            ],
            [
                'name' => 'Mohamed Arif',
                'previous_designation' => 'Parent',
                'current_designation' => 'Parent of Grade 10 Student',
                'message' => 'Exam preparation is organised and consistent. The teachers give helpful feedback, and students are pushed without losing confidence.',
            ],
            [
                'name' => 'Zahra Mohamed',
                'previous_designation' => 'Student, Class of 2022',
                'current_designation' => 'Foundation Studies Student',
                'message' => 'The school helped me discover my strengths beyond marks. I learned leadership, teamwork, and how to take responsibility.',
            ],
            [
                'name' => 'Rilwan Adam',
                'previous_designation' => 'Parent',
                'current_designation' => 'Parent of Grade 3 Student',
                'message' => 'The early grade teachers are patient and attentive. My child has become more independent, curious, and excited about learning.',
            ],
        ];

        $names = collect($testimonials)->pluck('name');
        $existingMaxSortOrder = HomeTestimonial::whereNotIn('name', $names)->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($testimonials as $index => $testimonial) {
            HomeTestimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                [
                    ...$testimonial,
                    'photo_path' => null,
                    'is_active' => true,
                    'sort_order' => $baseSortOrder + $index,
                ]
            );
        }
    }
}
