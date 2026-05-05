<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Links</h1>
        <p class="admin-muted">Route registry from <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono text-xs">config/links.php</code>.
        </p>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Route</th>
            <th class="admin-table-heading">Label</th>
        </x-slot>

        <x-slot name="body">
            @foreach($links as $route => $label)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $loop->iteration }}</td>
                    <td class="admin-table-cell-primary">
                        <code
                            class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-700">{{ $route }}</code>
                    </td>
                    <td class="admin-table-cell text-xs">{{ $label }}</td>
                </tr>
            @endforeach
        </x-slot>

        <x-slot name="mobile">
            @foreach($links as $route => $label)
                <li class="flex items-center justify-between gap-3 px-4 py-3">
                    <span class="text-sm font-medium text-zinc-950">{{ $label }}</span>
                    <code class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-700">{{ $route }}</code>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>
