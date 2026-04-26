<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Page / Layout
    |--------------------------------------------------------------------------
    */
    'page' => [
        'padding'    => 'px-4 py-6',      // Mobile page padding
        'padding_sm' => 'sm:px-6',         // Tablet+ page padding
        'gap'        => 'gap-4 sm:gap-6',  // Section-level spacing
    ],

    /*
    |--------------------------------------------------------------------------
    | Cards
    |--------------------------------------------------------------------------
    */
    'card' => [
        'padding'        => 'p-5',        // Standard card body padding
        'padding_compact'=> 'px-5 py-4', // Compact card (stat cards)
        'gap'            => 'gap-4',      // Space between cards in a grid
    ],

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'cell'         => 'px-5 py-3',    // td / th padding
        'header'       => 'px-5 py-3.5', // Section card headers
        'row_gap'      => 'divide-y divide-zinc-100',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    */
    'modal' => [
        'padding' => 'p-6',
        'gap'     => 'space-y-4',
    ],

    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    'form' => [
        'field_gap'   => 'space-y-4',      // Vertical space between fields
        'grid_gap'    => 'gap-4',          // Grid column gap
        'label_gap'   => 'mb-1.5',         // Space between label and input
        'section_gap' => 'space-y-6',      // Space between form sections
    ],

    /*
    |--------------------------------------------------------------------------
    | Inline / Component
    |--------------------------------------------------------------------------
    */
    'inline' => [
        'xs'  => 'gap-1',    // Tight icon+text gap
        'sm'  => 'gap-1.5',  // Small icon+text gap (nav items, badges)
        'md'  => 'gap-2',    // Standard inline gap
        'lg'  => 'gap-2.5',  // Wider inline gap (nav items)
        'xl'  => 'gap-3',    // Large inline gap (stat cards)
        'xxl' => 'gap-4',    // Extra large (form grids)
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */
    'sidebar' => [
        'width'       => 'w-56',
        'logo_height' => 'h-24',
        'nav_padding' => 'px-2 py-4',
        'item_px'     => 'px-3',
        'item_py'     => 'py-2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Topbar / Breadcrumb
    |--------------------------------------------------------------------------
    */
    'topbar' => [
        'height'  => 'h-12',
        'padding' => 'px-4',
    ],
    'breadcrumb' => [
        'height'  => 'h-12',
        'padding' => 'px-6',
    ],

];
