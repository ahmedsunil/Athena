<?php

namespace App\Livewire\Cms\Apps;

use App\Models\WebApp;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class WebAppForm extends Component
{
    use WithFileUploads;

    public ?int $appId = null;

    public string $title = '';
    public string $subtitle = '';
    public string $icon_key = 'ExternalLink';
    public $icon_image = null;
    public ?string $existing_icon_path = null;
    public bool $iconRemoved = false;
    public string $url = '';
    public string $action_label = 'Login';
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $appId = null): void
    {
        if ($appId) {
            $app = WebApp::findOrFail($appId);
            $this->appId              = $app->id;
            $this->title              = $app->title;
            $this->subtitle           = $app->subtitle ?? '';
            $this->icon_key           = $app->icon_key;
            $this->existing_icon_path = $app->icon_path;
            $this->url                = $app->url;
            $this->action_label       = $app->action_label;
            $this->sort_order         = $app->sort_order;
            $this->is_active          = $app->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'subtitle'     => ['nullable', 'string'],
            'icon_key'     => ['required', 'string', 'max:50'],
            'icon_image'   => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'url'          => ['required', 'url', 'max:2048'],
            'action_label' => ['required', 'string', 'max:50'],
            'sort_order'   => ['integer', 'min:0'],
            'is_active'    => ['boolean'],
        ];
    }

    public function updatedIconImage(): void
    {
        $this->validateOnly('icon_image');
    }

    public function removeIcon(): void
    {
        $this->icon_image = null;
        $this->existing_icon_path = null;
        $this->iconRemoved = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'subtitle'     => $this->subtitle ?: null,
            'icon_key'     => $this->icon_key,
            'url'          => $this->url,
            'action_label' => $this->action_label,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
        ];

        $app = $this->appId ? WebApp::findOrFail($this->appId) : null;
        $oldIconPath = $app?->icon_path;

        if ($this->icon_image) {
            $data['icon_path'] = $this->icon_image->store('apps', 'public');
        } elseif ($this->iconRemoved) {
            $data['icon_path'] = null;
        }

        if ($app) {
            $app->update($data);
            $this->dispatch('toast', message: 'App updated.');
        } else {
            WebApp::create($data);
            $this->dispatch('toast', message: 'App added.');
        }

        if ($oldIconPath && array_key_exists('icon_path', $data) && $data['icon_path'] !== $oldIconPath) {
            Storage::disk('public')->delete($oldIconPath);
        }

        $this->redirect(route('cms.apps'), navigate: true);
    }

    public function render()
    {
        $title = $this->appId ? 'Edit App' : 'New App';
        return view('livewire.cms.apps.web-app-form', [
            'iconKeys' => config('icons'),
        ])->layout('layouts.app', ['title' => $title]);
    }
}
