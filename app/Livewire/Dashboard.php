<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class Dashboard extends Component
{
    public string $usersFrom;

    public string $usersTo;

    public string $rolesFrom;

    public string $rolesTo;

    public string $lineFrom;

    public string $lineTo;

    public string $donutFrom;

    public string $donutTo;

    public string $horizontalFrom;

    public string $horizontalTo;

    public string $areaFrom;

    public string $areaTo;

    public string $stackedFrom;

    public string $stackedTo;

    public function mount(): void
    {
        $today = now();

        $this->usersFrom = $today->copy()->subMonths(5)->startOfMonth()->toDateString();
        $this->usersTo = $today->toDateString();
        $this->rolesFrom = $today->copy()->startOfYear()->toDateString();
        $this->rolesTo = $today->toDateString();
        $this->lineFrom = $today->copy()->subDays(6)->toDateString();
        $this->lineTo = $today->toDateString();
        $this->donutFrom = $today->copy()->startOfMonth()->toDateString();
        $this->donutTo = $today->toDateString();
        $this->horizontalFrom = $today->copy()->startOfMonth()->toDateString();
        $this->horizontalTo = $today->toDateString();
        $this->areaFrom = $today->copy()->subMonth()->toDateString();
        $this->areaTo = $today->toDateString();
        $this->stackedFrom = $today->copy()->subMonths(2)->startOfMonth()->toDateString();
        $this->stackedTo = $today->toDateString();
    }

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

        $recentUsers = User::with('roles')->latest()->limit(5)->get();
        $pendingUsers = User::where('is_active', false)->count();
        $conversionRate = $stats['totalUsers'] > 0
            ? round(($stats['activeUsers'] / $stats['totalUsers']) * 100)
            : 0;

        $usersByMonth = $this->monthlyUserSeries($this->usersFrom, $this->usersTo);
        $lineGraph = $this->dailyUserSeries($this->lineFrom, $this->lineTo);
        $areaGraph = $this->dailyUserSeries($this->areaFrom, $this->areaTo);
        $donutGraph = $this->statusSegments($this->donutFrom, $this->donutTo);
        $topRoles = $this->roleBreakdown($this->rolesFrom, $this->rolesTo);
        $horizontalGraph = $this->componentUsage($this->horizontalFrom, $this->horizontalTo);
        $stackedGraph = $this->stackedUserSeries($this->stackedFrom, $this->stackedTo);

        return view('livewire.dashboard', compact(
            'areaGraph',
            'conversionRate',
            'donutGraph',
            'horizontalGraph',
            'lineGraph',
            'pendingUsers',
            'recentUsers',
            'stackedGraph',
            'stats',
            'topRoles',
            'usersByMonth',
        ))->layout('layouts.app', ['title' => 'Dashboard']);
    }

    private function dateBounds(string $from, string $to): array
    {
        $start = Carbon::parse($from ?: now()->toDateString())->startOfDay();
        $end = Carbon::parse($to ?: now()->toDateString())->endOfDay();

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    private function monthlyUserSeries(string $from, string $to): array
    {
        [$start, $end] = $this->dateBounds($from, $to);
        $cursor = $start->copy()->startOfMonth();
        $items = [];

        while ($cursor->lessThanOrEqualTo($end) && count($items) < 12) {
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();
            $total = User::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $items[] = [
                'label' => $cursor->format('M'),
                'total' => $total,
            ];

            $cursor->addMonth();
        }

        return $this->withPercentages($items);
    }

    private function dailyUserSeries(string $from, string $to): array
    {
        [$start, $end] = $this->dateBounds($from, $to);
        $period = CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay());
        $items = [];

        foreach ($period as $date) {
            if (count($items) >= 14) {
                break;
            }

            $total = User::whereDate('created_at', $date)->count();
            $items[] = [
                'label' => $date->format('D'),
                'total' => $total,
            ];
        }

        return $this->withPercentages($items);
    }

    private function roleBreakdown(string $from, string $to): array
    {
        [$start, $end] = $this->dateBounds($from, $to);

        $roles = \Spatie\Permission\Models\Role::withCount([
            'users' => fn ($q) => $q->whereBetween('users.created_at', [$start, $end]),
        ])
            ->orderByDesc('users_count')
            ->get()
            ->map(fn ($role) => [
                'label' => ucfirst($role->name),
                'total' => (int) $role->users_count,
            ])
            ->filter(fn ($r) => $r['total'] > 0)
            ->values()
            ->all();

        return $this->withPercentages($roles ?: [
            ['label' => 'Admin', 'total' => 0],
            ['label' => 'User', 'total' => 0],
        ]);
    }

    private function statusSegments(string $from, string $to): array
    {
        [$start, $end] = $this->dateBounds($from, $to);
        $base = User::whereBetween('created_at', [$start, $end]);
        $active = (clone $base)->where('is_active', true)->count();
        $inactive = (clone $base)->where('is_active', false)->count();
        $unverified = (clone $base)->whereNull('email_verified_at')->count();
        $total = max($active + $inactive + $unverified, 1);

        return [
            ['label' => 'Active', 'total' => $active, 'percent' => round(($active / $total) * 100), 'class' => 'bg-zinc-950'],
            ['label' => 'Pending', 'total' => $unverified, 'percent' => round(($unverified / $total) * 100), 'class' => 'bg-amber-500'],
            ['label' => 'Inactive', 'total' => $inactive, 'percent' => round(($inactive / $total) * 100), 'class' => 'bg-red-500'],
        ];
    }

    private function componentUsage(string $from, string $to): array
    {
        [$start, $end] = $this->dateBounds($from, $to);
        $created = User::whereBetween('created_at', [$start, $end])->count();
        $active = User::whereBetween('created_at', [$start, $end])->where('is_active', true)->count();
        $admins = User::whereBetween('created_at', [$start, $end])->role('admin')->count();

        return $this->withPercentages([
            ['label' => 'Forms', 'total' => $created],
            ['label' => 'Tables', 'total' => $active],
            ['label' => 'Dialogs', 'total' => $admins],
        ]);
    }

    private function stackedUserSeries(string $from, string $to): array
    {
        [$start, $end] = $this->dateBounds($from, $to);
        $cursor = $start->copy()->startOfMonth();
        $items = [];

        while ($cursor->lessThanOrEqualTo($end) && count($items) < 6) {
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();
            $base = User::whereBetween('created_at', [$monthStart, $monthEnd]);
            $active = (clone $base)->where('is_active', true)->count();
            $inactive = (clone $base)->where('is_active', false)->count();
            $pending = (clone $base)->whereNull('email_verified_at')->count();
            $total = max($active + $inactive + $pending, 1);

            $items[] = [
                'label' => $cursor->format('F'),
                'active' => round(($active / $total) * 100),
                'pending' => round(($pending / $total) * 100),
                'inactive' => round(($inactive / $total) * 100),
            ];

            $cursor->addMonth();
        }

        return $items;
    }

    private function withPercentages(array $items): array
    {
        $max = max(array_column($items, 'total') ?: [0]) ?: 1;

        return array_map(fn ($item) => [
            ...$item,
            'percent' => max(round(($item['total'] / $max) * 100), $item['total'] > 0 ? 4 : 0),
        ], $items);
    }
}
