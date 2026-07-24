<div class="space-y-4">

    @include('layouts.partials.cms-academics-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Key Stage Cards</h1>
            <p class="admin-muted">Manage key stage cards shown on the Academics page.</p>
        </div>
        <a href="{{ route('cms.academics.levels.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add level
        </a>
    </div>

    @php
    $stageColors = [
        'FS'  => 'bg-[#002366]/10 text-[#002366]',
        'KS1' => 'bg-sky-100 text-sky-700',
        'KS2' => 'bg-emerald-100 text-emerald-700',
        'KS3' => 'bg-violet-100 text-violet-700',
        'KS4' => 'bg-amber-100 text-amber-700',
        'KS5' => 'bg-slate-100 text-slate-700',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Stage</th>
            <th class="admin-table-heading">Label</th>
            <th class="admin-table-heading">Ages / Grades</th>
            <th class="admin-table-heading">Lead teacher</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($levels as $level)
                <tr>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $level->abbreviation }}
                        </span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $level->label }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">
                        {{ $level->age_range }}<br>{{ $level->year_groups }}
                    </td>
                    <td class="admin-table-cell">
                        <div class="flex items-center gap-2">
                            @if($level->lead_teacher_photo_path)
                                <img src="{{ $level->lead_teacher_photo_url }}" alt="{{ $level->lead_teacher }}" class="h-7 w-7 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="h-7 w-7 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $level->initials }}
                                </div>
                            @endif
                            <span class="text-xs text-zinc-700">{{ $level->lead_teacher }}</span>
                        </div>
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $level->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $level->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.academics.levels.edit', $level->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $level->id }})" wire:confirm="Delete this level?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No levels yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($levels as $level)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold flex-shrink-0 {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $level->abbreviation }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $level->label }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $level->age_range }} · {{ $level->lead_teacher }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.academics.levels.edit', $level->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $level->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No levels yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $levels->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>
