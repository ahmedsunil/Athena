<div>
    <div>
        <h1 class="text-1xl text-slate-700">School Profile</h1>
    </div>
    <div>
        <form action="">
            @csrf
            <x-admin.forms.form-field label="Customer Name" field="customerName">
                <input type="text"
                       wire:model="customerName"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-stone-500 focus:outline-none focus:ring-1 focus:ring-stone-500">
            </x-admin.forms.form-field>
        </form>
    </div>
</div>
