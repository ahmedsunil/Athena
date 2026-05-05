<?php

namespace App\Livewire\Cms\HomeStats;

use App\Models\HomeStat;
use Livewire\Component;

class HomeStatsIndex extends Component
{
    public string $title = '';
    public string $value = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:100'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => $this->title,
            'value'      => $this->value,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            HomeStat::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Stat updated.');
        } else {
            HomeStat::create($data);
            $this->dispatch('toast', message: 'Stat created.');
        }

        $this->reset(['title', 'value', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
    }

    public function edit(int $id): void
    {
        $stat = HomeStat::findOrFail($id);
        $this->editingId  = $stat->id;
        $this->title      = $stat->title;
        $this->value      = $stat->value;
        $this->is_active  = $stat->is_active;
        $this->sort_order = $stat->sort_order;
    }

    public function cancel(): void
    {
        $this->reset(['title', 'value', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
    }

    public function delete(int $id): void
    {
        HomeStat::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Stat deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home-stats.home-stats-index', [
            'stats' => HomeStat::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Home Stats']);
    }
}
