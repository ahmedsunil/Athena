<?php

namespace App\Livewire\Cms\About;

use App\Models\LeadershipMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeadershipIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $name_dv = '';
    public string $role_en = '';
    public string $role_dv = '';
    public string $bio_en = '';
    public string $bio_dv = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'name_dv'    => ['nullable', 'string', 'max:255'],
            'role_en'    => ['required', 'string', 'max:255'],
            'role_dv'    => ['nullable', 'string', 'max:255'],
            'bio_en'     => ['nullable', 'string'],
            'bio_dv'     => ['nullable', 'string'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'name_dv'    => $this->name_dv ?: null,
            'role'       => ['en' => $this->role_en, 'dv' => $this->role_dv],
            'bio'        => ['en' => $this->bio_en, 'dv' => $this->bio_dv],
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/leadership', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            LeadershipMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Member updated.');
        } else {
            LeadershipMember::create($data);
            $this->dispatch('toast', message: 'Member added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = LeadershipMember::findOrFail($id);
        $this->editingId      = $item->id;
        $this->name           = $item->name;
        $this->name_dv        = $item->name_dv ?? '';
        $this->role_en        = $item->getTranslation('role', 'en', false) ?? '';
        $this->role_dv        = $item->getTranslation('role', 'dv', false) ?? '';
        $this->bio_en         = $item->getTranslation('bio', 'en', false) ?? '';
        $this->bio_dv         = $item->getTranslation('bio', 'dv', false) ?? '';
        $this->is_active      = $item->is_active;
        $this->sort_order     = $item->sort_order;
        $this->existing_photo = $item->photo_path;
        $this->photoRemoved   = false;
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        LeadershipMember::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Member deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'name_dv', 'role_en', 'role_dv', 'bio_en', 'bio_dv', 'is_active', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.cms.about.leadership-index', [
            'members' => LeadershipMember::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Leadership Team']);
    }
}
