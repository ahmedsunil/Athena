<?php

namespace App\Livewire\Website;

use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceResource;
use App\Models\DigitalServiceCalendarEntry;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DigitalServices extends Component
{
    use WithPagination;
    #[Url]
    public string $activeTab = 'downloads';

    #[Url]
    public string $activeCategory = 'All';

    #[Url]
    public string $activeYear = 'All';

    #[Url]
    public string $activeMonth = 'All';

    #[Url]
    public string $activeAudience = 'All';

    #[Url]
    public ?int $activeCalendarId = null;

    #[Url]
    public int $calendarMonth = 0;

    public string $search = '';

    public array $docCategories = [
        'Forms & Applications',
        'Policies & Handbooks',
        'Timetables & Schedules',
        'Academic Resources',
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = in_array($tab, ['downloads', 'resources', 'calendar']) ? $tab : 'downloads';
    }

    public function setCategory(string $category): void
    {
        $this->activeCategory = $category;
        $this->resetPage();
    }

    public function setAudience(string $audience): void
    {
        $this->activeAudience = $audience;
    }

    public function updatedActiveCalendarId(): void
    {
        $this->calendarMonth = 0;
    }

    public function nextMonth(): void
    {
        if ($this->calendarMonth < 12) {
            $this->calendarMonth++;
        }
    }

    public function prevMonth(): void
    {
        if ($this->calendarMonth > 0) {
            $this->calendarMonth--;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedActiveYear(): void
    {
        $this->resetPage();
    }

    public function updatedActiveMonth(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->activeCategory = 'All';
        $this->activeYear     = 'All';
        $this->activeMonth    = 'All';
        $this->search         = '';
        $this->resetPage();
    }

    public function render()
    {
        $docQuery = DigitalServiceDocument::where('is_active', true);

        if ($this->activeCategory !== 'All') {
            $docQuery->where('category->en', $this->activeCategory);
        }
        if ($this->activeYear !== 'All') {
            $docQuery->whereYear('published_at', (int) $this->activeYear);
        }
        if ($this->activeMonth !== 'All') {
            $docQuery->whereMonth('published_at', (int) $this->activeMonth);
        }
        if ($this->search !== '') {
            $docQuery->where('title->' . app()->getLocale(), 'like', '%' . $this->search . '%');
        }

        $documents = $docQuery->orderBy('sort_order')->orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(10);

        $years = DigitalServiceDocument::where('is_active', true)
            ->pluck('published_at')
            ->map(fn ($d) => date('Y', strtotime($d)))
            ->unique()
            ->sortDesc()
            ->values();

        $resourceQuery = DigitalServiceResource::where('is_active', true);
        if ($this->activeAudience !== 'All') {
            $resourceQuery->where(function ($q) {
                $q->where('audience', $this->activeAudience)->orWhere('audience', 'All');
            });
        }
        $resources = $resourceQuery->orderBy('sort_order')->orderBy('id')->get();

        $allCalendars = DigitalServiceCalendar::orderBy('year', 'desc')->limit(5)->get();

        if ($this->activeCalendarId === null) {
            $defaultCal = $allCalendars->firstWhere('is_active', true) ?? $allCalendars->first();
            $this->activeCalendarId = $defaultCal?->id;
        }

        $currentCalendar = $allCalendars->firstWhere('id', $this->activeCalendarId);
        $calYear         = $currentCalendar?->year ?? (int) date('Y');

        // Build months array: Jan through Jan of next year (indices 0–12)
        $calendarMonthsArr = [];
        for ($m = 1; $m <= 12; $m++) {
            $calendarMonthsArr[] = Carbon::create($calYear, $m, 1);
        }
        $calendarMonthsArr[] = Carbon::create($calYear + 1, 1, 1);

        $monthIndex         = max(0, min($this->calendarMonth, count($calendarMonthsArr) - 1));
        $currentMonthCarbon = $calendarMonthsArr[$monthIndex];

        // Load all active entries for this calendar
        $allCalendarEntries = $this->activeCalendarId
            ? DigitalServiceCalendarEntry::where('calendar_id', $this->activeCalendarId)
                ->where('is_active', true)
                ->orderBy('date')
                ->get()
            : collect();

        // Build date → entries map, expanding multi-day ranges
        $entriesByDate = [];
        foreach ($allCalendarEntries as $entry) {
            $start = $entry->date->copy();
            $end   = $entry->end_date ? $entry->end_date->copy() : $start->copy();
            $cur   = $start->copy();
            while ($cur->lte($end)) {
                $entriesByDate[$cur->format('Y-m-d')][] = $entry;
                $cur->addDay();
            }
        }

        // Derive total teaching/exam/holiday days across all term periods
        $termMarkers = $allCalendarEntries->where('type', 'term')->sortBy('date')->values();

        $countWeekdayDays = function (string $type) use ($allCalendarEntries): int {
            $days = [];
            foreach ($allCalendarEntries->where('type', $type) as $entry) {
                $cur = $entry->date->copy();
                $end = $entry->end_date ? $entry->end_date->copy() : $cur->copy();
                while ($cur->lte($end)) {
                    if (! $cur->isWeekend()) {
                        $days[$cur->format('Y-m-d')] = true;
                    }
                    $cur->addDay();
                }
            }
            return count($days);
        };

        // Sum weekdays across all term periods (marker pairs)
        $totalTermWeekdays = 0;
        for ($i = 0; $i < $termMarkers->count() - 1; $i += 2) {
            $tStart = $termMarkers->get($i)?->date;
            $tEnd   = $termMarkers->get($i + 1)?->date;
            if (! $tStart || ! $tEnd) {
                continue;
            }
            $cur = $tStart->copy();
            while ($cur->lte($tEnd)) {
                if (! $cur->isWeekend()) {
                    $totalTermWeekdays++;
                }
                $cur->addDay();
            }
        }

        $holidayDays  = $countWeekdayDays('holiday');
        $examDays     = $countWeekdayDays('exam');
        $teachingDays = max(0, $totalTermWeekdays - $holidayDays - $examDays);

        $stats = [
            'teaching' => $teachingDays,
            'exam'     => $examDays,
            'holiday'  => $holidayDays,
            'total'    => $teachingDays + $examDays,
            'events'   => $allCalendarEntries->where('type', 'event')->count(),
        ];

        return view('livewire.website.digital-services', compact(
            'documents', 'years', 'resources',
            'allCalendars', 'currentCalendar',
            'calendarMonthsArr', 'currentMonthCarbon',
            'allCalendarEntries', 'entriesByDate', 'stats'
        ))->layout('layouts.web');
    }
}
