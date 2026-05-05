<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserShow extends Component
{
    #[Locked]
    public int $userId;

    public function mount(int $userId): void
    {
        User::findOrFail($userId);

        $this->userId = $userId;
    }

    public function render()
    {
        $user = User::with('roles')->findOrFail($this->userId);

        return view('livewire.users.user-show', compact('user'))
            ->layout('layouts.app', ['title' => 'View User']);
    }
}
