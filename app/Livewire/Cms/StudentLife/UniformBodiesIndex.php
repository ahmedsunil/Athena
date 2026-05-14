<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeUniformBody;
use Livewire\Component;
use Livewire\WithFileUploads;

class UniformBodiesIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $group_type = '';
    public string $colour = 'emerald';
    public string $description = '';
    public string $meeting_schedule = '';
    public string $patron_name = '';
    public string $patron_role = '';
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
            'name'             => ['required', 'string', 'max:255'],
            'group_type'       => ['required', 'string', 'max:255'],
            'colour'           => ['required', 'in:rose,sky,emerald,amber,violet,teal'],
            'description'      => ['nullable', 'string'],
            'meeting_schedule' => ['nullable', 'string', 'max:255'],
            'patron_name'      => ['nullable', 'string', 'max:255'],
            'patron_role'      => ['nullable', 'string', 'max:255'],
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
            'name'             => $this->name,
            'group_type'       => $this->group_type,
            'colour'           => $this->colour,
            'description'      => $this->description ?: null,
            'meeting_schedule' => $this->meeting_schedule ?: null,
            'patron_name'      => $this->patron_name ?: null,
            'patron_role'      => $this->patron_role ?: null,
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
        $this->editingId        = $body->id;
        $this->name             = $body->name;
        $this->group_type       = $body->group_type;
        $this->colour           = $body->colour;
        $this->description      = $body->description ?? '';
        $this->meeting_schedule = $body->meeting_schedule ?? '';
        $this->patron_name      = $body->patron_name ?? '';
        $this->patron_role      = $body->patron_role ?? '';
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
            'name', 'group_type', 'description', 'meeting_schedule',
            'patron_name', 'patron_role', 'leader_name', 'leader_class',
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
