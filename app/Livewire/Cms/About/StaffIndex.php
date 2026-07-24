<?php

namespace App\Livewire\Cms\About;

use App\Models\StaffMember;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class StaffIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        $item = StaffMember::findOrFail($id);
        if ($item->photo_path) {
            Storage::disk('public')->delete($item->photo_path);
        }
        $item->delete();
        $this->dispatch('toast', message: 'Staff member deleted.');
    }

    public function render()
    {
        return view('livewire.cms.about.staff-index', [
            'staffMembers' => StaffMember::orderByRaw("FIELD(section,'senior_management','academic','administrative')")
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Team']);
    }
}
