<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicsOverview;
use Livewire\Component;

class AcademicsOverviewEdit extends Component
{
    public string $text_en = '';
    public string $curriculum_en = '';

    public function mount(): void
    {
        $record = AcademicsOverview::singleton();
        $this->text_en       = $record->getTranslation('text', 'en', false) ?? '';
        $this->curriculum_en = $record->getTranslation('curriculum', 'en', false) ?? '';
    }

    protected function rules(): array
    {
        return [
            'text_en'       => ['nullable', 'string'],
            'curriculum_en' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        AcademicsOverview::singleton()->update([
            'text'       => $this->text_en ? ['en' => $this->text_en] : null,
            'curriculum' => $this->curriculum_en ? ['en' => $this->curriculum_en] : null,
        ]);
        $this->dispatch('toast', message: 'Overview saved.');
    }

    public function render()
    {
        return view('livewire.cms.academics.overview-edit')
            ->layout('layouts.app', ['title' => 'Academics — Overview']);
    }
}
