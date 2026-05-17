<?php

namespace App\Livewire\Cms\FooterLinks;

use App\Models\FooterLink;
use Livewire\Component;

class FooterLinksIndex extends Component
{
    public string $label = '';
    public string $label_dv = '';
    public string $link_key = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'label'      => ['required', 'string', 'max:255'],
            'label_dv'   => ['nullable', 'string', 'max:255'],
            'link_key'   => ['required', 'string'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'label'      => $this->label,
            'label_dv'   => $this->label_dv,
            'link_key'   => $this->link_key,
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

        $this->reset(['label', 'label_dv', 'link_key', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
    }

    public function edit(int $id): void
    {
        $link = FooterLink::findOrFail($id);
        $this->editingId  = $link->id;
        $this->label      = $link->label;
        $this->label_dv   = $link->label_dv ?? '';
        $this->link_key   = $link->link_key;
        $this->is_active  = $link->is_active;
        $this->sort_order = $link->sort_order;
    }

    public function cancel(): void
    {
        $this->reset(['label', 'label_dv', 'link_key', 'is_active', 'sort_order', 'editingId']);
        $this->is_active = true;
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
