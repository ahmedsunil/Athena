<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Button
    |--------------------------------------------------------------------------
    */
    'button' => [
        'primary'   => 'inline-flex items-center gap-1.5 rounded-lg bg-zinc-950 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800',
        'secondary' => 'rounded-lg border border-zinc-200 px-4 py-2.5 text-center text-sm font-medium text-zinc-600 transition-colors hover:bg-zinc-50',
        'danger'    => 'rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-red-700',
        'ghost'     => 'text-xs font-medium text-zinc-500 hover:text-zinc-700',
        'ghost_primary' => 'text-xs font-medium text-zinc-950 hover:text-zinc-800',
        'ghost_danger'  => 'text-xs font-medium text-red-400 hover:text-red-600',
    ],

    /*
    |--------------------------------------------------------------------------
    | Card
    |--------------------------------------------------------------------------
    */
    'card' => [
        'base'    => 'rounded-xl border border-zinc-200 bg-white p-5 shadow-sm',
        'compact' => 'rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm',
        'table'   => 'overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Stat Card
    |--------------------------------------------------------------------------
    */
    'stat_card' => [
        'wrapper'         => 'flex items-center gap-4 rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm',
        'icon_container'  => 'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg',
        'label'           => 'text-xs font-medium text-zinc-500',
        'value'           => 'text-lg font-bold text-zinc-950',
        'secondary'       => 'text-xs text-zinc-400',
    ],

    /*
    |--------------------------------------------------------------------------
    | Badge / Status Pill
    |--------------------------------------------------------------------------
    */
    'badge' => [
        'base'    => 'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset',
        'success' => 'bg-zinc-100 text-zinc-800 ring-zinc-950/20',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'danger'  => 'bg-red-50 text-red-700 ring-red-600/20',
        'neutral' => 'bg-zinc-50 text-zinc-700 ring-zinc-600/20',
    ],

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'wrapper'    => 'overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm',
        'table'      => 'w-full text-sm',
        'head_row'   => 'border-b border-zinc-100 text-left text-xs font-medium text-zinc-400',
        'th'         => 'px-5 py-3',
        'body_row'   => 'border-b border-zinc-100 transition-colors odd:bg-white even:bg-zinc-50/50 hover:bg-zinc-100/60 last:border-0',
        'td'         => 'px-5 py-3',
        'actions'    => 'flex items-center justify-end gap-2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */
    'form' => [
        'input'     => 'w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950',
        'select'    => 'w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950',
        'textarea'  => 'w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950',
        'checkbox'  => 'h-4 w-4 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950',
        'label'     => 'mb-1.5 block text-xs font-medium text-zinc-700',
        'error'     => 'mt-1 text-xs text-red-600',
        'file'      => 'flex cursor-pointer items-center gap-2 rounded-lg border border-dashed border-zinc-300 p-4 text-sm text-zinc-500 transition-colors hover:border-zinc-400 hover:text-zinc-950',
    ],

    /*
    |--------------------------------------------------------------------------
    | Toggle Switch
    |--------------------------------------------------------------------------
    */
    'toggle' => [
        'track_on'  => 'inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full bg-zinc-950 transition-colors duration-200 focus:outline-none',
        'track_off' => 'inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full bg-zinc-200 transition-colors duration-200 focus:outline-none',
        'knob'      => 'inline-block h-3.5 w-3.5 rounded-full bg-white shadow transition-transform duration-200',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    */
    'modal' => [
        'overlay' => 'fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/30 backdrop-blur-sm',
        'box'     => 'w-full max-w-sm rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl',
        'title'   => 'text-base font-semibold text-zinc-950',
        'body'    => 'mt-2 text-sm text-zinc-500',
        'footer'  => 'mt-5 flex justify-end gap-2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Toast Notification
    |--------------------------------------------------------------------------
    */
    'toast' => [
        'wrapper' => 'fixed bottom-4 right-4 z-[200] flex flex-col gap-2 pointer-events-none',
        'item'    => 'flex items-center gap-2.5 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-lg pointer-events-auto min-w-[200px]',
        'success' => 'bg-zinc-950',
        'error'   => 'bg-red-600',
    ],

    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */
    'empty_state' => [
        'wrapper' => 'py-12 text-center',
        'icon'    => 'mx-auto mb-3 h-8 w-8 text-zinc-300',
        'message' => 'text-sm font-medium text-zinc-400',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filter Bar
    |--------------------------------------------------------------------------
    */
    'filter_bar' => [
        'wrapper'       => 'rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5',
        'inner'         => 'flex flex-wrap items-center gap-2',
        'search_wrap'   => 'relative flex-1 min-w-[180px]',
        'search_icon'   => 'absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-zinc-400',
        'search_input'  => 'w-full rounded-lg border border-zinc-200 bg-white py-1.5 pl-8 pr-3 text-sm',
        'date_input'    => 'rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-sm',
        'select'        => 'rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-sm',
        'clear_button'  => 'flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-zinc-500 hover:bg-zinc-200',
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Header
    |--------------------------------------------------------------------------
    */
    'page_header' => [
        'wrapper'   => 'mb-4 flex flex-wrap items-center justify-between gap-3 sm:mb-6',
        'title'     => 'text-lg font-bold text-zinc-950 sm:text-xl',
        'subtitle'  => 'text-xs text-zinc-500 sm:text-sm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nav Item (Sidebar)
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'item'     => 'flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
        'active'   => 'bg-zinc-100 text-zinc-800',
        'inactive' => 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950',
        'group'    => 'mb-1 px-3 text-[10px] font-semibold uppercase tracking-widest text-zinc-400',
    ],

    /*
    |--------------------------------------------------------------------------
    | Searchable Dropdown
    |--------------------------------------------------------------------------
    */
    'searchable_dropdown' => [
        'trigger' => 'flex w-full items-center justify-between rounded-lg border border-zinc-300 bg-white px-2.5 py-1.5 text-left text-sm',
        'panel'   => 'absolute left-0 top-full z-[30] mt-1 w-full min-w-[240px] overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-lg',
        'search'  => 'rounded-md border border-zinc-200 px-2.5 py-1.5 text-sm',
        'list'    => 'max-h-48 overflow-y-auto py-1',
        'item'    => 'flex w-full items-center justify-between px-3 py-2 text-left text-sm hover:bg-zinc-100',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bulk Action Bar
    |--------------------------------------------------------------------------
    */
    'bulk_action_bar' => [
        'wrapper' => 'flex items-center justify-between rounded-lg border border-red-100 bg-red-50 px-4 py-2.5',
        'label'   => 'text-sm font-medium text-red-700',
        'button'  => 'rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-red-700',
    ],

];
