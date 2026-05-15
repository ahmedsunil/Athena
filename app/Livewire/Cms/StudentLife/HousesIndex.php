<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeHouse;
use Livewire\Component;

class HousesIndex extends Component
{
    public string $name_en = '';
    public string $name_dv = '';
    public string $colour = 'rose';
    public string $motto_en = '';
    public string $motto_dv = '';
    public string $description_en = '';
    public string $description_dv = '';
    public string $house_master_name = '';
    public string $house_master_role_en = '';
    public string $house_master_role_dv = '';
    public string $captain_name = '';
    public string $captain_class = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name_en'              => ['required', 'string', 'max:255'],
            'name_dv'              => ['nullable', 'string', 'max:255'],
            'colour'               => ['required', 'in:rose,sky,emerald,amber'],
            'motto_en'             => ['nullable', 'string', 'max:255'],
            'motto_dv'             => ['nullable', 'string', 'max:255'],
            'description_en'       => ['nullable', 'string'],
            'description_dv'       => ['nullable', 'string'],
            'house_master_name'    => ['nullable', 'string', 'max:255'],
            'house_master_role_en' => ['nullable', 'string', 'max:255'],
            'house_master_role_dv' => ['nullable', 'string', 'max:255'],
            'captain_name'         => ['nullable', 'string', 'max:255'],
            'captain_class'        => ['nullable', 'string', 'max:100'],
            'sort_order'           => ['integer', 'min:0'],
            'is_active'            => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'              => ['en' => $this->name_en, 'dv' => $this->name_dv],
            'colour'            => $this->colour,
            'motto'             => ($this->motto_en || $this->motto_dv)
                                    ? ['en' => $this->motto_en, 'dv' => $this->motto_dv]
                                    : null,
            'description'       => ($this->description_en || $this->description_dv)
                                    ? ['en' => $this->description_en, 'dv' => $this->description_dv]
                                    : null,
            'house_master_name' => $this->house_master_name ?: null,
            'house_master_role' => ($this->house_master_role_en || $this->house_master_role_dv)
                                    ? ['en' => $this->house_master_role_en, 'dv' => $this->house_master_role_dv]
                                    : null,
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
        $this->editingId              = $house->id;
        $this->name_en                = $house->getTranslation('name', 'en', false) ?? '';
        $this->name_dv                = $house->getTranslation('name', 'dv', false) ?? '';
        $this->colour                 = $house->colour;
        $this->motto_en               = $house->getTranslation('motto', 'en', false) ?? '';
        $this->motto_dv               = $house->getTranslation('motto', 'dv', false) ?? '';
        $this->description_en         = $house->getTranslation('description', 'en', false) ?? '';
        $this->description_dv         = $house->getTranslation('description', 'dv', false) ?? '';
        $this->house_master_name      = $house->house_master_name ?? '';
        $this->house_master_role_en   = $house->getTranslation('house_master_role', 'en', false) ?? '';
        $this->house_master_role_dv   = $house->getTranslation('house_master_role', 'dv', false) ?? '';
        $this->captain_name           = $house->captain_name ?? '';
        $this->captain_class          = $house->captain_class ?? '';
        $this->sort_order             = $house->sort_order;
        $this->is_active              = $house->is_active;
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
            'name_en', 'name_dv', 'motto_en', 'motto_dv', 'description_en', 'description_dv',
            'house_master_name', 'house_master_role_en', 'house_master_role_dv',
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
