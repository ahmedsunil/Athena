<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeUniformBody;
use Livewire\Component;
use Livewire\WithFileUploads;

class UniformBodiesIndex extends Component
{
    use WithFileUploads;

    public string $name_en = '';
    public string $name_dv = '';
    public string $group_type = '';
    public string $colour = 'emerald';
    public string $description_en = '';
    public string $description_dv = '';
    public string $meeting_schedule_en = '';
    public string $meeting_schedule_dv = '';
    public string $patron_name = '';
    public string $patron_role_en = '';
    public string $patron_role_dv = '';
    public string $leader_name = '';
    public string $leader_class = '';
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
            'group_type'           => ['required', 'string', 'max:255'],
            'colour'               => ['required', 'in:rose,sky,emerald,amber,violet,teal'],
            'description_en'       => ['nullable', 'string'],
            'description_dv'       => ['nullable', 'string'],
            'meeting_schedule_en'  => ['nullable', 'string', 'max:255'],
            'meeting_schedule_dv'  => ['nullable', 'string', 'max:255'],
            'patron_name'          => ['nullable', 'string', 'max:255'],
            'patron_role_en'       => ['nullable', 'string', 'max:255'],
            'patron_role_dv'       => ['nullable', 'string', 'max:255'],
            'leader_name'      => ['nullable', 'string', 'max:255'],
            'leader_class'     => ['nullable', 'string', 'max:100'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'sort_order'       => ['integer', 'min:0'],
            'is_active'        => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'             => ['en' => $this->name_en, 'dv' => $this->name_dv],
            'group_type'       => $this->group_type,
            'colour'           => $this->colour,
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
            'leader_name'      => $this->leader_name ?: null,
            'leader_class'     => $this->leader_class ?: null,
            'sort_order'       => $this->sort_order,
            'is_active'        => $this->is_active,
        ];

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('student-life/uniform-bodies', 'public');
        } elseif ($this->logoRemoved) {
            $data['logo_path'] = null;
        }

        if ($this->editingId) {
            StudentLifeUniformBody::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Uniform body updated.');
        } else {
            StudentLifeUniformBody::create($data);
            $this->dispatch('toast', message: 'Uniform body added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $body = StudentLifeUniformBody::findOrFail($id);
        $this->editingId            = $body->id;
        $this->name_en              = $body->getTranslation('name', 'en', false) ?? '';
        $this->name_dv              = $body->getTranslation('name', 'dv', false) ?? '';
        $this->group_type           = $body->group_type;
        $this->colour               = $body->colour;
        $this->description_en       = $body->getTranslation('description', 'en', false) ?? '';
        $this->description_dv       = $body->getTranslation('description', 'dv', false) ?? '';
        $this->meeting_schedule_en  = $body->getTranslation('meeting_schedule', 'en', false) ?? '';
        $this->meeting_schedule_dv  = $body->getTranslation('meeting_schedule', 'dv', false) ?? '';
        $this->patron_name          = $body->patron_name ?? '';
        $this->patron_role_en       = $body->getTranslation('patron_role', 'en', false) ?? '';
        $this->patron_role_dv       = $body->getTranslation('patron_role', 'dv', false) ?? '';
        $this->leader_name      = $body->leader_name ?? '';
        $this->leader_class     = $body->leader_class ?? '';
        $this->existing_logo    = $body->logo_path;
        $this->logoRemoved      = false;
        $this->sort_order       = $body->sort_order;
        $this->is_active        = $body->is_active;
    }

    public function delete(int $id): void
    {
        StudentLifeUniformBody::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Uniform body deleted.');
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
            'name_en', 'name_dv', 'group_type', 'description_en', 'description_dv',
            'meeting_schedule_en', 'meeting_schedule_dv',
            'patron_name', 'patron_role_en', 'patron_role_dv', 'leader_name', 'leader_class',
            'logo', 'existing_logo', 'logoRemoved', 'editingId',
        ]);
        $this->colour     = 'emerald';
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.student-life.uniform-bodies-index', [
            'bodies' => StudentLifeUniformBody::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Student Life — Uniform Bodies']);
    }
}
