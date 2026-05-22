<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class ProfileSettings extends Component
{
    public string $activeTab = 'profile';

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['profile', 'password', 'two-factor'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function render()
    {
        return view('livewire.profile.profile-settings')
            ->layout('layouts.app', ['title' => 'Settings']);
    }
}
