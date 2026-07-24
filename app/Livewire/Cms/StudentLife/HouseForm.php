<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeHouse;
use Livewire\Component;

class HouseForm extends Component
{
    public ?int $houseId = null;

    public string $name_en = '';
    public string $colour = 'rose';
    public string $motto_en = '';
    public string $description_en = '';
    public string $house_master_name = '';
    public string $house_master_role_en = '';
    public string $captain_name = '';
    public string $captain_class = '';
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $houseId = null): void
    {
        if ($houseId) {
            $house = StudentLifeHouse::findOrFail($houseId);
            $this->houseId                = $house->id;
            $this->name_en                = $house->getTranslation('name', 'en', false) ?? '';
            $this->colour                 = $house->colour;
            $this->motto_en               = $house->getTranslation('motto', 'en', false) ?? '';
            $this->description_en         = $house->getTranslation('description', 'en', false) ?? '';
            $this->house_master_name      = $house->house_master_name ?? '';
            $this->house_master_role_en   = $house->getTranslation('house_master_role', 'en', false) ?? '';
            $this->captain_name           = $house->captain_name ?? '';
            $this->captain_class          = $house->captain_class ?? '';
            $this->sort_order             = $house->sort_order;
            $this->is_active              = $house->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'name_en'              => ['required', 'string', 'max:255'],
            'colour'               => ['required', 'in:rose,sky,emerald,amber'],
            'motto_en'             => ['nullable', 'string', 'max:255'],
            'description_en'       => ['nullable', 'string'],
            'house_master_name'    => ['nullable', 'string', 'max:255'],
            'house_master_role_en' => ['nullable', 'string', 'max:255'],
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
            'name'              => ['en' => $this->name_en],
            'colour'            => $this->colour,
            'motto'             => $this->motto_en ? ['en' => $this->motto_en] : null,
            'description'       => $this->description_en ? ['en' => $this->description_en] : null,
            'house_master_name' => $this->house_master_name ?: null,
            'house_master_role' => $this->house_master_role_en ? ['en' => $this->house_master_role_en] : null,
            'captain_name'      => $this->captain_name ?: null,
            'captain_class'     => $this->captain_class ?: null,
            'sort_order'        => $this->sort_order,
            'is_active'         => $this->is_active,
        ];

        if ($this->houseId) {
            StudentLifeHouse::findOrFail($this->houseId)->update($data);
            $this->dispatch('toast', message: 'House updated.');
        } else {
            StudentLifeHouse::create($data);
            $this->dispatch('toast', message: 'House added.');
        }

        $this->redirect(route('cms.houses'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.student-life.house-form')
            ->layout('layouts.app', ['title' => $this->houseId ? 'Edit House' : 'New House']);
    }
}
