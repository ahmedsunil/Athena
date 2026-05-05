<?php

namespace App\Livewire\Cms\Icons;

use Livewire\Component;

class IconsIndex extends Component
{
    public function render()
    {
        $icons = collect(config('icons'))
            ->mapWithKeys(fn ($label, $key) => [
                $key => [
                    'label' => $label,
                    'lucide' => ltrim(strtolower(preg_replace(['/([A-Z])/', '/(\d+)/'], ['-$1', '-$1'], lcfirst($key))), '-'),
                ],
            ])
            ->all();

        return view('livewire.cms.icons.icons-index', compact('icons'))
            ->layout('layouts.app', ['title' => 'Icons']);
    }
}
