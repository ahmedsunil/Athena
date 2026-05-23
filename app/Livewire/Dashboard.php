<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'totalUsers'   => User::count(),
            'activeUsers'  => User::where('is_active', true)->count(),
            'adminUsers'   => User::role('admin')->count(),
            'newThisMonth' => User::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count(),
        ];

        return view('livewire.dashboard', compact('stats'))
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
