<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Events\RecoveryCodeReplaced;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public string $code         = '';
    public string $recoveryCode = '';
    public bool   $useRecovery  = false;

    public function authenticate(Request $request, TwoFactorAuthenticationProvider $provider): void
    {
        $user = $request->session()->get('login.id')
            ? User::find($request->session()->get('login.id'))
            : null;

        if (! $user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if ($this->useRecovery) {
            $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            $index = array_search($this->recoveryCode, $codes);

            if ($index === false) {
                throw ValidationException::withMessages([
                    'recoveryCode' => [__('The provided two factor recovery code was invalid.')],
                ]);
            }

            $codes[$index] = Str::random(10) . '-' . Str::random(10);
            $user->forceFill(['two_factor_recovery_codes' => encrypt(json_encode($codes))])->save();
            event(new RecoveryCodeReplaced($user, $this->recoveryCode));
        } else {
            if (! $provider->verify(decrypt($user->two_factor_secret), $this->code)) {
                throw ValidationException::withMessages([
                    'code' => [__('The provided two factor authentication code was invalid.')],
                ]);
            }
        }

        Auth::login($user, $request->session()->get('login.remember'));
        $request->session()->forget(['login.id', 'login.remember']);
        $request->session()->regenerate();

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
}
