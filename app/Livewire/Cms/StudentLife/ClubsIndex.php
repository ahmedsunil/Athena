<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeClub;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClubsIndex extends Component
{
    use WithFileUploads;

    public string $name_en = '';
    public string $name_dv = '';
    public string $description_en = '';
    public string $description_dv = '';
    public string $meeting_schedule_en = '';
    public string $meeting_schedule_dv = '';
    public string $patron_name = '';
    public string $patron_role_en = '';
    public string $patron_role_dv = '';
    public string $president_name = '';
    public string $president_class = '';
    public $logo = null;
    public ?string $existing_logo = null;
    public bool $logoRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name_en'              => ['required', 'string', 'max:255'],
            'name_dv'              => ['nullable', 'string', 'max:255'],
            'description_en'       => ['nullable', 'string'],
            'description_dv'       => ['nullable', 'string'],
            'meeting_schedule_en'  => ['nullable', 'string', 'max:255'],
            'meeting_schedule_dv'  => ['nullable', 'string', 'max:255'],
            'patron_name'          => ['nullable', 'string', 'max:255'],
            'patron_role_en'       => ['nullable', 'string', 'max:255'],
            'patron_role_dv'       => ['nullable', 'string', 'max:255'],
            'president_name'       => ['nullable', 'string', 'max:255'],
            'president_class'      => ['nullable', 'string', 'max:100'],
            'logo'                 => ['nullable', 'image', 'max:2048'],
            'sort_order'           => ['integer', 'min:0'],
            'is_active'            => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'             => ['en' => $this->name_en, 'dv' => $this->name_dv],
            'description'      => ($this->description_en || $this->description_dv)
                                   ? ['en' => $this->description_en, 'dv' => $this->description_dv]
                                   : null,
            'meeting_schedule' => ($this->meeting_schedule_en || $this->meeting_schedule_dv)
                                   ? ['en' => $this->meeting_schedule_en, 'dv' => $this->meeting_schedule_dv]
                                   : null,
            'patron_name'      => $this->patron_name ?: null,
            'patron_role'      => ($this->patron_role_en || $this->patron_role_dv)
                                   ? ['en' => $this->patron_role_en, 'dv' => $this->patron_role_dv]
                                   : null,
            'president_name'   => $this->president_name ?: null,
            'president_class'  => $this->president_class ?: null,
            'sort_order'       => $this->sort_order,
            'is_active'        => $this->is_active,
        ];

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('student-life/clubs', 'public');
        } elseif ($this->logoRemoved) {
            $data['logo_path'] = null;
        }

        if ($this->editingId) {
            StudentLifeClub::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Club updated.');
        } else {
            StudentLifeClub::create($data);
            $this->dispatch('toast', message: 'Club added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $club = StudentLifeClub::findOrFail($id);
        $this->editingId            = $club->id;
        $this->name_en              = $club->getTranslation('name', 'en', false) ?? '';
        $this->name_dv              = $club->getTranslation('name', 'dv', false) ?? '';
        $this->description_en       = $club->getTranslation('description', 'en', false) ?? '';
        $this->description_dv       = $club->getTranslation('description', 'dv', false) ?? '';
        $this->meeting_schedule_en  = $club->getTranslation('meeting_schedule', 'en', false) ?? '';
        $this->meeting_schedule_dv  = $club->getTranslation('meeting_schedule', 'dv', false) ?? '';
        $this->patron_name          = $club->patron_name ?? '';
        $this->patron_role_en       = $club->getTranslation('patron_role', 'en', false) ?? '';
        $this->patron_role_dv       = $club->getTranslation('patron_role', 'dv', false) ?? '';
        $this->president_name       = $club->president_name ?? '';
        $this->president_class      = $club->president_class ?? '';
        $this->existing_logo        = $club->logo_path;
        $this->logoRemoved          = false;
        $this->sort_order           = $club->sort_order;
        $this->is_active            = $club->is_active;
    }

    public function delete(int $id): void
    {
        StudentLifeClub::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Club deleted.');
    }

    public function removeLogo(): void
    {
        $this->logo          = null;
        $this->existing_logo = null;
        $this->logoRemoved   = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name_en', 'name_dv', 'description_en', 'description_dv',
            'meeting_schedule_en', 'meeting_schedule_dv',
            'patron_name', 'patron_role_en', 'patron_role_dv',
            'president_name', 'president_class',
            'logo', 'existing_logo', 'logoRemoved', 'editingId',
        ]);
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.student-life.clubs-index', [
            'clubs' => StudentLifeClub::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Student Life — Clubs']);
    }
}
