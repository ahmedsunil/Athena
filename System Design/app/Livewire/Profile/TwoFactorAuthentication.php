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

    public function enableTwoFactor(EnableTwoFactorAuthentication $enable): void
    {
        $enable(Auth::user());

        $this->showingQrCode = true;
        $this->showingConfirmation = true;
    }

    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirm): void
    {
        try {
            $confirm(Auth::user(), $this->code);
            $this->showingQrCode = false;
            $this->showingConfirmation = false;
            $this->showingRecoveryCodes = true;
            $this->code = '';
            $this->error = '';
        } catch (\Exception) {
            $this->error = 'The code you entered is invalid. Please try again.';
        }
    }

    public function showRecoveryCodes(): void
    {
        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $generate(Auth::user());
        $this->showingRecoveryCodes = true;
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disable): void
    {
        $disable(Auth::user());
        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;
    }

    public function render()
    {
        $user = Auth::user()->fresh();

        return view('livewire.profile.two-factor-authentication', [
            'enabled'        => ! is_null($user->two_factor_confirmed_at),
            'qrCodeSvg'      => $this->showingQrCode ? $user->twoFactorQrCodeSvg() : null,
            'recoveryCodes'  => $this->showingRecoveryCodes ? $user->recoveryCodes() : [],
            'setupKey'       => $this->showingQrCode ? $user->two_factor_secret : null,
        ]);
    }
}
