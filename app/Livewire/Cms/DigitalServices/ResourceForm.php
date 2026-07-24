<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceResource;
use Livewire\Component;

class ResourceForm extends Component
{
    public ?int $itemId = null;

    public string $title_en = '';
    public string $description_en = '';
    public string $audience = 'All';
    public string $icon = 'BookOpen';
    public string $icon_color = 'sky';
    public string $url = '';
    public int $sort_order = 0;
    public bool $is_active = true;

    public array $audiences = ['All', 'Students', 'Parents', 'Staff'];

    public array $icons = [
        'BookOpen', 'BarChart2', 'Users', 'CreditCard',
        'Library', 'Briefcase', 'Mail', 'ClipboardList', 'Bell',
    ];

    public array $iconColors = ['sky', 'rose', 'emerald', 'amber', 'violet', 'slate'];

    public function mount(?int $itemId = null): void
    {
        if ($itemId) {
            $resource = DigitalServiceResource::findOrFail($itemId);
            $this->itemId          = $resource->id;
            $this->title_en        = $resource->getTranslation('title', 'en', false) ?? '';
            $this->description_en  = $resource->getTranslation('description', 'en', false) ?? '';
            $this->audience        = $resource->audience;
            $this->icon            = $resource->icon;
            $this->icon_color      = $resource->icon_color;
            $this->url             = $resource->url;
            $this->sort_order      = $resource->sort_order;
            $this->is_active       = $resource->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'       => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'audience'       => ['required', 'in:All,Students,Parents,Staff'],
            'icon'           => ['required', 'string', 'max:50'],
            'icon_color'     => ['required', 'in:sky,rose,emerald,amber,violet,slate'],
            'url'            => ['required', 'url', 'max:500'],
            'sort_order'     => ['integer', 'min:0'],
            'is_active'      => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'       => ['en' => $this->title_en],
            'description' => $this->description_en ? ['en' => $this->description_en] : null,
            'audience'    => $this->audience,
            'icon'        => $this->icon,
            'icon_color'  => $this->icon_color,
            'url'         => $this->url,
            'sort_order'  => $this->sort_order,
            'is_active'   => $this->is_active,
        ];

        if ($this->itemId) {
            DigitalServiceResource::findOrFail($this->itemId)->update($data);
            $this->dispatch('toast', message: 'Resource updated.');
        } else {
            DigitalServiceResource::create($data);
            $this->dispatch('toast', message: 'Resource added.');
        }

        $this->redirect(route('cms.digital-services.resources'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.digital-services.resource-form')
            ->layout('layouts.app', ['title' => $this->itemId ? 'Edit Resource' : 'New Resource']);
    }
}
