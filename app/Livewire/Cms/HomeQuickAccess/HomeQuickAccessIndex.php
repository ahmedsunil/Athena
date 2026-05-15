<?php

namespace App\Livewire\Cms\HomeQuickAccess;

use App\Models\HomeQuickAccess;
use Livewire\Component;

class HomeQuickAccessIndex extends Component
{
    public string $icon_key = '';
    public string $title_en = '';
    public string $title_dv = '';
    public string $link_key = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'icon_key'   => ['required', 'string'],
            'title_en'   => ['required', 'string', 'max:255'],
            'title_dv'   => ['nullable', 'string', 'max:255'],
            'link_key'   => ['required', 'string'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'icon_key'   => $this->icon_key,
            'title'      => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'link_key'   => $this->link_key,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            HomeQuickAccess::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Quick access item updated.');
        } else {
            HomeQuickAccess::create($data);
            $this->dispatch('toast', message: 'Quick access item created.');
        }

        $this->reset(['icon_key', 'title_en', 'title_dv', 'link_key', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
    }

    public function edit(int $id): void
    {
        $item = HomeQuickAccess::findOrFail($id);
        $this->editingId  = $item->id;
        $this->icon_key   = $item->icon_key;
        $this->title_en   = $item->getTranslation('title', 'en', false) ?? '';
        $this->title_dv   = $item->getTranslation('title', 'dv', false) ?? '';
        $this->link_key   = $item->link_key;
        $this->is_active  = $item->is_active;
        $this->sort_order = $item->sort_order;
    }

    public function cancel(): void
    {
        $this->reset(['icon_key', 'title_en', 'title_dv', 'link_key', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
    }

    public function delete(int $id): void
    {
        HomeQuickAccess::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Quick access item deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home-quick-access.home-quick-access-index', [
            'items'    => HomeQuickAccess::orderBy('sort_order')->orderBy('id')->get(),
            'linkKeys' => config('links'),
            'iconKeys' => config('icons'),
        ])->layout('layouts.app', ['title' => 'Home Quick Access']);
    }
}
