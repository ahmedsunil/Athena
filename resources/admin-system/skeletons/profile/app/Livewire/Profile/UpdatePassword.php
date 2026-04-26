<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UpdatePassword extends Component
{
    public string $currentPassword      = '';
    public string $password             = '';
    public string $passwordConfirmation = '';
    public string $status               = '';

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword'      => ['required', 'string', 'current_password'],
            'password'             => ['required', 'string', Password::defaults(), 'confirmed'],
            'passwordConfirmation' => ['required'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset('currentPassword', 'password', 'passwordConfirmation');
        $this->status = 'saved';
    }

    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
