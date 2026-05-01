<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Font Families
    |--------------------------------------------------------------------------
    */
    'family' => [
        'sans' => 'Inter',          // Body, UI, labels
        'mono' => 'IBM Plex Mono',  // Numbers, codes, IDs
    ],

    /*
    |--------------------------------------------------------------------------
    | Font Sizes
    |--------------------------------------------------------------------------
    */
    'size' => [
        'nav_group'  => 'text-[10px]',  // Sidebar group labels
        'badge_id'   => 'text-[11px]',  // Badge/ID text
        'logo'       => 'text-[15px]',  // Logo text
        'xs'         => 'text-xs',      // Hints, timestamps, labels
        'sm'         => 'text-sm',      // Body, inputs, table cells
        'base'       => 'text-base',    // Default
        'lg'         => 'text-lg',      // Stat values, mobile page titles
        'xl'         => 'text-xl',      // Page titles (desktop)
        '2xl'        => 'text-2xl',
        '3xl'        => 'text-3xl',
    ],

    /*
    |--------------------------------------------------------------------------
    | Font Weights
    |--------------------------------------------------------------------------
    */
    'weight' => [
        'normal'    => 'font-normal',    // Default body
        'medium'    => 'font-medium',    // Labels, secondary elements
        'semibold'  => 'font-semibold',  // Page titles, headers, buttons
        'bold'      => 'font-bold',      // Stat values, strong emphasis
    ],

    /*
    |--------------------------------------------------------------------------
    | Letter Spacing
    |--------------------------------------------------------------------------
    */
    'tracking' => [
        'tight'   => 'tracking-tight',    // Headings
        'wide'    => 'tracking-wide',     // Section labels
        'widest'  => 'tracking-widest',   // Sidebar nav group labels
    ],

    /*
    |--------------------------------------------------------------------------
    | Preset Text Styles (combine size + weight + color)
    |--------------------------------------------------------------------------
    */
    'styles' => [
        'page_title'    => 'admin-page-title',
        'card_header'   => 'admin-section-title',
        'section_label' => 'admin-label',
        'body'          => 'admin-body',
        'muted'         => 'admin-muted',
        'stat_value'    => 'admin-stat-value',
        'stat_label'    => 'admin-stat-label',
        'nav_group'     => 'text-[10px] font-semibold uppercase tracking-widest text-zinc-400',
        'nav_item'      => 'text-sm font-medium',
        'table_header'  => 'admin-table-heading',
        'table_cell'    => 'admin-table-cell',
        'badge'         => 'admin-badge',
        'error'         => 'text-xs text-red-600',
        'hint'          => 'text-xs text-zinc-400',
    ],

];
