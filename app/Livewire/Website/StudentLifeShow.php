<?php

namespace App\Livewire\Website;

use App\Models\StudentLifeClub;
use App\Models\StudentLifeHouse;
use App\Models\StudentLifePrefect;
use App\Models\StudentLifeUniformBody;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Url;
use Livewire\Component;

class StudentLifeShow extends Component
{
    public string $type;

    public int $id;

    #[Url(as: 'people')]
    public ?string $peopleFilter = null;

    public function mount(string $type, int $id): void
    {
        abort_unless(in_array($type, ['clubs', 'houses', 'prefects', 'uniform-bodies'], true), 404);

        $this->type = $type;
        $this->id = $id;
    }

    public function render()
    {
        $item = $this->findItem();
        $historyPeople = method_exists($item, 'people')
            ? $item->people()->orderByDesc('is_active')->orderByDesc('year')->orderBy('sort_order')->orderBy('id')->get()
            : collect();
        $activePeople = $historyPeople->where('is_active', true)->values();
        $peopleYears = $historyPeople->pluck('year')->unique()->sortDesc()->values();

        if ($this->peopleFilter === null) {
            $this->peopleFilter = $activePeople->isNotEmpty() ? 'active' : (string) ($peopleYears->first() ?? 'active');
        }

        $people = $this->peopleFilter === 'active'
            ? $activePeople
            : $historyPeople->where('year', (int) $this->peopleFilter)->values();

        return view('livewire.website.student-life-show', [
            'item' => $item,
            'type' => $this->type,
            'backTab' => $this->backTab(),
            'typeLabel' => $this->typeLabel(),
            'people' => $people,
            'peopleYears' => $peopleYears,
            'hasPeopleHistory' => $historyPeople->isNotEmpty(),
            'hasActivePeople' => $activePeople->isNotEmpty(),
            'fallbackPeople' => $this->fallbackPeople($item),
        ])->layout('layouts.web');
    }

    public function setPeopleFilter(string $filter): void
    {
        $this->peopleFilter = $filter;
    }

    private function findItem(): Model
    {
        return match ($this->type) {
            'clubs' => StudentLifeClub::where('is_active', true)->findOrFail($this->id),
            'houses' => StudentLifeHouse::where('is_active', true)->findOrFail($this->id),
            'prefects' => StudentLifePrefect::where('is_active', true)->findOrFail($this->id),
            'uniform-bodies' => StudentLifeUniformBody::where('is_active', true)->findOrFail($this->id),
        };
    }

    private function backTab(): string
    {
        return match ($this->type) {
            'clubs', 'houses' => 'student-council',
            'prefects' => 'prefects',
            'uniform-bodies' => 'uniform-bodies',
        };
    }

    private function typeLabel(): string
    {
        return match ($this->type) {
            'clubs' => __('student_life_tab_clubs'),
            'houses' => __('student_life_tab_houses'),
            'prefects' => __('student_life_tab_prefects'),
            'uniform-bodies' => __('student_life_tab_uniform_bodies'),
        };
    }

    private function fallbackPeople(Model $item): array
    {
        return match ($this->type) {
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
