<?php

namespace App\Livewire\Website;

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

        $calendarEntries = DigitalServiceCalendarEntry::where('is_active', true)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        return view('livewire.website.digital-services', compact(
            'documents', 'years', 'resources', 'calendarEntries'
        ))->layout('layouts.web');
    }
}
