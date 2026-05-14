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

    public function render()
    {
        $profile = SchoolProfile::singleton();
        $mission = Mission::singleton();
        $leadership = LeadershipMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $foundingMembers = FoundingMember::orderBy('sort_order')->orderBy('id')->get();
        $historySections = HistorySection::orderBy('sort_order')->orderBy('id')->get();

        $achievementsQuery = Achievement::where('is_active', true);
        if ($this->activeCategory !== 'all') {
            $achievementsQuery->where('category', $this->activeCategory);
        }
        if ($this->activeYear !== 'all') {
            $achievementsQuery->where('year', (int) $this->activeYear);
        }
        $achievements = $achievementsQuery->orderByDesc('year')->orderBy('sort_order')->orderBy('id')->get();

        $achievementYears = Achievement::where('is_active', true)->distinct()->orderByDesc('year')->pluck('year');
        $totalCount   = Achievement::where('is_active', true)->count();
        $studentCount = Achievement::where('is_active', true)->where('category', 'students')->count();
        $schoolCount  = Achievement::where('is_active', true)->where('category', 'school')->count();

        $staff = StaffMember::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('section');

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
