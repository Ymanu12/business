<?php

namespace App\Livewire\Search;

use App\Models\{Category, Gig};
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SearchResults extends Component
{
    use WithPagination;

    public string $query = '';
    public ?int $categoryId = null;
    public string $sortBy = 'popular';
    public ?int $minPrice = null;
    public ?int $maxPrice = null;

    public function mount(): void
    {
        $this->query = request('q', '');
        $this->categoryId = request('category') ? (int) request('category') : null;
    }

    public function updatedQuery(): void    { $this->resetPage(); }
    public function updatedCategoryId(): void { $this->resetPage(); }
    public function updatedSortBy(): void   { $this->resetPage(); }
    public function updatedMinPrice(): void { $this->resetPage(); }
    public function updatedMaxPrice(): void { $this->resetPage(); }

    public function render(): View
    {
        $gigs = Gig::published()
            ->with(['freelancer', 'category'])
            ->withCount(['reviews', 'orders'])
            ->when($this->query, fn ($q) => $q->where(function ($q) {
                $q->where('title', 'like', "%{$this->query}%")
                  ->orWhere('description', 'like', "%{$this->query}%");
            }))
            ->when($this->categoryId, fn ($q) => $q->where(function ($q) {
                $q->where('category_id', $this->categoryId)
                  ->orWhere('sub_category_id', $this->categoryId);
            }))
            ->when($this->minPrice, fn ($q) => $q->where('starting_price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn ($q) => $q->where('starting_price', '<=', $this->maxPrice))
            ->when($this->sortBy === 'popular',   fn ($q) => $q->orderByDesc('orders_count'))
            ->when($this->sortBy === 'rating',    fn ($q) => $q->orderByDesc('avg_rating'))
            ->when($this->sortBy === 'price_asc', fn ($q) => $q->orderBy('starting_price'))
            ->when($this->sortBy === 'price_desc', fn ($q) => $q->orderByDesc('starting_price'))
            ->when($this->sortBy === 'newest',    fn ($q) => $q->latest())
            ->paginate(12);

        return view('livewire.search.search-results', [
            'gigs'       => $gigs,
            'categories' => Category::parents()->withCount('gigs')->get(),
        ])->layout('layouts.afritask');
    }
}
