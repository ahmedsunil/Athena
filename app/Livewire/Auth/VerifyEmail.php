<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public string $status = '';

    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirect(route('cms.school-profile'));
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        $this->status = 'A new verification link has been sent to your email address.';
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
