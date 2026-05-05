@props(['title', 'subtitle' => null])

<div class="mb-4 flex flex-wrap items-center justify-between gap-3 sm:mb-6">
    <div>
        <h1 class="admin-page-title">{{ $title }}</h1>
        @if($subtitle)
            <p class="admin-page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($action))
        <div>{{ $action }}</div>
    @endif
</div>
