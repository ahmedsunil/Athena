<?php

namespace App\Livewire\Cms\About;

use App\Models\LeadershipMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeadershipIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $role = '';
    public string $bio = '';
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
            'role'       => ['required', 'string', 'max:255'],
            'bio'        => ['nullable', 'string'],
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
            'role'       => $this->role,
            'bio'        => $this->bio,
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
        $this->role           = $item->role;
        $this->bio            = $item->bio ?? '';
        $this->is_active      = $item->is_active;
        $this->sort_order     = $item->sort_order;
        $this->existing_photo = $item->photo_path;
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
        $this->reset(['name', 'role', 'bio', 'is_active', 'sort_order',
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
