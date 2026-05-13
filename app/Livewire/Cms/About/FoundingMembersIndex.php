<?php

namespace App\Livewire\Cms\About;

use App\Models\FoundingMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoundingMembersIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $subject = '';
    public string $tribute = '';
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'subject'    => ['nullable', 'string', 'max:255'],
            'tribute'    => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'subject'    => $this->subject,
            'tribute'    => $this->tribute,
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
        $this->subject        = $item->subject ?? '';
        $this->tribute        = $item->tribute ?? '';
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
        $this->reset(['name', 'subject', 'tribute', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
    }

    public function render()
    {
        return view('livewire.cms.about.founding-members-index', [
            'members' => FoundingMember::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Founding Teachers']);
    }
}
