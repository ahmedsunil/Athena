<?php

namespace App\Livewire\Cms\Apps;

use App\Models\WebApp;
use Livewire\Component;

class WebAppForm extends Component
{
    public ?int $appId = null;

    public string $title = '';
    public string $subtitle = '';
    public string $icon_key = 'ExternalLink';
    public string $url = '';
    public string $action_label = 'Login';
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $appId = null): void
    {
        if ($appId) {
            $app = WebApp::findOrFail($appId);
            $this->appId        = $app->id;
            $this->title        = $app->title;
            $this->subtitle     = $app->subtitle ?? '';
            $this->icon_key     = $app->icon_key;
            $this->url          = $app->url;
            $this->action_label = $app->action_label;
            $this->sort_order   = $app->sort_order;
            $this->is_active    = $app->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'subtitle'     => ['nullable', 'string'],
            'icon_key'     => ['required', 'string', 'max:50'],
            'url'          => ['required', 'url', 'max:2048'],
            'action_label' => ['required', 'string', 'max:50'],
            'sort_order'   => ['integer', 'min:0'],
            'is_active'    => ['boolean'],
        ];
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

        if ($this->appId) {
            WebApp::findOrFail($this->appId)->update($data);
            $this->dispatch('toast', message: 'App updated.');
        } else {
            WebApp::create($data);
            $this->dispatch('toast', message: 'App added.');
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
