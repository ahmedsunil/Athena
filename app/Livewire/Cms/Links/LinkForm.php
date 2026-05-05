<?php

namespace App\Livewire\Cms\Links;

use App\Models\Link;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class LinkForm extends Component
{
    #[Locked]
    public ?int $linkId = null;

    public string $name = '';
    public string $route = '';

    public function mount(?int $linkId = null): void
    {
        if ($linkId) {
            $link = Link::findOrFail($linkId);
            $this->linkId = $link->id;
            $this->name = $link->name;
            $this->route = $link->route;
        }
    }

    protected function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'route' => ['required', 'string', 'max:255', Rule::unique('links', 'route')->ignore($this->linkId)],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->linkId) {
            Link::findOrFail($this->linkId)->update($validated);
            $this->dispatch('toast', message: 'Link updated.');
        } else {
            Link::create($validated);
            $this->dispatch('toast', message: 'Link created.');
        }

        $this->redirect(route('cms.links.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.links.link-form')
            ->layout('layouts.app', ['title' => $this->linkId ? 'Edit Link' : 'New Link']);
    }
}
