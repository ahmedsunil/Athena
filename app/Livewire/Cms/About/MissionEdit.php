<?php

namespace App\Livewire\Cms\About;

use App\Models\Mission;
use Livewire\Component;

class MissionEdit extends Component
{
    public string $mission_en = '';
    public string $vision_en = '';

    public function mount(): void
    {
        $record = Mission::singleton();
        $this->mission_en = $record->getTranslation('mission', 'en', false) ?? '';
        $this->vision_en  = $record->getTranslation('vision', 'en', false) ?? '';
    }

    protected function rules(): array
    {
        return [
            'mission_en' => ['nullable', 'string'],
            'vision_en'  => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        Mission::singleton()->update([
            'mission' => ['en' => $this->mission_en],
            'vision'  => ['en' => $this->vision_en],
        ]);
        $this->dispatch('toast', message: 'Mission & Vision saved.');
    }

    public function render()
    {
        return view('livewire.cms.about.mission-edit')
            ->layout('layouts.app', ['title' => 'Mission & Vision']);
    }
}
