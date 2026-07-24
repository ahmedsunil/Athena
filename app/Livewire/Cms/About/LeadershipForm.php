<?php

namespace App\Livewire\Cms\About;

use App\Models\LeadershipMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeadershipForm extends Component
{
    use WithFileUploads;

    public ?int $leadershipId = null;

    public string $name = '';
    public string $role_en = '';
    public string $bio_en = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;

    public function mount(?int $leadershipId = null): void
    {
        if ($leadershipId) {
            $item = LeadershipMember::findOrFail($leadershipId);
            $this->leadershipId    = $item->id;
            $this->name            = $item->name;
            $this->role_en         = $item->getTranslation('role', 'en', false) ?? '';
            $this->bio_en          = $item->getTranslation('bio', 'en', false) ?? '';
            $this->is_active       = $item->is_active;
            $this->sort_order      = $item->sort_order;
            $this->existing_photo  = $item->photo_path;
        }
    }

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'role_en'    => ['required', 'string', 'max:255'],
            'bio_en'     => ['nullable', 'string'],
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
            'role'       => ['en' => $this->role_en],
            'bio'        => ['en' => $this->bio_en],
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/leadership', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->leadershipId) {
            LeadershipMember::findOrFail($this->leadershipId)->update($data);
            $this->dispatch('toast', message: 'Member updated.');
        } else {
            LeadershipMember::create($data);
            $this->dispatch('toast', message: 'Member added.');
        }

        $this->redirect(route('cms.about.leadership'), navigate: true);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function render()
    {
        $title = $this->leadershipId ? 'Edit Leadership Member' : 'New Leadership Member';
        return view('livewire.cms.about.leadership-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
