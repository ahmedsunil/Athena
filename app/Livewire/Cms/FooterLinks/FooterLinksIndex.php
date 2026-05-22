<?php

namespace App\Livewire\Cms\FooterLinks;

use App\Models\FooterLink;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FooterLinksIndex extends Component
{
    public string $label = '';
    public string $label_dv = '';
    public string $link_type = 'internal';
    public string $link_key = '';
    public string $custom_url = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'label'      => ['required', 'string', 'max:255'],
            'label_dv'   => ['nullable', 'string', 'max:255'],
            'link_type'  => ['required', Rule::in(['internal', 'custom'])],
            'link_key'   => ['required_if:link_type,internal', 'nullable', 'string', Rule::in(array_keys(config('links')))],
            'custom_url' => ['required_if:link_type,custom', 'nullable', 'url', 'max:2048'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function setLinkType(string $type): void
    {
        if (! in_array($type, ['internal', 'custom'], true)) {
            return;
        }

        $this->link_type = $type;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'label'      => $this->label,
            'label_dv'   => $this->label_dv,
            'link_key'   => $this->link_type === 'custom' ? $this->custom_url : $this->link_key,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            FooterLink::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Footer link updated.');
        } else {
            FooterLink::create($data);
            $this->dispatch('toast', message: 'Footer link created.');
        }

        $this->reset(['label', 'label_dv', 'link_type', 'link_key', 'custom_url', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
        $this->link_type = 'internal';
    }

    public function edit(int $id): void
    {
        $link = FooterLink::findOrFail($id);
        $isExternal = preg_match('/^https?:\/\//i', $link->link_key) === 1;

        $this->editingId  = $link->id;
        $this->label      = $link->label;
        $this->label_dv   = $link->label_dv ?? '';
        $this->link_type  = $isExternal ? 'custom' : 'internal';
        $this->link_key   = $isExternal ? '' : $link->link_key;
        $this->custom_url = $isExternal ? $link->link_key : '';
        $this->is_active  = $link->is_active;
        $this->sort_order = $link->sort_order;
    }

    public function cancel(): void
    {
        $this->reset(['label', 'label_dv', 'link_type', 'link_key', 'custom_url', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
        $this->link_type = 'internal';
    }

    public function delete(int $id): void
    {
        FooterLink::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Footer link deleted.');
    }

    public function render()
    {
        return view('livewire.cms.footer-links.footer-links-index', [
            'links'    => FooterLink::orderBy('sort_order')->orderBy('id')->get(),
            'linkKeys' => config('links'),
        ])->layout('layouts.app', ['title' => 'Footer Links']);
    }
}
