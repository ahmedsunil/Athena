@php
    $routeLabels = [
        'cms.school-profile' => 'Home',
        'profile'            => 'Profile',
    ];

    $currentRoute = request()->route()?->getName() ?? '';
    $breadcrumb   = 'Admin';

    foreach ($routeLabels as $prefix => $label) {
        if (str_starts_with($currentRoute, $prefix)) {
            $breadcrumb = $label;
            break;
        }
    }
@endphp

<div class="hidden h-12 shrink-0 items-center border-b border-zinc-200 bg-white px-6 md:flex">
    <span class="admin-label-muted">{{ $breadcrumb }}</span>
</div>
