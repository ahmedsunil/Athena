<?php

namespace App\Livewire\Cms\About;

use App\Models\Mission;
use Livewire\Component;

class MissionEdit extends Component
{
    public string $mission = '';
    public string $vision = '';

    public function mount(): void
    {
        $record = Mission::singleton();
        $this->mission = $record->mission ?? '';
        $this->vision  = $record->vision ?? '';
    }

    protected function rules(): array
    {
        return [
            'mission' => ['nullable', 'string'],
            'vision'  => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        Mission::singleton()->update([
            'mission' => $this->mission,
            'vision'  => $this->vision,
        ]);
        $this->dispatch('toast', message: 'Mission & Vision saved.');
    }

    public function render()
    {
        return view('livewire.cms.about.mission-edit')
            ->layout('layouts.app', ['title' => 'Mission & Vision']);
    }
}
