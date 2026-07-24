<?php

function current_breadcrumb(): string
{
    $labels = config('breadcrumbs', []);
    $currentRoute = request()?->route()?->getName() ?? '';

    foreach ($labels as $prefix => $label) {
        if ($currentRoute == $prefix or Str::startsWith($currentRoute, $prefix)) {
            return $label;
        }
    }

    return 'Admin';
}
