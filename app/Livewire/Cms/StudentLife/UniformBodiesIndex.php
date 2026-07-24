<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeUniformBody;
use Livewire\Component;
use Livewire\WithPagination;

class UniformBodiesIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        StudentLifeUniformBody::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Uniform body deleted.');
    }

    public function render()
    {
        return view('livewire.cms.student-life.uniform-bodies-index', [
            'bodies' => StudentLifeUniformBody::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Student Life — Uniform Bodies']);
    }
}
