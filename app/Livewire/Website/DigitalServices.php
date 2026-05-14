<?php

namespace App\Livewire\Website;

use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceResource;
use App\Models\DigitalServiceCalendarEntry;
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

    public int $calendarPage = 1;
    public int $calendarPerPage = 15;

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
        $this->calendarPage = 1;
    }

    public function calendarNextPage(): void
    {
        $this->calendarPage++;
    }

    public function calendarPrevPage(): void
    {
        if ($this->calendarPage > 1) {
            $this->calendarPage--;
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

        $calendarEntriesQuery = DigitalServiceCalendarEntry::where('is_active', true)
            ->where('calendar_id', $this->activeCalendarId)
            ->orderBy('date')->orderBy('id');

        $totalCalendarEntries = $calendarEntriesQuery->count();
        $calendarEntries = $calendarEntriesQuery
            ->skip(($this->calendarPage - 1) * $this->calendarPerPage)
            ->take($this->calendarPerPage)
            ->get();

        $calendarTotalPages = (int) ceil($totalCalendarEntries / $this->calendarPerPage);

        return view('livewire.website.digital-services', compact(
            'documents', 'years', 'resources',
            'allCalendars', 'currentCalendar', 'calendarEntries', 'calendarTotalPages'
        ))->layout('layouts.web');
    }
}
