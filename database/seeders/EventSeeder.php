<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $eventsPath = base_path('api_jsons/events.json');
        $homePath = base_path('api_jsons/home.json');

        $eventsJson = File::exists($eventsPath)
            ? json_decode(File::get($eventsPath), true, flags: JSON_THROW_ON_ERROR)
            : ['events' => $this->fallbackEvents()];

        $homeJson = File::exists($homePath)
            ? json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR)
            : [];

        $featuredHrefs = collect($homeJson['featuredEvents'] ?? [])->pluck('href')->values();
        $defaultFeaturedOrder = [
            'evt-002' => 0,
            'evt-003' => 1,
            'evt-005' => 2,
        ];

        foreach ($eventsJson['events'] ?? [] as $event) {
            $generatedHref = '/events/'.str($event['title'])->slug();
            $featuredIndex = $featuredHrefs->search($generatedHref);
            $featuredOrder = $featuredIndex === false
                ? ($defaultFeaturedOrder[$event['id']] ?? false)
                : $featuredIndex;
            $featuredHref = $featuredOrder === false ? null : $featuredHrefs->get($featuredOrder);

            Event::updateOrCreate(
                ['public_id' => $event['id']],
                [
                    'status' => $event['status'],
                    'title' => $event['title'],
                    'slug' => $this->slugFromHref($featuredHref) ?: str($event['title'])->slug()->toString(),
                    'date_start' => $event['dateStart'],
                    'date_end' => $event['dateEnd'],
                    'location' => $event['location'],
                    'cover_image_path' => $event['coverImageUrl'] ?? null,
                    'short_description' => $event['shortDescription'],
                    'full_description' => $event['fullDescription'],
                    'attachments' => $event['attachments'] ?? [],
                    'contact' => $event['contact'] ?? null,
                    'is_featured' => $featuredOrder !== false,
                    'featured_sort_order' => $featuredOrder === false ? 0 : $featuredOrder,
                    'is_active' => true,
                ]
            );
        }
    }

    private function slugFromHref(?string $href): ?string
    {
        if (! $href) {
            return null;
        }

        $path = trim(parse_url($href, PHP_URL_PATH) ?: '', '/');
        $slug = str($path)->afterLast('/')->slug()->toString();

        return $slug === '' ? null : $slug;
    }

    private function fallbackEvents(): array
    {
        return [
            [
                'id' => 'evt-001',
                'status' => 'ongoing',
                'title' => 'Inter-House Sports Week',
                'dateStart' => '2026-05-11',
                'dateEnd' => '2026-05-15',
                'location' => 'School Ground',
                'coverImageUrl' => 'https://images.unsplash.com/photo-1526676037777-05a232554f77?w=1200&q=80',
                'shortDescription' => 'A week of athletics, team games, and house competitions for students across all grades.',
                'fullDescription' => "Students will compete across track, field, and team events throughout the week. The programme is designed to encourage teamwork, discipline, and healthy competition.\n\nParents and guardians are welcome to attend the final day events and prize presentation.",
                'attachments' => [
                    ['label' => 'Sports Week Schedule', 'url' => '/downloads/sports-week-schedule.pdf'],
                ],
                'contact' => 'sports@hulhudhuffaaru.edu.mv',
            ],
            [
                'id' => 'evt-002',
                'status' => 'upcoming',
                'title' => 'Science and Innovation Fair',
                'dateStart' => '2026-06-04',
                'dateEnd' => '2026-06-04',
                'location' => 'Main Hall',
                'coverImageUrl' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=1200&q=80',
                'shortDescription' => 'Student teams present experiments, prototypes, and research projects to the school community.',
                'fullDescription' => "The annual Science and Innovation Fair gives students a platform to demonstrate inquiry, creativity, and technical skill.\n\nProjects will be reviewed by a panel of teachers and guest judges, with awards presented for research quality, presentation, and practical impact.",
                'attachments' => [
                    ['label' => 'Project Guidelines', 'url' => '/downloads/science-fair-guidelines.pdf'],
                ],
                'contact' => 'science@hulhudhuffaaru.edu.mv',
            ],
            [
                'id' => 'evt-003',
                'status' => 'upcoming',
                'title' => 'Parents and Teachers Conference',
                'dateStart' => '2026-06-18',
                'dateEnd' => '2026-06-18',
                'location' => 'Classrooms and Administration Block',
                'coverImageUrl' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1200&q=80',
                'shortDescription' => 'A structured meeting day for families and teachers to review student progress and support plans.',
                'fullDescription' => "Parents and guardians are invited to meet class teachers and subject teachers for a detailed review of student progress.\n\nAppointment slots will be shared in advance. Families are encouraged to bring any questions about learning goals, attendance, and student wellbeing.",
                'attachments' => [
                    ['label' => 'Conference Appointment Guide', 'url' => '/downloads/ptc-appointment-guide.pdf'],
                ],
                'contact' => 'office@hulhudhuffaaru.edu.mv',
            ],
            [
                'id' => 'evt-004',
                'status' => 'upcoming',
                'title' => 'Quran Recitation Competition',
                'dateStart' => '2026-07-02',
                'dateEnd' => '2026-07-02',
                'location' => 'School Mosque Hall',
                'coverImageUrl' => 'https://images.unsplash.com/photo-1564769662533-4f00a87b4056?w=1200&q=80',
                'shortDescription' => 'Students participate in recitation categories grouped by grade level.',
                'fullDescription' => "The competition recognises students for clear recitation, memorisation, pronunciation, and confidence.\n\nSelected participants from each grade will compete before a judging panel, and certificates will be awarded during assembly.",
                'attachments' => [],
                'contact' => 'islam@hulhudhuffaaru.edu.mv',
            ],
            [
                'id' => 'evt-005',
                'status' => 'upcoming',
                'title' => 'Career Guidance Day',
                'dateStart' => '2026-07-16',
                'dateEnd' => '2026-07-16',
                'location' => 'Library and ICT Lab',
                'coverImageUrl' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=1200&q=80',
                'shortDescription' => 'Senior students meet professionals and alumni to explore study pathways and future careers.',
                'fullDescription' => "Career Guidance Day connects senior students with alumni, local professionals, and higher education representatives.\n\nSessions will cover subject choices, scholarship preparation, workplace expectations, and planning for life after school.",
                'attachments' => [
                    ['label' => 'Career Day Programme', 'url' => '/downloads/career-day-programme.pdf'],
                ],
                'contact' => 'counsellor@hulhudhuffaaru.edu.mv',
            ],
            [
                'id' => 'evt-006',
                'status' => 'completed',
                'title' => 'Environment Club Island Clean-Up',
                'dateStart' => '2026-04-25',
                'dateEnd' => '2026-04-25',
                'location' => 'Hulhudhuffaaru Beachfront',
                'coverImageUrl' => 'https://images.unsplash.com/photo-1618477462146-050d2767eac4?w=1200&q=80',
                'shortDescription' => 'Students and staff worked with community partners to clean and document key shoreline areas.',
                'fullDescription' => "The Environment Club led a community clean-up focused on responsible waste collection and awareness.\n\nStudents recorded the types of waste collected and discussed practical ways to reduce single-use plastics on campus and at home.",
                'attachments' => [],
                'contact' => 'environment@hulhudhuffaaru.edu.mv',
            ],
        ];
    }
}
