<?php

namespace App\Livewire\Home;

use App\Models\{Category, Gig};
use Livewire\Component;

class Homepage extends Component
{
    public string $searchQuery = '';

    public function search(): void
    {
        if (filled($this->searchQuery)) {
            $this->redirect(route('search', ['q' => $this->searchQuery]));
        }
    }

    public function render()
    {
        return view('livewire.home.homepage', [
            'categories'    => Category::parents()->withCount('gigs')->get(),
            'featuredGigs'  => Gig::published()
                ->featured()
                ->with(['freelancer', 'category', 'reviews'])
                ->withCount('reviews')
                ->limit(8)
                ->get(),
            'popularGigs'   => Gig::published()
                ->with(['freelancer', 'category'])
                ->withCount(['reviews', 'orders'])
                ->orderByDesc('orders_count')
                ->limit(8)
                ->get(),
        ])->layout('layouts.afritask');
    }
}
