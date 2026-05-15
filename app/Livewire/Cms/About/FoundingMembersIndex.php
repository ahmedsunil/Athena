<?php

namespace App\Livewire\Cms\About;

use App\Models\FoundingMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoundingMembersIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $subject_en = '';
    public string $subject_dv = '';
    public string $tribute_en = '';
    public string $tribute_dv = '';
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'subject_en' => ['nullable', 'string', 'max:255'],
            'subject_dv' => ['nullable', 'string', 'max:255'],
            'tribute_en' => ['nullable', 'string'],
            'tribute_dv' => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'subject'    => ['en' => $this->subject_en, 'dv' => $this->subject_dv],
            'tribute'    => ['en' => $this->tribute_en, 'dv' => $this->tribute_dv],
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/founding', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            FoundingMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Founding member updated.');
        } else {
            FoundingMember::create($data);
            $this->dispatch('toast', message: 'Founding member added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = FoundingMember::findOrFail($id);
        $this->editingId      = $item->id;
        $this->name           = $item->name;
        $this->subject_en     = $item->getTranslation('subject', 'en', false) ?? '';
        $this->subject_dv     = $item->getTranslation('subject', 'dv', false) ?? '';
        $this->tribute_en     = $item->getTranslation('tribute', 'en', false) ?? '';
        $this->tribute_dv     = $item->getTranslation('tribute', 'dv', false) ?? '';
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
        FoundingMember::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Founding member deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'subject_en', 'subject_dv', 'tribute_en', 'tribute_dv', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
    }

    public function render()
    {
        return view('livewire.cms.about.founding-members-index', [
            'members' => FoundingMember::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Founding Teachers']);
    }
}
