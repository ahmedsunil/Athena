{{--
    Forms: SelectInput
    ------------------
    Styled <select> dropdown.

    Usage:
        <x-admin.forms.select-input wire:model="category">
            <option value="">All categories</option>
            <option value="product">Product</option>
            <option value="service">Service</option>
        </x-admin.forms.select-input>

        {{-- Inside a form-field wrapper --}}
        <x-admin.forms.form-field label="Status" field="status">
            <x-admin.forms.select-input wire:model="status">
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
            </x-admin.forms.select-input>
        </x-admin.forms.form-field>

    All attributes (wire:model, id, etc.) are forwarded automatically.
--}}

<select {{ $attributes->class(['admin-form-control w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}>
    {{ $slot }}
</select>
