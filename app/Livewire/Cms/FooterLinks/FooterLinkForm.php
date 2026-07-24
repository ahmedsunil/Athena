<?php

namespace App\Livewire\Cms\FooterLinks;

use App\Models\FooterLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FooterLinkForm extends Component
{
    public ?int $linkId = null;

    public string $label = '';
    public string $link_type = 'internal';
    public string $link_key = '';
    public string $custom_url = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    public function mount(?int $linkId = null): void
    {
        if ($linkId) {
            $link = FooterLink::findOrFail($linkId);
            $isExternal = preg_match('/^https?:\/\//i', $link->link_key) === 1;

            $this->linkId     = $link->id;
            $this->label      = $link->label;
            $this->link_type  = $isExternal ? 'custom' : 'internal';
            $this->link_key   = $isExternal ? '' : $link->link_key;
            $this->custom_url = $isExternal ? $link->link_key : '';
            $this->is_active  = $link->is_active;
            $this->sort_order = $link->sort_order;
        }
    }

    public function setLinkType(string $type): void
    {
        if (! in_array($type, ['internal', 'custom'], true)) {
            return;
        }

        $this->link_type = $type;
    }

    protected function rules(): array
    {
        return [
            'label'      => ['required', 'string', 'max:255'],
            'link_type'  => ['required', Rule::in(['internal', 'custom'])],
            'link_key'   => ['required_if:link_type,internal', 'nullable', 'string', Rule::in(array_keys(config('links')))],
            'custom_url' => ['required_if:link_type,custom', 'nullable', 'url', 'max:2048'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'label'      => $this->label,
            'link_key'   => $this->link_type === 'custom' ? $this->custom_url : $this->link_key,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->linkId) {
            FooterLink::findOrFail($this->linkId)->update($data);
            $this->dispatch('toast', message: 'Footer link updated.');
        } else {
            FooterLink::create($data);
            $this->dispatch('toast', message: 'Footer link created.');
        }

        Cache::store('file')->forget('footer_data');
        $this->redirect(route('cms.footer-links'), navigate: true);
    }

    public function render()
    {
        $title = $this->linkId ? 'Edit footer link' : 'New footer link';
        return view('livewire.cms.footer-links.footer-link-form', [
            'linkKeys' => config('links'),
        ])->layout('layouts.app', ['title' => $title]);
    }
}
