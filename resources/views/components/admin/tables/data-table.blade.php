{{--
    Tables: DataTable
    -----------------
    Responsive table container. Shows a desktop table on md+ and a mobile card
    list on smaller screens. Wraps thead/tbody slots + pagination.

    Usage:
        <x-admin.tables.data-table>

            {{-- Desktop table head --}}
            <x-slot name="head">
                <th class="px-5 py-3">Customer</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3">Total</th>
                <th class="px-5 py-3 text-right">Actions</th>
            </x-slot>

            {{-- Desktop table body --}}
            <x-slot name="body">
                @foreach($items as $item)
                    <tr>
                        <td class="px-5 py-3 font-medium text-zinc-800">{{ $item->name }}</td>
                        <td class="px-5 py-3 text-zinc-500">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3 font-mono font-medium text-zinc-900">{{ $item->total }}</td>
                        <td class="px-5 py-3">
                            <x-admin.tables.row-actions :item="$item" />
                        </td>
                    </tr>
                @endforeach
            </x-slot>

            {{-- Mobile card list --}}
            <x-slot name="mobile">
                @foreach($items as $item)
                    <li class="flex items-start gap-3 px-4 py-3">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-zinc-800">{{ $item->name }}</p>
                        </div>
                    </li>
                @endforeach
            </x-slot>

            {{-- Pagination --}}
            <x-slot name="pagination">
                {{ $items->links() }}
            </x-slot>

        </x-admin.tables.data-table>

    Slots:
        $head        — <th> cells for the desktop thead row
        $body        — <tr> rows for the desktop tbody
        $mobile      — <li> items for the mobile card list
        $pagination  — Livewire links() call
--}}

<div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">

    {{-- Mobile card list --}}
    @if(isset($mobile))
        <ul class="divide-y divide-zinc-100 md:hidden">
            {{ $mobile }}
        </ul>
    @endif

    {{-- Desktop table --}}
    @if(isset($head) || isset($body))
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full text-sm">
                @if(isset($head))
                    <thead>
                        <tr class="border-b border-zinc-100 text-left text-xs font-medium text-zinc-500">
                            {{ $head }}
                        </tr>
                    </thead>
                @endif
                @if(isset($body))
                    <tbody class="admin-table-body">
                        {{ $body }}
                    </tbody>
                @endif
            </table>
        </div>
    @endif

    {{-- Fallback slot (empty state or custom content) --}}
    @if(isset($slot) && $slot->isNotEmpty())
        {{ $slot }}
    @endif

    {{-- Pagination --}}
    @if(isset($pagination))
        <div class="border-t border-zinc-100 px-5 py-3">
            {{ $pagination }}
        </div>
    @endif

</div>
