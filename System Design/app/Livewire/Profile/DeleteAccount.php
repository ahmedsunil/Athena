<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class DeleteAccount extends Component
{
    public bool $confirming = false;
    public string $password = '';
    public string $error = '';

    public function confirmDeletion(): void
    {
        $this->confirming = true;
    }

    public function deleteAccount(): void
    {
        if (! Hash::check($this->password, Auth::user()->password)) {
            $this->error = 'The password you entered is incorrect.';
            return;
        }

        $user = Auth::user();
        Auth::logout();

        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.delete-account');
    }
}
