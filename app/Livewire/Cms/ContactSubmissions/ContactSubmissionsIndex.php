<?php

namespace App\Livewire\Cms\ContactSubmissions;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class ContactSubmissionsIndex extends Component
{
    use WithPagination;

    public ?ContactSubmission $viewing = null;

    public function view(int $id): void
    {
        $submission = ContactSubmission::findOrFail($id);
        if (! $submission->is_read) {
            $submission->update(['is_read' => true]);
        }
        $this->viewing = $submission;
    }

    public function closeView(): void
    {
        $this->viewing = null;
    }

    public function delete(int $id): void
    {
        ContactSubmission::findOrFail($id)->delete();
        if ($this->viewing?->id === $id) {
            $this->viewing = null;
        }
        $this->dispatch('toast', message: 'Submission deleted.');
    }

    public function render()
    {
        return view('livewire.cms.contact-submissions.contact-submissions-index', [
            'submissions' => ContactSubmission::latest()->paginate(20),
            'unreadCount' => ContactSubmission::where('is_read', false)->count(),
        ])->layout('layouts.app', ['title' => 'Contact Submissions']);
    }
}
