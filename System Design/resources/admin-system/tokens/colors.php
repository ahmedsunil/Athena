<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Primary (shadcn / Zinc)
    |--------------------------------------------------------------------------
    */
    'primary' => [
        'light'  => 'zinc-100',  // bg-zinc-100 — active nav bg, badge bg
        'base'   => 'zinc-950',  // bg-zinc-950 — primary buttons, active nav
        'dark'   => 'zinc-950',  // text-zinc-950 — active nav text, badge text
        'accent' => 'zinc-900',  // bg-zinc-900 — chart fills
    ],

    /*
    |--------------------------------------------------------------------------
    | Neutral (shadcn / Zinc)
    |--------------------------------------------------------------------------
    */
    'neutral' => [
        'bg'        => 'zinc-50',    // Page background, filter bar bg
        'surface'   => 'white',      // Cards, inputs, containers
        'subtle'    => 'zinc-100',   // Icon bg, table striping base
        'muted'     => 'zinc-200',   // Hover states, disabled
        'border'    => 'zinc-200',   // Card borders, input borders
        'divider'   => 'zinc-100',   // Table row dividers
        'input'     => 'zinc-300',   // Input border (slightly darker)
    ],

    /*
    |--------------------------------------------------------------------------
    | Text
    |--------------------------------------------------------------------------
    */
    'text' => [
        'primary'   => 'zinc-950',   // Headings, stat values
        'secondary' => 'zinc-700',   // Labels, emphasized body
        'body'      => 'zinc-600',   // Regular body text
        'muted'     => 'zinc-500',   // Subtitles, secondary labels
        'hint'      => 'zinc-400',   // Hints, placeholders, icons
        'faint'     => 'zinc-300',   // Very light text, empty state icons
        'inverse'   => 'white',      // Text on dark/colored backgrounds
    ],

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */
    'status' => [
        'success' => [
            'bg'   => 'zinc-100',
            'text' => 'zinc-950',
            'ring' => 'zinc-950/20',
        ],
        'warning' => [
            'bg'   => 'amber-50',
            'text' => 'amber-700',
            'ring' => 'amber-600/20',
        ],
        'error' => [
            'bg'   => 'red-50',
            'text' => 'red-600',
            'ring' => 'red-600/20',
        ],
        'info' => [
            'bg'   => 'zinc-50',
            'text' => 'zinc-700',
            'ring' => 'zinc-600/20',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Danger / Destructive
    |--------------------------------------------------------------------------
    */
    'danger' => [
        'light' => 'red-50',   // bg-red-50  — delete zone bg
        'base'  => 'red-600',  // bg-red-600 — danger buttons
        'dark'  => 'red-700',  // bg-red-700 — danger button hover
        'muted' => 'red-400',  // text-red-400 — ghost delete text
        'ring'  => 'red-100',  // border-red-100 — danger alert border
    ],

    /*
    |--------------------------------------------------------------------------
    | Focus Ring
    |--------------------------------------------------------------------------
    */
    'focus' => [
        'border' => 'zinc-950',  // focus:border-zinc-950
        'ring'   => 'zinc-950',  // focus:ring-zinc-950
    ],

];
