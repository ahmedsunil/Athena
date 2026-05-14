@php
    $currentRoute = request()->route()?->getName() ?? '';
    $digitalServicesTabs = [
        ['label' => 'Documents',         'route' => 'cms.digital-services.documents',  'href' => route('cms.digital-services.documents')],
        ['label' => 'Resources',         'route' => 'cms.digital-services.resources',  'href' => route('cms.digital-services.resources')],
        ['label' => 'Academic Calendar', 'route' => 'cms.digital-services.calendar',   'href' => route('cms.digital-services.calendar')],
        ['label' => 'Calendars',         'route' => 'cms.digital-services.calendars',  'href' => route('cms.digital-services.calendars')],
    ];
@endphp

<div>
    <h1 class="admin-page-title text-lg">Digital Services</h1>
    <p class="admin-muted">Manage documents, online resources, and academic calendar entries.</p>
</div>

<div class="flex items-center gap-1 pb-0 flex-wrap">
    @foreach($digitalServicesTabs as $tab)
        @php $isActive = str_starts_with($currentRoute, $tab['route']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive ? 'text-zinc-50' : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
