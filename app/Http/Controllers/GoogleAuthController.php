<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $this->configureSocialite();

        return Socialite::driver('google')->redirect();
    }

    private function configureSocialite(): void
    {
        $encryptedSecret = Setting::get('google_client_secret', '');
        $secret = '';
        if ($encryptedSecret) {
            try {
                $secret = Crypt::decryptString($encryptedSecret);
            } catch (Exception) {
                $secret = '';
            }
        }

        config([
            'services.google.client_id' => Setting::get('google_client_id'),
            'services.google.client_secret' => $secret,
            'services.google.redirect' => Setting::get('google_redirect_uri', url('/auth/google/callback')),
        ]);
    }

    public function callback()
    {
        $this->configureSocialite();

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            $user->syncRoles(['user']);
        } else {
            if (! $user->is_active) {
                return redirect()->route('login')->with('error', 'Your account has been deactivated.');
            }
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user, true);
        Session::regenerate();

        session()->flash('toast', 'Welcome, '.$user->name.'!');
        session()->flash('toast_type', 'success');

        return redirect()->intended(route('dashboard'));
    }
}
