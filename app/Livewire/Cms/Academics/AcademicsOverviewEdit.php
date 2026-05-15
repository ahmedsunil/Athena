<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicsOverview;
use Livewire\Component;

class AcademicsOverviewEdit extends Component
{
    public string $text_en = '';
    public string $text_dv = '';
    public string $curriculum_en = '';
    public string $curriculum_dv = '';

    public function mount(): void
    {
        $record = AcademicsOverview::singleton();
        $this->text_en       = $record->getTranslation('text', 'en', false) ?? '';
        $this->text_dv       = $record->getTranslation('text', 'dv', false) ?? '';
        $this->curriculum_en = $record->getTranslation('curriculum', 'en', false) ?? '';
        $this->curriculum_dv = $record->getTranslation('curriculum', 'dv', false) ?? '';
    }

    protected function rules(): array
    {
        return [
            'text_en'       => ['nullable', 'string'],
            'text_dv'       => ['nullable', 'string'],
            'curriculum_en' => ['nullable', 'string', 'max:255'],
            'curriculum_dv' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        AcademicsOverview::singleton()->update([
            'text'       => ($this->text_en || $this->text_dv)
                             ? ['en' => $this->text_en, 'dv' => $this->text_dv]
                             : null,
            'curriculum' => ($this->curriculum_en || $this->curriculum_dv)
                             ? ['en' => $this->curriculum_en, 'dv' => $this->curriculum_dv]
                             : null,
        ]);
        $this->dispatch('toast', message: 'Overview saved.');
    }

    public function render()
    {
        return view('livewire.cms.academics.overview-edit')
            ->layout('layouts.app', ['title' => 'Academics — Overview']);
    }
}
