<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceResource;
use Livewire\Component;

class ResourcesIndex extends Component
{
    public string $title_en = '';
    public string $title_dv = '';
    public string $description_en = '';
    public string $description_dv = '';
    public string $audience = 'All';
    public string $icon = 'BookOpen';
    public string $icon_color = 'sky';
    public string $url = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    public array $audiences = ['All', 'Students', 'Parents', 'Staff'];

    public array $icons = [
        'BookOpen', 'BarChart2', 'Users', 'CreditCard',
        'Library', 'Briefcase', 'Mail', 'ClipboardList', 'Bell',
    ];

    public array $iconColors = ['sky', 'rose', 'emerald', 'amber', 'violet', 'slate'];

    protected function rules(): array
    {
        return [
            'title_en'       => ['required', 'string', 'max:255'],
            'title_dv'       => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_dv' => ['nullable', 'string'],
            'audience'       => ['required', 'in:All,Students,Parents,Staff'],
            'icon'        => ['required', 'string', 'max:50'],
            'icon_color'  => ['required', 'in:sky,rose,emerald,amber,violet,slate'],
            'url'         => ['required', 'url', 'max:500'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'       => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'description' => ($this->description_en || $this->description_dv)
                              ? ['en' => $this->description_en, 'dv' => $this->description_dv]
                              : null,
            'audience'    => $this->audience,
            'icon'        => $this->icon,
            'icon_color'  => $this->icon_color,
            'url'         => $this->url,
            'sort_order'  => $this->sort_order,
            'is_active'   => $this->is_active,
        ];

        if ($this->editingId) {
            DigitalServiceResource::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Resource updated.');
        } else {
            DigitalServiceResource::create($data);
            $this->dispatch('toast', message: 'Resource added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $resource = DigitalServiceResource::findOrFail($id);
        $this->editingId      = $resource->id;
        $this->title_en       = $resource->getTranslation('title', 'en', false) ?? '';
        $this->title_dv       = $resource->getTranslation('title', 'dv', false) ?? '';
        $this->description_en = $resource->getTranslation('description', 'en', false) ?? '';
        $this->description_dv = $resource->getTranslation('description', 'dv', false) ?? '';
        $this->audience       = $resource->audience;
        $this->icon         = $resource->icon;
        $this->icon_color   = $resource->icon_color;
        $this->url          = $resource->url;
        $this->sort_order   = $resource->sort_order;
        $this->is_active    = $resource->is_active;
    }

    public function delete(int $id): void
    {
        DigitalServiceResource::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Resource deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['title_en', 'title_dv', 'description_en', 'description_dv', 'url', 'editingId']);
        $this->audience   = 'All';
        $this->icon       = 'BookOpen';
        $this->icon_color = 'sky';
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.digital-services.resources-index', [
            'resources' => DigitalServiceResource::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Digital Services — Resources']);
    }
}
