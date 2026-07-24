@php
    $currentRoute = request()->route()?->getName() ?? '';
    $aboutTabs = [
        ['label' => 'Mission & Vision',   'route' => 'cms.about.mission',           'href' => route('cms.about.mission')],
        ['label' => 'Leadership Team',    'route' => 'cms.about.leadership',         'href' => route('cms.about.leadership')],
        ['label' => 'Founding Teachers',  'route' => 'cms.about.founding-members',   'href' => route('cms.about.founding-members')],
    ];
@endphp

<div>
    <h1 class="admin-page-title text-lg">About</h1>
    <p class="admin-muted">Manage the public about page content.</p>
</div>

<div class="flex items-center gap-1 pb-0 flex-wrap">
    @foreach($aboutTabs as $tab)
        @php $isActive = str_starts_with($currentRoute, $tab['route']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive ? 'text-zinc-50 after:absolute after:inset-x-0' : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
