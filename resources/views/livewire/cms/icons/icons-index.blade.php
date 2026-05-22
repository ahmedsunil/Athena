<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Icons</h1>
        <p class="admin-muted">Icon registry from <code class="rounded bg-zinc-100 px-1.5 py-0.5 font-mono text-xs">config/icons.php</code>.
        </p>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Preview</th>
            <th class="admin-table-heading">Key</th>
            <th class="admin-table-heading">Label</th>
        </x-slot>

        <x-slot name="body">
            @foreach($icons as $key => $icon)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $loop->iteration }}</td>
                    <td class="admin-table-cell">
                        <x-icon :key="$key" class="h-4 w-4" />
                    </td>
                    <td class="admin-table-cell-primary">
                        <code class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-700">{{ $key }}</code>
                    </td>
                    <td class="admin-table-cell text-xs">{{ $icon['label'] }}</td>
                </tr>
            @endforeach
        </x-slot>

        <x-slot name="mobile">
            @foreach($icons as $key => $icon)
                <li class="flex items-center gap-3 px-4 py-3">
                    <x-icon :key="$key" class="h-4 w-4 shrink-0 text-zinc-500" />
                    <div class="min-w-0 flex-1">
                        <code class="font-mono text-xs text-zinc-700">{{ $key }}</code>
                        <p class="text-sm text-zinc-500">{{ $icon['label'] }}</p>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>
