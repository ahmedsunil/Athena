<?php

namespace Database\Seeders;

use App\Models\StudentLifeClub;
use App\Models\StudentLifeHouse;
use App\Models\StudentLifePerson;
use App\Models\StudentLifePrefect;
use App\Models\StudentLifeUniformBody;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class StudentLifeSeeder extends Seeder
{
    public function run(): void
    {
        // Clubs
        $clubs = [
            [
                'sort_order'      => 1,
                'name'            => 'Science & Technology Club',
                'description'     => 'Explores real-world science through experiments, robotics projects, and visits to tech companies. Open to all students with a passion for STEM.',
                'meeting_schedule'=> 'Thursdays, 2:30 – 4:00 PM',
                'patron_name'     => 'Mr. Adeyemi Olawale',
                'patron_role'     => 'Physics Teacher',
                'president_name'  => 'Temi Okafor',
                'president_class' => 'SSS 2A',
                'is_active'       => true,
            ],
            [
                'sort_order'      => 2,
                'name'            => 'Drama & Creative Arts Society',
                'description'     => 'Produces two major stage productions per year and trains students in acting, stagecraft, and public speaking. Welcomes all year groups.',
                'meeting_schedule'=> 'Tuesdays, 3:00 – 4:30 PM',
                'patron_name'     => 'Mrs. Ngozi Abiodun',
                'patron_role'     => 'English Teacher',
                'president_name'  => 'Chidinma Eze',
                'president_class' => 'SSS 3B',
                'is_active'       => true,
            ],
            [
                'sort_order'      => 3,
                'name'            => 'Debate Club',
                'description'     => 'Sharpens critical thinking and public speaking through inter-school debate competitions. Members compete at state and national levels.',
                'meeting_schedule'=> 'Wednesdays, 2:00 – 3:30 PM',
                'patron_name'     => 'Mr. Emeka Chukwuemeka',
                'patron_role'     => 'Literature Teacher',
                'president_name'  => 'Adaeze Nwosu',
                'president_class' => 'SSS 2C',
                'is_active'       => true,
            ],
            [
                'sort_order'      => 4,
                'name'            => 'Mathematics Club',
                'description'     => 'Tackles challenging problems beyond the classroom syllabus and prepares students for mathematics olympiads at all levels.',
                'meeting_schedule'=> 'Fridays, 1:00 – 2:30 PM',
                'patron_name'     => 'Mrs. Ifeanyi Eze',
                'patron_role'     => 'Mathematics Teacher',
                'president_name'  => 'Seun Adeleke',
                'president_class' => 'SSS 1A',
                'is_active'       => true,
            ],
            [
                'sort_order'      => 5,
                'name'            => 'Green Earth Environmental Club',
                'description'     => 'Promotes sustainability on campus through recycling drives, tree planting, and environmental awareness campaigns.',
                'meeting_schedule'=> 'Mondays, 3:00 – 4:00 PM',
                'patron_name'     => 'Mr. Usman Ibrahim',
                'patron_role'     => 'Geography Teacher',
                'president_name'  => 'Blessing Okonkwo',
                'president_class' => 'JSS 3B',
                'is_active'       => true,
            ],
            [
                'sort_order'      => 6,
                'name'            => 'French Language & Culture Club',
                'description'     => 'Develops French language skills through conversation sessions, cultural events, and preparation for DELF certification.',
                'meeting_schedule'=> 'Thursdays, 12:30 – 1:30 PM',
                'patron_name'     => 'Mrs. Amaka Okonkwo',
                'patron_role'     => 'French Teacher',
                'president_name'  => 'Fatima Musa',
                'president_class' => 'SSS 2B',
                'is_active'       => true,
            ],
        ];

        $clubModels = [];
        foreach ($clubs as $club) {
            $clubModels[$club['name']] = StudentLifeClub::updateOrCreate(['name' => $club['name']], $club);
        }

        // Prefects
        $prefects = [
            [
                'sort_order' => 1,
                'name'       => 'Chukwuemeka Obi',
                'role'       => 'Head Boy',
                'class_name' => 'SSS 3A',
                'quote'      => 'Leadership is not about being in charge — it is about taking care of those in your charge.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 2,
                'name'       => 'Amaka Nwosu',
                'role'       => 'Head Girl',
                'class_name' => 'SSS 3B',
                'quote'      => 'We rise by lifting others. My goal is to leave this school better than I found it.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 3,
                'name'       => 'Tunde Adebayo',
                'role'       => 'Senior Prefect',
                'class_name' => 'SSS 3A',
                'quote'      => 'Discipline is the bridge between goals and accomplishment.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 4,
                'name'       => 'Aisha Ibrahim',
                'role'       => 'Sports Prefect',
                'class_name' => 'SSS 3C',
                'quote'      => 'Sport teaches you character, challenges you, and teaches you what it means to never give up.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 5,
                'name'       => 'David Oyelaran',
                'role'       => 'Library Prefect',
                'class_name' => 'SSS 3B',
                'quote'      => 'A reader lives a thousand lives. I am here to open those doors for every student.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 6,
                'name'       => 'Chisom Adeleke',
                'role'       => 'Social Prefect',
                'class_name' => 'SSS 3A',
                'quote'      => 'A strong community is built one act of kindness at a time.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 7,
                'name'       => 'Kelechi Eze',
                'role'       => 'Sanitation Prefect',
                'class_name' => 'SSS 2C',
                'quote'      => 'A clean environment is the first sign of a disciplined mind.',
                'is_active'  => true,
            ],
            [
                'sort_order' => 8,
                'name'       => 'Ngozi Okafor',
                'role'       => 'Cultural Prefect',
                'class_name' => 'SSS 2B',
                'quote'      => 'Our culture is our identity — I am proud to celebrate and share it every day.',
                'is_active'  => true,
            ],
        ];

        foreach ($prefects as $prefect) {
            StudentLifePrefect::updateOrCreate(['name' => $prefect['name'], 'role' => $prefect['role']], $prefect);
        }

        // Houses
        $houses = [
            [
                'sort_order'        => 1,
                'name'              => 'Eagle House',
                'colour'            => 'rose',
                'motto'             => 'Soar High, Aim Higher',
                'description'       => 'Eagle House embodies ambition and excellence, challenging its members to reach for the highest standards in academics and character.',
                'house_master_name' => 'Mr. Adeyemi Olawale',
                'house_master_role' => 'House Master',
                'captain_name'      => 'Emeka Okonkwo',
                'captain_class'     => 'SSS 3A',
                'is_active'         => true,
            ],
            [
                'sort_order'        => 2,
                'name'              => 'Lion House',
                'colour'            => 'sky',
                'motto'             => 'Strength and Courage',
                'description'       => 'Lion House is built on bravery, resilience, and team spirit — qualities that define champions on the field and in the classroom.',
                'house_master_name' => 'Mrs. Adaeze Nwachukwu',
                'house_master_role' => 'House Mistress',
                'captain_name'      => 'Fatima Ibrahim',
                'captain_class'     => 'SSS 3B',
                'is_active'         => true,
            ],
            [
                'sort_order'        => 3,
                'name'              => 'Dolphin House',
                'colour'            => 'emerald',
                'motto'             => 'Unity in Excellence',
                'description'       => 'Dolphin House celebrates collaboration and creativity, fostering a community where every member supports and uplifts each other.',
                'house_master_name' => 'Mr. Chidi Okafor',
                'house_master_role' => 'House Master',
                'captain_name'      => 'Blessing Adesanya',
                'captain_class'     => 'SSS 3C',
                'is_active'         => true,
            ],
            [
                'sort_order'        => 4,
                'name'              => 'Falcon House',
                'colour'            => 'amber',
                'motto'             => 'Swift and True',
                'description'       => 'Falcon House values precision, integrity, and determination. Its members are known for their sharp focus and unwavering commitment.',
                'house_master_name' => 'Mrs. Yetunde Adeleke',
                'house_master_role' => 'House Mistress',
                'captain_name'      => 'Kelechi Eze',
                'captain_class'     => 'SSS 3A',
                'is_active'         => true,
            ],
        ];

        $houseModels = [];
        foreach ($houses as $house) {
            $houseModels[$house['name']] = StudentLifeHouse::updateOrCreate(['name' => $house['name']], $house);
        }

        // Uniform Bodies
        $uniformBodies = [
            [
                'sort_order'       => 1,
                'name'             => 'Boy Scouts Troop',
                'group_type'       => 'Boy Scouts',
                'colour'           => 'emerald',
                'description'      => 'Develops boys into responsible, self-reliant young men through outdoor skills, community service, and character training aligned with the world scouting movement.',
                'meeting_schedule' => 'Saturdays, 8:00 – 10:00 AM',
                'patron_name'      => 'Mr. Kunle Balogun',
                'patron_role'      => 'Physical Education Teacher',
                'leader_name'      => 'Samuel Adeyemi',
                'leader_class'     => 'SSS 2A',
                'is_active'        => true,
            ],
            [
                'sort_order'       => 2,
                'name'             => 'Girls Brigade Company',
                'group_type'       => 'Girls Brigade',
                'colour'           => 'rose',
                'description'      => 'Builds young women of faith, discipline, and service through uniformed activities, skill development, and community outreach programmes.',
                'meeting_schedule' => 'Saturdays, 8:00 – 10:00 AM',
                'patron_name'      => 'Mrs. Ngozi Okonkwo',
                'patron_role'      => 'Home Economics Teacher',
                'leader_name'      => 'Grace Obiora',
                'leader_class'     => 'SSS 2B',
                'is_active'        => true,
            ],
            [
                'sort_order'       => 3,
                'name'             => 'Red Cross Society',
                'group_type'       => 'Red Cross',
                'colour'           => 'teal',
                'description'      => 'Trains students in first aid, emergency response, and humanitarian values. Members serve at school events and participate in community health outreaches.',
                'meeting_schedule' => 'Fridays, 2:00 – 3:30 PM',
                'patron_name'      => 'Mr. Tayo Adeleke',
                'patron_role'      => 'Biology Teacher',
                'leader_name'      => 'Adaora Nwosu',
                'leader_class'     => 'SSS 1C',
                'is_active'        => true,
            ],
        ];

        $uniformBodyModels = [];
        foreach ($uniformBodies as $body) {
            $uniformBodyModels[$body['name']] = StudentLifeUniformBody::updateOrCreate(['name' => $body['name']], $body);
        }

        $this->seedPeopleHistory($clubModels, $houseModels, $uniformBodyModels);
    }

    private function seedPeopleHistory(array $clubs, array $houses, array $uniformBodies): void
    {
        $currentYear = now()->year;
        $years = range($currentYear, $currentYear - 5);

        $teachers = [
            'Ahmed Rasheed',
            'Aishath Haleema',
            'Mohamed Latheef',
            'Fathimath Niyaza',
            'Hassan Zareer',
            'Mariya Ali',
        ];

        $students = [
            ['name' => 'Aishath Nuha', 'grade' => 'Grade 10A'],
            ['name' => 'Mohamed Nihan', 'grade' => 'Grade 10B'],
            ['name' => 'Fathimath Zaina', 'grade' => 'Grade 9A'],
            ['name' => 'Ahmed Zayan', 'grade' => 'Grade 9B'],
            ['name' => 'Mariyam Zaha', 'grade' => 'Grade 8A'],
            ['name' => 'Yoosuf Ayaan', 'grade' => 'Grade 8B'],
            ['name' => 'Aishath Meesha', 'grade' => 'Grade 10A'],
            ['name' => 'Ibrahim Rayan', 'grade' => 'Grade 10B'],
            ['name' => 'Fathimath Aira', 'grade' => 'Grade 9A'],
            ['name' => 'Mohamed Shayan', 'grade' => 'Grade 9B'],
            ['name' => 'Mariyam Hana', 'grade' => 'Grade 8A'],
            ['name' => 'Ahmed Mueen', 'grade' => 'Grade 8B'],
        ];

        foreach (array_values($clubs) as $clubIndex => $club) {
            foreach ($years as $yearIndex => $year) {
                $offset = ($clubIndex * 2) + $yearIndex;
                $this->seedPeopleForOwner($club, $year, [
                    [
                        'name' => $teachers[($clubIndex + $yearIndex) % count($teachers)],
                        'designation' => 'Teacher in Charge',
                        'is_teacher_in_charge' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => $students[$offset % count($students)]['name'],
                        'designation' => 'President',
                        'grade' => $students[$offset % count($students)]['grade'],
                        'sort_order' => 2,
                    ],
                    [
                        'name' => $students[($offset + 3) % count($students)]['name'],
                        'designation' => 'Vice President',
                        'grade' => $students[($offset + 3) % count($students)]['grade'],
                        'sort_order' => 3,
                    ],
                ], $year === $currentYear);
            }
        }

        foreach (array_values($houses) as $houseIndex => $house) {
            foreach ($years as $yearIndex => $year) {
                $offset = ($houseIndex * 3) + $yearIndex;
                $this->seedPeopleForOwner($house, $year, [
                    [
                        'name' => $teachers[($houseIndex + $yearIndex + 2) % count($teachers)],
                        'designation' => 'House Master',
                        'is_teacher_in_charge' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => $students[$offset % count($students)]['name'],
                        'designation' => 'House Captain',
                        'grade' => $students[$offset % count($students)]['grade'],
                        'sort_order' => 2,
                    ],
                    [
                        'name' => $students[($offset + 5) % count($students)]['name'],
                        'designation' => 'Vice Captain',
                        'grade' => $students[($offset + 5) % count($students)]['grade'],
                        'sort_order' => 3,
                    ],
                ], $year === $currentYear);
            }
        }

        foreach (array_values($uniformBodies) as $bodyIndex => $body) {
            foreach ($years as $yearIndex => $year) {
                $offset = ($bodyIndex * 4) + $yearIndex;
                $this->seedPeopleForOwner($body, $year, [
                    [
                        'name' => $teachers[($bodyIndex + $yearIndex + 4) % count($teachers)],
                        'designation' => 'Patron',
                        'is_teacher_in_charge' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => $students[$offset % count($students)]['name'],
                        'designation' => 'Leader',
                        'grade' => $students[$offset % count($students)]['grade'],
                        'sort_order' => 2,
                    ],
                    [
                        'name' => $students[($offset + 7) % count($students)]['name'],
                        'designation' => 'Deputy Leader',
                        'grade' => $students[($offset + 7) % count($students)]['grade'],
                        'sort_order' => 3,
                    ],
                ], $year === $currentYear);
            }
        }
    }

    private function seedPeopleForOwner(Model $owner, int $year, array $people, bool $isActiveYear): void
    {
        foreach ($people as $person) {
            StudentLifePerson::updateOrCreate(
                [
                    'personable_type' => $owner::class,
                    'personable_id' => $owner->getKey(),
                    'year' => $year,
                    'designation' => $person['designation'],
                    'sort_order' => $person['sort_order'],
                ],
                [
                    'name' => $person['name'],
                    'grade' => ($person['is_teacher_in_charge'] ?? false) ? null : ($person['grade'] ?? null),
                    'is_teacher_in_charge' => $person['is_teacher_in_charge'] ?? false,
                    'is_active' => $isActiveYear,
                ]
            );
        }
    }
}
