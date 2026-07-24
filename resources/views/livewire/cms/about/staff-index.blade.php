<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Team</h1>
            <p class="admin-muted">Manage staff members displayed on the public About → Team tab.</p>
        </div>
        <a href="{{ route('cms.team.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add staff member
        </a>
    </div>

    {{-- Staff list table --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Designation</th>
            <th class="admin-table-heading">Section</th>
            <th class="admin-table-heading">Sub-section</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($staffMembers as $s)
                <tr>
                    <td class="admin-table-cell">
                        @if($s->photo_url)
                            <img src="{{ $s->photo_url }}" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="h-8 w-8 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-500 text-xs font-bold">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $s->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $s->designation }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $s->section === 'senior_management' ? 'bg-[#002366]/10 text-[#002366]' : ($s->section === 'academic' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ match($s->section) { 'senior_management' => 'Senior Mgmt', 'academic' => 'Academic', 'administrative' => 'Admin' } }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400 text-xs">{{ $s->sub_section ? str_replace('_', ' ', ucfirst($s->sub_section)) : '—' }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $s->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $s->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.team.edit', $s->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete {{ $s->name }}?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No staff members yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($staffMembers as $s)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($s->photo_url)
                        <img src="{{ $s->photo_url }}" class="h-8 w-8 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="h-8 w-8 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-500 text-xs font-bold flex-shrink-0">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $s->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $s->designation }} · {{ str_replace('_', ' ', $s->section) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.team.edit', $s->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $staffMembers->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>
