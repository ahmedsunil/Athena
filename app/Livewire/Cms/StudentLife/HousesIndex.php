<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeHouse;
use Livewire\Component;

class HousesIndex extends Component
{
    public string $name = '';
    public string $colour = 'rose';
    public string $motto = '';
    public string $description = '';
    public string $house_master_name = '';
    public string $house_master_role = '';
    public string $captain_name = '';
    public string $captain_class = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'colour'            => ['required', 'in:rose,sky,emerald,amber'],
            'motto'             => ['nullable', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'house_master_name' => ['nullable', 'string', 'max:255'],
            'house_master_role' => ['nullable', 'string', 'max:255'],
            'captain_name'      => ['nullable', 'string', 'max:255'],
            'captain_class'     => ['nullable', 'string', 'max:100'],
            'sort_order'        => ['integer', 'min:0'],
            'is_active'         => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'              => $this->name,
            'colour'            => $this->colour,
            'motto'             => $this->motto ?: null,
            'description'       => $this->description ?: null,
            'house_master_name' => $this->house_master_name ?: null,
            'house_master_role' => $this->house_master_role ?: null,
            'captain_name'      => $this->captain_name ?: null,
            'captain_class'     => $this->captain_class ?: null,
            'sort_order'        => $this->sort_order,
            'is_active'         => $this->is_active,
        ];

        if ($this->editingId) {
            StudentLifeHouse::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'House updated.');
        } else {
            StudentLifeHouse::create($data);
            $this->dispatch('toast', message: 'House added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $house = StudentLifeHouse::findOrFail($id);
        $this->editingId         = $house->id;
        $this->name              = $house->name;
        $this->colour            = $house->colour;
        $this->motto             = $house->motto ?? '';
        $this->description       = $house->description ?? '';
        $this->house_master_name = $house->house_master_name ?? '';
        $this->house_master_role = $house->house_master_role ?? '';
        $this->captain_name      = $house->captain_name ?? '';
        $this->captain_class     = $house->captain_class ?? '';
        $this->sort_order        = $house->sort_order;
        $this->is_active         = $house->is_active;
    }

    public function delete(int $id): void
    {
        StudentLifeHouse::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'House deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'motto', 'description',
            'house_master_name', 'house_master_role',
            'captain_name', 'captain_class', 'editingId',
        ]);
        $this->colour     = 'rose';
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.student-life.houses-index', [
            'houses' => StudentLifeHouse::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Student Life — Houses']);
    }
}
