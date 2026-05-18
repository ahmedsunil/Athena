<?php

namespace App\Livewire\Website;

use App\Models\GalleryAlbum;
use Livewire\Attributes\Url;
use Livewire\Component;

class Gallery extends Component
{
    #[Url]
    public string $activeCategory = 'All';

    #[Url]
    public string $activeYear = 'All';

    #[Url]
    public string $activeMonth = 'All';

    public array $categories = ['Events', 'Sports', 'Graduation', 'Cultural', 'Academic', 'Trips'];

    public function setCategory(string $cat): void
    {
        $this->activeCategory = $cat;
    }

    public function clearFilters(): void
    {
        $this->activeCategory = 'All';
        $this->activeYear     = 'All';
        $this->activeMonth    = 'All';
    }

    public function render()
    {
        $query = GalleryAlbum::where('is_active', true);

        if ($this->activeCategory !== 'All') {
            $query->where('category->en', $this->activeCategory);
        }
        if ($this->activeYear !== 'All') {
            $query->whereYear('date', (int) $this->activeYear);
        }
        if ($this->activeMonth !== 'All') {
            $query->whereMonth('date', (int) $this->activeMonth);
        }

        $albums = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $years = GalleryAlbum::where('is_active', true)
            ->pluck('date')
            ->map(fn ($d) => date('Y', strtotime((string) $d)))
            ->unique()
            ->sortDesc()
            ->values();

        $total = GalleryAlbum::where('is_active', true)->count();

        return view('livewire.website.gallery', [
            'albums' => $albums,
            'years'  => $years,
            'total'  => $total,
        ])->layout('layouts.web');
    }
}
