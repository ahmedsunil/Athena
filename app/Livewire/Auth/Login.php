<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    public function login(): void
    {
        $this->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($this->ensureIsNotRateLimited()) {
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            $this->dispatch('toast', message: 'These credentials do not match our records.', type: 'error');
            $this->addError('email', __('auth.failed'));
            return;
        }

        if (! $user->is_active) {
            $this->addError('email', 'This account has been deactivated.');
            return;
        }

        RateLimiter::clear($this->throttleKey());

        // If 2FA is confirmed, send to the challenge screen instead of logging in
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            session()->put('login.id', $user->getKey());
            session()->put('login.remember', $this->remember);
            $this->redirect(route('two-factor.login'), navigate: true);
            return;
        }

        Auth::login($user, $this->remember);
        Session::regenerate();

        session()->flash('toast', 'Welcome back, ' . $user->name . '!');
        session()->flash('toast_type', 'success');

        $this->redirectIntended(default: route('cms.school-profile'));
    }

    protected function ensureIsNotRateLimited(): bool
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return false;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $message = __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]);

        $this->dispatch('toast', message: 'Too many login attempts. Try again in ' . ceil($seconds / 60) . ' min.', type: 'error');
        $this->addError('email', $message);

        return true;
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
