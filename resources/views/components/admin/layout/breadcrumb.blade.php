{{--
    Layout: Breadcrumb Bar (Desktop only)
    --------------------------------------
    Thin bar shown below the topbar on md+ screens.
    Automatically resolves the current route name to a human label.

    Usage:
        <x-admin.layout.breadcrumb />

    Customise:
        - Add more entries to $routeLabels to match your routes.
--}}
@php
    $routeLabels = [
        'dashboard'  => 'Dashboard',
        // Add your routes here:
        // 'orders'     => 'Orders',
        // 'customers'  => 'Customers',
        // 'products'   => 'Products',
        // 'profile'    => 'Profile',
    ];

    $currentRoute = request()->route()?->getName() ?? '';
    $breadcrumb   = 'Dashboard';

    foreach ($routeLabels as $prefix => $label) {
        if (str_starts_with($currentRoute, $prefix)) {
            $breadcrumb = $label;
            break;
        }
    }
@endphp

<div class="hidden h-12 shrink-0 items-center border-b border-stone-200 bg-white px-6 md:flex">
    <span class="text-xs font-medium text-stone-500">{{ $breadcrumb }}</span>
</div>
