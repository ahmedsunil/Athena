<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Livewire\Component;

class BrowserSessions extends Component
{
    public bool $confirmingLogout = false;
    public string $password = '';
    public string $error = '';

    public function confirmLogoutOtherBrowserSessions(): void
    {
        $this->confirmingLogout = true;
    }

    public function logoutOtherBrowserSessions(): void
    {
        if (! Hash::check($this->password, Auth::user()->password)) {
            $this->error = 'The password you entered is incorrect.';
            return;
        }

        Auth::logoutOtherDevices($this->password);

        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();

        $this->confirmingLogout = false;
        $this->password = '';
        $this->error = '';
    }

    public function getSessions(): array
    {
        if (config('session.driver') !== 'database') {
            return [];
        }

        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $agent = $this->createAgent($session);

                return (object) [
                    'agent'         => (object) [
                        'isDesktop' => $agent->isDesktop(),
                        'platform'  => $agent->platform(),
                        'browser'   => $agent->browser(),
                    ],
                    'ipAddress'     => $session->ip_address,
                    'isCurrentDevice' => $session->id === session()->getId(),
                    'lastActive'    => now()->diffForHumans(now()->setTimestamp($session->last_activity)),
                ];
            })
            ->toArray();
    }

    protected function createAgent(object $session): Agent
    {
        $agent = new Agent();
        $agent->setUserAgent($session->user_agent ?? '');

        return $agent;
    }

    public function render()
    {
        return view('livewire.profile.browser-sessions', [
            'sessions' => $this->getSessions(),
        ]);
    }
}
