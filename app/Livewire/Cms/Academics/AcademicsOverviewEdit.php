<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicsOverview;
use Livewire\Component;

class AcademicsOverviewEdit extends Component
{
    public string $text = '';
    public string $curriculum = '';

    public function mount(): void
    {
        $record = AcademicsOverview::singleton();
        $this->text       = $record->text ?? '';
        $this->curriculum = $record->curriculum ?? '';
    }

    protected function rules(): array
    {
        return [
            'text'       => ['nullable', 'string'],
            'curriculum' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        AcademicsOverview::singleton()->update([
            'text'       => $this->text ?: null,
            'curriculum' => $this->curriculum ?: null,
        ]);
        $this->dispatch('toast', message: 'Overview saved.');
    }

    public function render()
    {
        return view('livewire.cms.academics.overview-edit')
            ->layout('layouts.app', ['title' => 'Academics — Overview']);
    }
}
