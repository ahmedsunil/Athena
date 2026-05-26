<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeClub;
use App\Models\StudentLifeHouse;
use App\Models\StudentLifePerson;
use App\Models\StudentLifeUniformBody;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;

class PeopleIndex extends Component
{
    use WithFileUploads;

    public string $type = 'clubs';
    public string $item_id = '';
    public string $year = '';
    public string $name = '';
    public string $designation = '';
    public string $grade = '';
    public bool $is_teacher_in_charge = false;
    public bool $is_active = false;
    public int $sort_order = 0;
    public $avatar = null;
    public ?string $existing_avatar = null;
    public bool $avatarRemoved = false;
    public ?int $editingId = null;

    public function mount(): void
    {
        $this->year = (string) now()->year;
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:clubs,houses,uniform-bodies'],
            'item_id' => ['required', 'integer', 'min:1'],
            'year' => ['required', 'integer', 'min:1900', 'max:2100'],
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:100'],
            'is_teacher_in_charge' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function updatedType(): void
    {
        $this->item_id = '';
    }

    public function updatedIsTeacherInCharge(bool $value): void
    {
        if ($value) {
            $this->grade = '';
        }
    }

    public function save(): void
    {
        $this->validate();

        $owner = $this->owner();

        $data = [
            'personable_type' => $owner::class,
            'personable_id' => $owner->getKey(),
            'year' => (int) $this->year,
            'name' => $this->name,
            'designation' => $this->designation,
            'grade' => $this->is_teacher_in_charge ? null : ($this->grade ?: null),
            'is_teacher_in_charge' => $this->is_teacher_in_charge,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->avatar) {
            $data['avatar_path'] = $this->avatar->store('student-life/people', 'public');
        } elseif ($this->avatarRemoved) {
            $data['avatar_path'] = null;
        }

        if ($this->editingId) {
            StudentLifePerson::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Person updated.');
        } else {
            StudentLifePerson::create($data);
            $this->dispatch('toast', message: 'Person added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $person = StudentLifePerson::findOrFail($id);
        $this->editingId = $person->id;
        $this->type = $this->typeFromClass($person->personable_type);
        $this->item_id = (string) $person->personable_id;
        $this->year = (string) $person->year;
        $this->name = $person->name;
        $this->designation = $person->designation;
        $this->grade = $person->grade ?? '';
        $this->is_teacher_in_charge = $person->is_teacher_in_charge;
        $this->is_active = $person->is_active;
        $this->sort_order = $person->sort_order;
        $this->existing_avatar = $person->avatar_path;
        $this->avatarRemoved = false;
    }

    public function delete(int $id): void
    {
        StudentLifePerson::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Person deleted.');
    }

    public function removeAvatar(): void
    {
        $this->avatar = null;
        $this->existing_avatar = null;
        $this->avatarRemoved = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $type = $this->type;
        $itemId = $this->item_id;

        $this->reset([
            'name', 'designation', 'grade', 'is_teacher_in_charge', 'is_active',
            'sort_order', 'avatar', 'existing_avatar', 'avatarRemoved', 'editingId',
        ]);

        $this->type = $type;
        $this->item_id = $itemId;
        $this->year = (string) now()->year;
    }

    private function owner(): Model
    {
        return match ($this->type) {
            'clubs' => StudentLifeClub::findOrFail((int) $this->item_id),
            'houses' => StudentLifeHouse::findOrFail((int) $this->item_id),
            'uniform-bodies' => StudentLifeUniformBody::findOrFail((int) $this->item_id),
        };
    }

    private function typeFromClass(string $class): string
    {
        return match ($class) {
            StudentLifeHouse::class => 'houses',
            StudentLifeUniformBody::class => 'uniform-bodies',
            default => 'clubs',
        };
    }

    public function render()
    {
        return view('livewire.cms.student-life.people-index', [
            'items' => match ($this->type) {
                'houses' => StudentLifeHouse::orderBy('sort_order')->orderBy('id')->get(),
                'uniform-bodies' => StudentLifeUniformBody::orderBy('sort_order')->orderBy('id')->get(),
                default => StudentLifeClub::orderBy('sort_order')->orderBy('id')->get(),
            },
            'people' => StudentLifePerson::with('personable')
                ->orderByDesc('is_active')
                ->orderByDesc('year')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Student Life — People History']);
    }
}
