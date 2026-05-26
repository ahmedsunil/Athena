<?php

namespace App\Livewire\Website;

use App\Models\Achievement;
use App\Models\FoundingMember;
use App\Models\HistorySection;
use App\Models\LeadershipMember;
use App\Models\Mission;
use App\Models\SchoolProfile;
use App\Models\StaffMember;
use Livewire\Component;

class About extends Component
{
    public string $activeTab = 'about';
    public string $activeCategory = 'all';
    public string $activeYear = 'all';
    public array $openStaffSections = [];
    public array $expandedStaffCards = [];

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function switchCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function switchYear(string $year): void
    {
        $this->activeYear = $year;
    }

    public function toggleStaffSection(string $section): void
    {
        $this->openStaffSections[$section] = ! ($this->openStaffSections[$section] ?? false);
    }

    public function toggleStaffCard(int $memberId): void
    {
        $this->expandedStaffCards[$memberId] = ! ($this->expandedStaffCards[$memberId] ?? false);
    }

    public function render()
    {
        $profile = SchoolProfile::singleton();

        $mission         = null;
        $leadership      = collect();
        $foundingMembers = collect();
        $historySections = collect();
        $staff           = collect();
        $achievements    = collect();
        $achievementYears = collect();
        $totalCount = $studentCount = $schoolCount = 0;

        match ($this->activeTab) {
            'about' => [
                $mission    = Mission::singleton(),
                $leadership = LeadershipMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            ],
            'history' => [
                $historySections = HistorySection::orderBy('sort_order')->orderBy('id')->get(),
                $foundingMembers = FoundingMember::orderBy('sort_order')->orderBy('id')->get(),
            ],
            'achievements' => (function () use (&$achievements, &$achievementYears, &$totalCount, &$studentCount, &$schoolCount) {
                $all = Achievement::where('is_active', true)
                    ->orderByDesc('year')->orderBy('sort_order')->orderBy('id')
                    ->get();
                $achievements     = $all
                    ->when($this->activeCategory !== 'all', fn ($c) => $c->where('category', $this->activeCategory))
                    ->when($this->activeYear !== 'all', fn ($c) => $c->where('year', (int) $this->activeYear))
                    ->values();
                $achievementYears = $all->pluck('year')->unique()->sortDesc()->values();
                $totalCount       = $all->count();
                $studentCount     = $all->where('category', 'students')->count();
                $schoolCount      = $all->where('category', 'school')->count();
            })(),
            'team' => [
                $leadership = LeadershipMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
                $staff      = StaffMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()->groupBy('section'),
            ],
            default => null,
        };

        return view('livewire.website.about', [
            'profile'          => $profile,
            'mission'          => $mission,
            'leadership'       => $leadership,
            'foundingMembers'  => $foundingMembers,
            'historySections'  => $historySections,
            'achievements'     => $achievements,
            'achievementYears' => $achievementYears,
            'totalCount'       => $totalCount,
            'studentCount'     => $studentCount,
            'schoolCount'      => $schoolCount,
            'staff'            => $staff,
        ])->layout('layouts.web', ['title' => 'About']);
    }
}
