<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeClub;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClubForm extends Component
{
    use WithFileUploads;

    public ?int $clubId = null;

    public string $name_en = '';
    public string $description_en = '';
    public string $meeting_schedule_en = '';
    public string $patron_name = '';
    public string $patron_role_en = '';
    public string $president_name = '';
    public string $president_class = '';
    public $logo = null;
    public ?string $existing_logo = null;
    public bool $logoRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $clubId = null): void
    {
        if ($clubId) {
            $club = StudentLifeClub::findOrFail($clubId);
            $this->clubId               = $club->id;
            $this->name_en              = $club->getTranslation('name', 'en', false) ?? '';
            $this->description_en       = $club->getTranslation('description', 'en', false) ?? '';
            $this->meeting_schedule_en  = $club->getTranslation('meeting_schedule', 'en', false) ?? '';
            $this->patron_name          = $club->patron_name ?? '';
            $this->patron_role_en       = $club->getTranslation('patron_role', 'en', false) ?? '';
            $this->president_name       = $club->president_name ?? '';
            $this->president_class      = $club->president_class ?? '';
            $this->existing_logo        = $club->logo_path;
            $this->sort_order           = $club->sort_order;
            $this->is_active            = $club->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'name_en'              => ['required', 'string', 'max:255'],
            'description_en'       => ['nullable', 'string'],
            'meeting_schedule_en'  => ['nullable', 'string', 'max:255'],
            'patron_name'          => ['nullable', 'string', 'max:255'],
            'patron_role_en'       => ['nullable', 'string', 'max:255'],
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
            'name'             => ['en' => $this->name_en],
            'description'      => $this->description_en ? ['en' => $this->description_en] : null,
            'meeting_schedule' => $this->meeting_schedule_en ? ['en' => $this->meeting_schedule_en] : null,
            'patron_name'      => $this->patron_name ?: null,
            'patron_role'      => $this->patron_role_en ? ['en' => $this->patron_role_en] : null,
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

        if ($this->clubId) {
            StudentLifeClub::findOrFail($this->clubId)->update($data);
            $this->dispatch('toast', message: 'Club updated.');
        } else {
            StudentLifeClub::create($data);
            $this->dispatch('toast', message: 'Club added.');
        }

        $this->redirect(route('cms.clubs'), navigate: true);
    }

    public function removeLogo(): void
    {
        $this->logo          = null;
        $this->existing_logo = null;
        $this->logoRemoved   = true;
    }

    public function render()
    {
        return view('livewire.cms.student-life.club-form')
            ->layout('layouts.app', ['title' => $this->clubId ? 'Edit Club' : 'New Club']);
    }
}
