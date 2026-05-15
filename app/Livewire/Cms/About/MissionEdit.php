<?php

namespace App\Livewire\Cms\About;

use App\Models\Mission;
use Livewire\Component;

class MissionEdit extends Component
{
    public string $mission_en = '';
    public string $mission_dv = '';
    public string $vision_en = '';
    public string $vision_dv = '';

    public function mount(): void
    {
        $record = Mission::singleton();
        $this->mission_en = $record->getTranslation('mission', 'en', false) ?? '';
        $this->mission_dv = $record->getTranslation('mission', 'dv', false) ?? '';
        $this->vision_en  = $record->getTranslation('vision', 'en', false) ?? '';
        $this->vision_dv  = $record->getTranslation('vision', 'dv', false) ?? '';
    }

    protected function rules(): array
    {
        return [
            'mission_en' => ['nullable', 'string'],
            'mission_dv' => ['nullable', 'string'],
            'vision_en'  => ['nullable', 'string'],
            'vision_dv'  => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        Mission::singleton()->update([
            'mission' => ['en' => $this->mission_en, 'dv' => $this->mission_dv],
            'vision'  => ['en' => $this->vision_en, 'dv' => $this->vision_dv],
        ]);
        $this->dispatch('toast', message: 'Mission & Vision saved.');
    }

    public function render()
    {
        return view('livewire.cms.about.mission-edit')
            ->layout('layouts.app', ['title' => 'Mission & Vision']);
    }
}
