<?php

namespace App\Livewire\Category;

use App\Models\{Category, Gig};
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryPage extends Component
{
    use WithPagination;

    public Category $category;
    public string $sortBy = 'popular';

    public function mount(Category $category): void
    {
        $this->category = $category->load('children');
    }

    public function updatedSortBy(): void { $this->resetPage(); }

    public function render(): View
    {
        $categoryIds = $this->category->children->pluck('id')->prepend($this->category->id);

        $gigs = Gig::published()
            ->whereIn('category_id', $categoryIds)
            ->with(['freelancer', 'category'])
            ->withCount(['reviews', 'orders'])
            ->when($this->sortBy === 'popular',   fn ($q) => $q->orderByDesc('orders_count'))
            ->when($this->sortBy === 'rating',    fn ($q) => $q->orderByDesc('avg_rating'))
            ->when($this->sortBy === 'price_asc', fn ($q) => $q->orderBy('starting_price'))
            ->when($this->sortBy === 'newest',    fn ($q) => $q->latest())
            ->paginate(12);

        return view('livewire.category.category-page', compact('gigs'))->layout('layouts.afritask');
    }
}
