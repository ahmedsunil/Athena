<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

class TwoFactorAuthentication extends Component
{
    public bool $showingQrCode = false;
    public bool $showingConfirmation = false;
    public bool $showingRecoveryCodes = false;
    public string $code = '';
    public string $error = '';

    public function mount(): void
    {
        $user = Auth::user()->fresh();

        // Resume an in-progress (unconfirmed) setup so the same QR is shown
        if ($user->two_factor_secret && ! $user->two_factor_confirmed_at) {
            $this->showingQrCode      = true;
            $this->showingConfirmation = true;
        }
    }

    public function enableTwoFactor(EnableTwoFactorAuthentication $enable): void
    {
        // Only generates a new secret if none exists; preserves the current one otherwise.
        // To get a fresh QR code the user must first cancel (which clears the secret).
        $enable(Auth::user()->fresh());

        $this->showingQrCode      = true;
        $this->showingConfirmation = true;
        $this->code  = '';
        $this->error = '';
    }

    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirm): void
    {
        $code = preg_replace('/\s+/', '', $this->code);

        try {
            $confirm(Auth::user()->fresh(), $code);
            $this->showingQrCode = false;
            $this->showingConfirmation = false;
            $this->showingRecoveryCodes = true;
            $this->code = '';
            $this->error = '';
            $this->dispatch('toast', message: 'Two-factor authentication enabled.');
        } catch (\Exception) {
            $this->error = 'The code you entered is invalid. Please try again.';
            $this->dispatch('toast', message: 'Invalid code. Please try again.', type: 'error');
        }
    }

    public function showRecoveryCodes(): void
    {
        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $generate(Auth::user()->fresh());
        $this->showingRecoveryCodes = true;
        $this->dispatch('toast', message: 'Recovery codes regenerated. Store them safely.');
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disable): void
    {
        $disable(Auth::user()->fresh());
        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;
        $this->dispatch('toast', message: 'Two-factor authentication disabled.');
    }

    public function render()
    {
        $user = Auth::user()->fresh();

        $setupKey = null;
        if ($this->showingQrCode && $user->two_factor_secret) {
            $setupKey = \Laravel\Fortify\Fortify::currentEncrypter()
                ->decrypt($user->two_factor_secret);
        }

        return view('livewire.profile.two-factor-authentication', [
            'enabled'        => ! is_null($user->two_factor_confirmed_at),
            'qrCodeSvg'      => $this->showingQrCode ? $user->twoFactorQrCodeSvg() : null,
            'recoveryCodes'  => $this->showingRecoveryCodes ? $user->recoveryCodes() : [],
            'setupKey'       => $setupKey,
        ]);
    }
}
