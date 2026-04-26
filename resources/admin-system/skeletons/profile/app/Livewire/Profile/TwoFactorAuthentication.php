<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Livewire\Component;

class TwoFactorAuthentication extends Component
{
    public bool   $showingQrCode        = false;
    public bool   $showingConfirmation  = false;
    public bool   $showingRecoveryCodes = false;
    public string $code                 = '';
    public string $confirmationCode     = '';

    public function enableTwoFactor(EnableTwoFactorAuthentication $enable): void
    {
        $enable(Auth::user());
        $this->showingQrCode       = true;
        $this->showingConfirmation = true;
    }

    public function confirmTwoFactor(TwoFactorAuthenticationProvider $provider): void
    {
        $user = Auth::user();

        if (empty($this->confirmationCode) || ! $provider->verify(decrypt($user->two_factor_secret), $this->confirmationCode)) {
            throw ValidationException::withMessages([
                'confirmationCode' => [__('The provided two factor authentication code was invalid.')],
            ]);
        }

        $user->forceFill(['two_factor_confirmed_at' => now()])->save();

        $this->showingQrCode        = false;
        $this->showingConfirmation  = false;
        $this->showingRecoveryCodes = true;
        $this->confirmationCode     = '';
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disable): void
    {
        $disable(Auth::user());
        $this->showingQrCode        = false;
        $this->showingConfirmation  = false;
        $this->showingRecoveryCodes = false;
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $generate(Auth::user());
        $this->showingRecoveryCodes = true;
    }

    public function showRecoveryCodes(): void
    {
        $this->showingRecoveryCodes = ! $this->showingRecoveryCodes;
    }

    public function render()
    {
        return view('livewire.profile.two-factor-authentication', [
            'user'    => Auth::user(),
            'enabled' => ! is_null(Auth::user()->two_factor_confirmed_at),
        ]);
    }
}
