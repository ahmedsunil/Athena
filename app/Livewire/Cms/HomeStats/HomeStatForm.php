<?php

namespace App\Livewire\Cms\HomeStats;

use App\Models\HomeStat;
use Livewire\Component;

class HomeStatForm extends Component
{
    public ?int $itemId = null;

    public string $title_en = '';
    public string $value = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    public function mount(?int $itemId = null): void
    {
        $this->itemId = $itemId;

        if ($itemId) {
            $stat = HomeStat::findOrFail($itemId);
            $this->title_en   = $stat->getTranslation('title', 'en', false) ?? '';
            $this->value      = $stat->value;
            $this->is_active  = $stat->is_active;
            $this->sort_order = $stat->sort_order;
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'   => ['required', 'string', 'max:255'],
            'value'      => ['required', 'string', 'max:100'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => ['en' => $this->title_en],
            'value'      => $this->value,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->itemId) {
            HomeStat::findOrFail($this->itemId)->update($data);
            $this->dispatch('toast', message: 'Stat updated.');
        } else {
            HomeStat::create($data);
            $this->dispatch('toast', message: 'Stat created.');
        }

        $this->redirect(route('cms.home.stats'), navigate: true);
    }

    public function render()
    {
        $title = $this->itemId ? 'Edit Stat' : 'New Stat';
        return view('livewire.cms.home-stats.home-stats-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
