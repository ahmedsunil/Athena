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
        'page_title'    => 'text-lg font-bold text-zinc-950 sm:text-xl',
        'card_header'   => 'text-sm font-semibold text-zinc-700',
        'section_label' => 'text-xs font-medium text-zinc-700',
        'body'          => 'text-sm text-zinc-600',
        'muted'         => 'text-xs text-zinc-400',
        'stat_value'    => 'text-lg font-bold text-zinc-950',
        'stat_label'    => 'text-xs font-medium text-zinc-500',
        'nav_group'     => 'text-[10px] font-semibold uppercase tracking-widest text-zinc-400',
        'nav_item'      => 'text-sm font-medium',
        'table_header'  => 'text-xs font-medium text-zinc-400',
        'table_cell'    => 'text-sm text-zinc-700',
        'badge'         => 'text-xs font-medium',
        'error'         => 'text-xs text-red-600',
        'hint'          => 'text-xs text-zinc-400',
    ],

];
