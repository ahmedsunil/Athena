<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeUniformBody;
use Livewire\Component;
use Livewire\WithFileUploads;

class UniformBodyForm extends Component
{
    use WithFileUploads;

    public ?int $bodyId = null;

    public string $name_en = '';
    public string $group_type = '';
    public string $colour = 'emerald';
    public string $description_en = '';
    public string $meeting_schedule_en = '';
    public string $patron_name = '';
    public string $patron_role_en = '';
    public string $leader_name = '';
    public string $leader_class = '';
    public $logo = null;
    public ?string $existing_logo = null;
    public bool $logoRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $bodyId = null): void
    {
        if ($bodyId) {
            $body = StudentLifeUniformBody::findOrFail($bodyId);
            $this->bodyId               = $body->id;
            $this->name_en              = $body->getTranslation('name', 'en', false) ?? '';
            $this->group_type           = $body->group_type;
            $this->colour               = $body->colour;
            $this->description_en       = $body->getTranslation('description', 'en', false) ?? '';
            $this->meeting_schedule_en  = $body->getTranslation('meeting_schedule', 'en', false) ?? '';
            $this->patron_name          = $body->patron_name ?? '';
            $this->patron_role_en       = $body->getTranslation('patron_role', 'en', false) ?? '';
            $this->leader_name          = $body->leader_name ?? '';
            $this->leader_class         = $body->leader_class ?? '';
            $this->existing_logo        = $body->logo_path;
            $this->sort_order           = $body->sort_order;
            $this->is_active            = $body->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'name_en'              => ['required', 'string', 'max:255'],
            'group_type'           => ['required', 'string', 'max:255'],
            'colour'               => ['required', 'in:rose,sky,emerald,amber,violet,teal'],
            'description_en'       => ['nullable', 'string'],
            'meeting_schedule_en'  => ['nullable', 'string', 'max:255'],
            'patron_name'          => ['nullable', 'string', 'max:255'],
            'patron_role_en'       => ['nullable', 'string', 'max:255'],
            'leader_name'          => ['nullable', 'string', 'max:255'],
            'leader_class'         => ['nullable', 'string', 'max:100'],
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
            'group_type'       => $this->group_type,
            'colour'           => $this->colour,
            'description'      => $this->description_en ? ['en' => $this->description_en] : null,
            'meeting_schedule' => $this->meeting_schedule_en ? ['en' => $this->meeting_schedule_en] : null,
            'patron_name'      => $this->patron_name ?: null,
            'patron_role'      => $this->patron_role_en ? ['en' => $this->patron_role_en] : null,
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

        if ($this->bodyId) {
            StudentLifeUniformBody::findOrFail($this->bodyId)->update($data);
            $this->dispatch('toast', message: 'Uniform body updated.');
        } else {
            StudentLifeUniformBody::create($data);
            $this->dispatch('toast', message: 'Uniform body added.');
        }

        $this->redirect(route('cms.uniform-bodies'), navigate: true);
    }

    public function removeLogo(): void
    {
        $this->logo          = null;
        $this->existing_logo = null;
        $this->logoRemoved   = true;
    }

    public function render()
    {
        return view('livewire.cms.student-life.uniform-body-form')
            ->layout('layouts.app', ['title' => $this->bodyId ? 'Edit Uniform Body' : 'New Uniform Body']);
    }
}
