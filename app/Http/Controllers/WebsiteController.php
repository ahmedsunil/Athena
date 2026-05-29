<?php

namespace App\Http\Controllers;

use App\Models\AcademicLevel;
use App\Models\AcademicsOverview;
use App\Models\Achievement;
use App\Models\Announcement;
use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceCalendarEntry;
use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceResource;
use App\Models\Event;
use App\Models\FoundingMember;
use App\Models\GalleryAlbum;
use App\Models\HistorySection;
use App\Models\HomeQuickAccess;
use App\Models\HomeSlide;
use App\Models\HomeStat;
use App\Models\HomeTestimonial;
use App\Models\LeadershipMember;
use App\Models\Mission;
use App\Models\SchoolProfile;
use App\Models\StaffMember;
use App\Models\StudentLifeClub;
use App\Models\StudentLifeHouse;
use App\Models\StudentLifePrefect;
use App\Models\StudentLifeUniformBody;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class WebsiteController extends Controller
{
    public function home(): View
    {
        return $this->page('livewire.website.home', [
            'slides' => HomeSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'stats' => HomeStat::where('is_active', true)->orderBy('sort_order')->get(),
            'quickAccess' => HomeQuickAccess::where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => HomeTestimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'profile' => SchoolProfile::singleton(),
            'featuredEvents' => Event::where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('featured_sort_order')
                ->take(3)
                ->get(),
        ]);
    }

    public function announcements(Request $request): View
    {
        $filter = in_array($request->query('filter'), ['active', 'closed'], true)
            ? $request->query('filter')
            : 'active';
        $today = now()->toDateString();

        $announcements = Announcement::query()
            ->when(
                $filter === 'closed',
                fn (Builder $query) => $query->where(fn (Builder $query) => $query
                    ->where('is_active', false)
                    ->orWhereDate('deadline', '<', $today)),
                fn (Builder $query) => $query
                    ->where('is_active', true)
                    ->where(fn (Builder $query) => $query
                        ->whereNull('deadline')
                        ->orWhereDate('deadline', '>=', $today))
            )
            ->orderBy('sort_order')
            ->orderByRaw('deadline is null')
            ->orderBy('deadline')
            ->orderByDesc('created_at')
            ->paginate(5)
            ->withQueryString();

        return $this->page('livewire.website.announcements', compact('announcements', 'filter'));
    }

    public function announcementShow(string $slug): View
    {
        return $this->page('livewire.website.announcement-show', [
            'announcement' => Announcement::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail(),
        ]);
    }

    public function about(Request $request): View
    {
        $allAchievements = Achievement::where('is_active', true)
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $activeCategory = $request->query('category', 'all');
        $activeYear = $request->query('year', 'all');

        return $this->page('livewire.website.about', [
            'profile' => SchoolProfile::singleton(),
            'mission' => Mission::singleton(),
            'leadership' => LeadershipMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'foundingMembers' => FoundingMember::orderBy('sort_order')->orderBy('id')->get(),
            'historySections' => HistorySection::orderBy('sort_order')->orderBy('id')->get(),
            'achievements' => $allAchievements
                ->when($activeCategory !== 'all', fn ($collection) => $collection->where('category', $activeCategory))
                ->when($activeYear !== 'all', fn ($collection) => $collection->where('year', (int) $activeYear))
                ->values(),
            'achievementYears' => $allAchievements->pluck('year')->unique()->sortDesc()->values(),
            'totalCount' => $allAchievements->count(),
            'studentCount' => $allAchievements->where('category', 'students')->count(),
            'schoolCount' => $allAchievements->where('category', 'school')->count(),
            'staff' => StaffMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()->groupBy('section'),
            'activeTab' => $request->query('tab', 'about'),
            'activeCategory' => $activeCategory,
            'activeYear' => $activeYear,
            'openStaffSections' => ['academic' => true, 'administrative' => true],
            'expandedStaffCards' => [],
        ]);
    }

    public function events(Request $request): View
    {
        $filter = in_array($request->query('filter'), ['all', 'ongoing', 'upcoming', 'completed'], true)
            ? $request->query('filter')
            : 'all';

        $events = Event::where('is_active', true)
            ->orderByRaw("CASE WHEN status='ongoing' THEN 0 WHEN status='upcoming' THEN 1 ELSE 2 END")
            ->orderBy('date_start')
            ->get();

        return $this->page('livewire.website.events', compact('events', 'filter'));
    }

    public function eventShow(string $slug): View
    {
        return $this->page('livewire.website.event-show', [
            'event' => Event::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail(),
        ]);
    }

    public function academics(): View
    {
        return $this->page('livewire.website.academics', [
            'overview' => AcademicsOverview::singleton(),
            'levels' => AcademicLevel::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function studentLife(Request $request): View
    {
        return $this->page('livewire.website.student-life', [
            'clubs' => StudentLifeClub::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'prefects' => StudentLifePrefect::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'houses' => StudentLifeHouse::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'uniformBodies' => StudentLifeUniformBody::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'activeTab' => $request->query('activeTab', 'student-council'),
        ]);
    }

    public function studentLifeShow(string $type, int $id, Request $request): View
    {
        abort_unless(in_array($type, ['clubs', 'houses', 'prefects', 'uniform-bodies'], true), 404);

        $item = $this->findStudentLifeItem($type, $id);
        $historyPeople = method_exists($item, 'people')
            ? $item->people()->orderByDesc('is_active')->orderByDesc('year')->orderBy('sort_order')->orderBy('id')->get()
            : collect();
        $activePeople = $historyPeople->where('is_active', true)->values();
        $peopleYears = $historyPeople->pluck('year')->unique()->sortDesc()->values();
        $peopleFilter = $request->query('people') ?: ($activePeople->isNotEmpty() ? 'active' : (string) ($peopleYears->first() ?? 'active'));
        $people = $peopleFilter === 'active'
            ? $activePeople
            : $historyPeople->where('year', (int) $peopleFilter)->values();

        return $this->page('livewire.website.student-life-show', [
            'item' => $item,
            'type' => $type,
            'backTab' => $this->studentLifeBackTab($type),
            'typeLabel' => $this->studentLifeTypeLabel($type),
            'people' => $people,
            'peopleFilter' => $peopleFilter,
            'peopleYears' => $peopleYears,
            'hasPeopleHistory' => $historyPeople->isNotEmpty(),
            'hasActivePeople' => $activePeople->isNotEmpty(),
            'fallbackPeople' => $this->fallbackPeople($type, $item),
        ]);
    }

    public function gallery(Request $request): View
    {
        $activeCategory = $request->query('activeCategory', 'All');
        $activeYear = $request->query('activeYear', 'All');
        $activeMonth = $request->query('activeMonth', 'All');
        $categories = ['Events', 'Sports', 'Graduation', 'Cultural', 'Academic', 'Trips'];

        $albums = GalleryAlbum::where('is_active', true)
            ->when($activeCategory !== 'All', fn ($query) => $query->where('category->en', $activeCategory))
            ->when($activeYear !== 'All', fn ($query) => $query->whereYear('date', (int) $activeYear))
            ->when($activeMonth !== 'All', fn ($query) => $query->whereMonth('date', (int) $activeMonth))
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $years = GalleryAlbum::where('is_active', true)
            ->pluck('date')
            ->map(fn ($date) => date('Y', strtotime((string) $date)))
            ->unique()
            ->sortDesc()
            ->values();

        return $this->page('livewire.website.gallery', [
            'albums' => $albums,
            'years' => $years,
            'total' => GalleryAlbum::where('is_active', true)->count(),
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'activeYear' => $activeYear,
            'activeMonth' => $activeMonth,
        ]);
    }

    public function digitalServices(Request $request): View
    {
        $data = $this->digitalServicesData($request);

        return $this->page('livewire.website.digital-services', $data);
    }

    public function search(Request $request): JsonResponse
    {
        $query = mb_substr(preg_replace('/\s+/', ' ', trim((string) $request->query('q'))) ?? '', 0, 80);

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $query) . '%';
        $results = [];

        Event::where('is_active', true)
            ->where(fn ($builder) => $builder->where('title', 'like', $like)->orWhere('short_description', 'like', $like))
            ->orderByDesc('date_start')
            ->limit(5)
            ->get(['title', 'slug', 'short_description'])
            ->each(function (Event $event) use (&$results) {
                $results[] = [
                    'section' => 'Events',
                    'title' => $event->title,
                    'snippet' => $event->short_description,
                    'url' => route('events.show', $event->slug),
                ];
            });

        GalleryAlbum::where('is_active', true)
            ->where('title', 'like', $like)
            ->orderByDesc('date')
            ->limit(4)
            ->get(['title', 'category'])
            ->each(function (GalleryAlbum $album) use (&$results) {
                $results[] = [
                    'section' => 'Gallery',
                    'title' => $album->title,
                    'snippet' => $album->category,
                    'url' => route('gallery.index'),
                ];
            });

        DigitalServiceDocument::where('is_active', true)
            ->where(fn ($builder) => $builder->where('title', 'like', $like)->orWhere('category', 'like', $like))
            ->orderByDesc('published_at')
            ->limit(4)
            ->get(['title', 'category'])
            ->each(function (DigitalServiceDocument $document) use (&$results) {
                $results[] = [
                    'section' => 'Downloads',
                    'title' => $document->title,
                    'snippet' => $document->category,
                    'url' => route('digital-services.index'),
                ];
            });

        Announcement::where('is_active', true)
            ->where(fn ($builder) => $builder->where('title', 'like', $like)->orWhere('category', 'like', $like)->orWhere('description', 'like', $like))
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get(['title', 'slug', 'category', 'description'])
            ->each(function (Announcement $announcement) use (&$results) {
                $results[] = [
                    'section' => 'Announcements',
                    'title' => $announcement->title,
                    'snippet' => $announcement->category ?: $announcement->description,
                    'url' => route('announcements.show', $announcement->slug),
                ];
            });

        return response()->json(['results' => $results]);
    }

    private function page(string $view, array $data = []): View
    {
        return view('layouts.web', [
            'slot' => new HtmlString(view($view, $data)->render()),
        ]);
    }

    private function digitalServicesData(Request $request): array
    {
        $activeTab = in_array($request->query('activeTab'), ['downloads', 'resources', 'calendar'], true)
            ? $request->query('activeTab')
            : 'downloads';
        $activeCategory = $request->query('activeCategory', 'All');
        $activeYear = $request->query('activeYear', 'All');
        $activeMonth = $request->query('activeMonth', 'All');
        $activeAudience = $request->query('activeAudience', 'All');

        $documents = DigitalServiceDocument::where('is_active', true)
            ->when($activeCategory !== 'All', fn ($query) => $query->where('category->en', $activeCategory))
            ->when($activeYear !== 'All', fn ($query) => $query->whereYear('published_at', (int) $activeYear))
            ->when($activeMonth !== 'All', fn ($query) => $query->whereMonth('published_at', (int) $activeMonth))
            ->when($request->query('search'), function ($query, string $search) {
                $query->where('title->' . app()->getLocale(), 'like', '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $years = DigitalServiceDocument::where('is_active', true)
            ->pluck('published_at')
            ->map(fn ($date) => date('Y', strtotime($date)))
            ->unique()
            ->sortDesc()
            ->values();

        $resources = DigitalServiceResource::where('is_active', true)
            ->when($activeAudience !== 'All', fn ($query) => $query->where(fn ($query) => $query->where('audience', $activeAudience)->orWhere('audience', 'All')))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $allCalendars = DigitalServiceCalendar::orderBy('year', 'desc')->limit(5)->get();
        $activeCalendarId = (int) ($request->query('activeCalendarId') ?: 0);
        if ($activeCalendarId === 0) {
            $defaultCalendar = $allCalendars->firstWhere('is_active', true) ?? $allCalendars->first();
            $activeCalendarId = (int) ($defaultCalendar?->id ?? 0);
        }

        $currentCalendar = $allCalendars->firstWhere('id', $activeCalendarId);
        $calYear = $currentCalendar?->year ?? (int) date('Y');
        $calendarMonthsArr = collect(range(1, 12))
            ->map(fn (int $month) => Carbon::create($calYear, $month, 1))
            ->push(Carbon::create($calYear + 1, 1, 1))
            ->all();
        $calendarMonth = max(0, min((int) $request->query('calendarMonth', 0), count($calendarMonthsArr) - 1));
        $currentMonthCarbon = $calendarMonthsArr[$calendarMonth];

        $allCalendarEntries = $activeCalendarId
            ? DigitalServiceCalendarEntry::where('calendar_id', $activeCalendarId)
                ->where('is_active', true)
                ->orderBy('date')
                ->get()
            : collect();

        $entriesByDate = $this->entriesByDate($allCalendarEntries);
        $stats = $this->calendarStats($allCalendarEntries);

        return compact(
            'documents',
            'years',
            'resources',
            'allCalendars',
            'currentCalendar',
            'calendarMonthsArr',
            'currentMonthCarbon',
            'allCalendarEntries',
            'entriesByDate',
            'stats',
            'activeTab',
            'activeCategory',
            'activeYear',
            'activeMonth',
            'activeAudience',
            'activeCalendarId',
            'calendarMonth'
        ) + [
            'docCategories' => [
                'Forms & Applications',
                'Policies & Handbooks',
                'Timetables & Schedules',
                'Academic Resources',
            ],
            'search' => (string) $request->query('search', ''),
        ];
    }

    private function entriesByDate(Collection $allCalendarEntries): array
    {
        $entriesByDate = [];

        foreach ($allCalendarEntries as $entry) {
            $cursor = $entry->date->copy();
            $end = $entry->end_date ? $entry->end_date->copy() : $cursor->copy();

            while ($cursor->lte($end)) {
                $entriesByDate[$cursor->format('Y-m-d')][] = $entry;
                $cursor->addDay();
            }
        }

        return $entriesByDate;
    }

    private function calendarStats(Collection $allCalendarEntries): array
    {
        $countWeekdayDays = function (string $type) use ($allCalendarEntries): int {
            $days = [];

            foreach ($allCalendarEntries->where('type', $type) as $entry) {
                $cursor = $entry->date->copy();
                $end = $entry->end_date ? $entry->end_date->copy() : $cursor->copy();

                while ($cursor->lte($end)) {
                    if (! $cursor->isWeekend()) {
                        $days[$cursor->format('Y-m-d')] = true;
                    }
                    $cursor->addDay();
                }
            }

            return count($days);
        };

        $termMarkers = $allCalendarEntries->where('type', 'term')->sortBy('date')->values();
        $totalTermWeekdays = 0;

        for ($i = 0; $i < $termMarkers->count() - 1; $i += 2) {
            $start = $termMarkers->get($i)?->date;
            $end = $termMarkers->get($i + 1)?->date;
            if (! $start || ! $end) {
                continue;
            }

            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                if (! $cursor->isWeekend()) {
                    $totalTermWeekdays++;
                }
                $cursor->addDay();
            }
        }

        $holidayDays = $countWeekdayDays('holiday');
        $examDays = $countWeekdayDays('exam');
        $teachingDays = max(0, $totalTermWeekdays - $holidayDays - $examDays);

        return [
            'teaching' => $teachingDays,
            'exam' => $examDays,
            'holiday' => $holidayDays,
            'total' => $teachingDays + $examDays,
            'events' => $allCalendarEntries->where('type', 'event')->count(),
        ];
    }

    private function findStudentLifeItem(string $type, int $id): Model
    {
        return match ($type) {
            'clubs' => StudentLifeClub::where('is_active', true)->findOrFail($id),
            'houses' => StudentLifeHouse::where('is_active', true)->findOrFail($id),
            'prefects' => StudentLifePrefect::where('is_active', true)->findOrFail($id),
            'uniform-bodies' => StudentLifeUniformBody::where('is_active', true)->findOrFail($id),
        };
    }

    private function studentLifeBackTab(string $type): string
    {
        return match ($type) {
            'clubs', 'houses' => 'student-council',
            'prefects' => 'prefects',
            'uniform-bodies' => 'uniform-bodies',
        };
    }

    private function studentLifeTypeLabel(string $type): string
    {
        return match ($type) {
            'clubs' => __('student_life_tab_clubs'),
            'houses' => __('student_life_tab_houses'),
            'prefects' => __('student_life_tab_prefects'),
            'uniform-bodies' => __('student_life_tab_uniform_bodies'),
        };
    }

    private function fallbackPeople(string $type, Model $item): array
    {
        return match ($type) {
            'clubs' => [
                ['name' => $item->patron_name, 'role' => $item->patron_role ?: __('student_life_clubs_teacher_in_charge'), 'grade' => null, 'color' => 'blue'],
                ['name' => $item->president_name, 'role' => __('student_life_clubs_president'), 'grade' => $item->president_class, 'color' => 'red'],
            ],
            'houses' => [
                ['name' => $item->house_master_name, 'role' => $item->house_master_role ?: __('student_life_houses_master'), 'grade' => null, 'color' => 'blue'],
                ['name' => $item->captain_name, 'role' => __('student_life_houses_captain'), 'grade' => $item->captain_class, 'color' => 'red'],
            ],
            'uniform-bodies' => [
                ['name' => $item->patron_name, 'role' => $item->patron_role ?: __('student_life_uniform_patron'), 'grade' => null, 'color' => 'blue'],
                ['name' => $item->leader_name, 'role' => __('student_life_uniform_leader'), 'grade' => $item->leader_class, 'color' => 'red'],
            ],
            default => [],
        };
    }
}
