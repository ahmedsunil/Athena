<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Testimonials</h1>
            <p class="admin-muted">Alumni and parent testimonials shown on the home page.</p>
        </div>
        <a href="{{ route('cms.home.testimonials.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add testimonial
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Designation</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($testimonials as $t)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $t->id }}</td>
                    <td class="admin-table-cell">
                        @if($t->photo_path)
                            <img src="{{ $t->photo_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                                {{ strtoupper(substr($t->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $t->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $t->current_designation }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $t->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $t->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $t->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.home.testimonials.edit', $t->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $t->id }})" wire:confirm="Delete this testimonial?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No testimonials yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($testimonials as $t)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($t->photo_path)
                        <img src="{{ $t->photo_url }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                            {{ strtoupper(substr($t->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $t->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $t->current_designation }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.home.testimonials.edit', $t->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $t->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $testimonials->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>
