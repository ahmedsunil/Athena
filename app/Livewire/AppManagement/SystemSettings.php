<?php

namespace App\Livewire\AppManagement;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class SystemSettings extends Component
{
    // Google OAuth
    public bool $googleLoginEnabled = false;
    public string $googleClientId = '';
    public string $googleClientSecret = '';
    public string $googleRedirectUri = '';

    public function mount(): void
    {
        $this->googleLoginEnabled = (bool) Setting::get('google_login_enabled', false);
        $this->googleClientId     = (string) Setting::get('google_client_id', '');
        $this->googleRedirectUri  = (string) Setting::get('google_redirect_uri', url('/auth/google/callback'));

        $encrypted = Setting::get('google_client_secret', '');
        $this->googleClientSecret = $encrypted ? $this->decrypt($encrypted) : '';
    }

    public function saveGoogle(): void
    {
        $this->validate([
            'googleClientId'     => $this->googleLoginEnabled ? ['required', 'string'] : ['nullable', 'string'],
            'googleClientSecret' => $this->googleLoginEnabled ? ['required', 'string'] : ['nullable', 'string'],
            'googleRedirectUri'  => ['required', 'url'],
        ], [], [
            'googleClientId'     => 'Client ID',
            'googleClientSecret' => 'Client secret',
            'googleRedirectUri'  => 'Redirect URI',
        ]);

        Setting::setMany([
            'google_login_enabled' => $this->googleLoginEnabled ? '1' : '0',
            'google_client_id'     => $this->googleClientId,
            'google_client_secret' => $this->googleClientSecret ? Crypt::encryptString($this->googleClientSecret) : '',
            'google_redirect_uri'  => $this->googleRedirectUri,
        ]);

        $this->dispatch('toast', message: 'Google login settings saved.');
    }

    private function decrypt(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception) {
            return '';
        }
    }

    public function render()
    {
        return view('livewire.app-management.system-settings')
            ->layout('layouts.app', ['title' => 'System Settings']);
    }
}
