<?php

namespace App\Livewire\Website;

use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceResource;
use App\Models\DigitalServiceCalendarEntry;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class DigitalServices extends Component
{
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

    public int $selectedTerm = 1;

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
    }

    public function setAudience(string $audience): void
    {
        $this->activeAudience = $audience;
    }

    public function setCalendar(int $id): void
    {
        $this->activeCalendarId = $id;
        $this->calendarMonth    = 0;
    }

    public function setTerm(int $term): void
    {
        $this->selectedTerm = in_array($term, [1, 2]) ? $term : 1;
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

    public function clearFilters(): void
    {
        $this->activeCategory = 'All';
        $this->activeYear     = 'All';
        $this->activeMonth    = 'All';
        $this->search         = '';
    }

    public function render()
    {
        $docQuery = DigitalServiceDocument::where('is_active', true);

        if ($this->activeCategory !== 'All') {
            $docQuery->where('category', $this->activeCategory);
        }
        if ($this->activeYear !== 'All') {
            $docQuery->whereYear('published_at', (int) $this->activeYear);
        }
        if ($this->activeMonth !== 'All') {
            $docQuery->whereMonth('published_at', (int) $this->activeMonth);
        }
        if ($this->search !== '') {
            $docQuery->where('title', 'like', '%' . $this->search . '%');
        }

        $documents = $docQuery->orderBy('sort_order')->orderBy('published_at', 'desc')->orderBy('id', 'desc')->get();

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

        $allCalendars = DigitalServiceCalendar::orderBy('year', 'desc')->get();

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

        // Compute stats per half-year term (Term 1 = Jan–Jun, Term 2 = Jul–Dec)
        $stats = [];
        foreach ([1, 2] as $term) {
            $termStart = Carbon::create($calYear, $term === 1 ? 1 : 7, 1);
            $termEnd   = Carbon::create($calYear, $term === 1 ? 6 : 12, 1)->endOfMonth();

            $countDays = function (string $type) use ($allCalendarEntries, $termStart, $termEnd): int {
                $days = [];
                foreach ($allCalendarEntries->where('type', $type) as $entry) {
                    $s = $entry->date->copy()->max($termStart);
                    $e = ($entry->end_date ?? $entry->date)->copy()->min($termEnd);
                    if ($s->gt($e)) {
                        continue;
                    }
                    $cur = $s->copy();
                    while ($cur->lte($e)) {
                        $days[$cur->format('Y-m-d')] = true;
                        $cur->addDay();
                    }
                }
                return count($days);
            };

            $holidayDays = $countDays('holiday');
            $examDays    = $countDays('exam');
            $termDays    = $countDays('term');

            $stats[$term] = [
                'teaching' => max(0, $termDays - $holidayDays),
                'exam'     => $examDays,
                'holiday'  => $holidayDays,
                'total'    => max(0, $termDays - $holidayDays) + $examDays,
            ];
        }

        return view('livewire.website.digital-services', compact(
            'documents', 'years', 'resources',
            'allCalendars', 'currentCalendar',
            'calendarMonthsArr', 'currentMonthCarbon',
            'allCalendarEntries', 'entriesByDate', 'stats'
        ))->layout('layouts.web');
    }
}
