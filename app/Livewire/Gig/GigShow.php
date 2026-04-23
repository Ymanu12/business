<?php

namespace App\Livewire\Gig;

use App\Models\{Favorite, Gig};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class GigShow extends Component
{
    public Gig $gig;
    public int $selectedPackageIndex = 0;

    public function mount(Gig $gig): void
    {
        $this->gig = $gig->load([
            'freelancer.freelancerProfile',
            'category',
            'packages',
            'gallery',
            'faqs',
            'extras',
            'tags',
            'reviews.reviewer',
        ]);
    }

    public function toggleFavorite(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login');
            return;
        }

        $user = auth()->user();
        $existing = Favorite::where('user_id', $user->id)->where('gig_id', $this->gig->id)->first();

        if ($existing) {
            $existing->delete();
        } else {
            Favorite::create(['user_id' => $user->id, 'gig_id' => $this->gig->id]);
        }
    }

    public function isFavorited(): bool
    {
        return auth()->check() && Favorite::where('user_id', auth()->id())->where('gig_id', $this->gig->id)->exists();
    }

    public function render(): View
    {
        $selectedPackage = $this->gig->packages->get($this->selectedPackageIndex) ?? $this->gig->packages->first();

        $similarGigs = Gig::published()
            ->where('category_id', $this->gig->category_id)
            ->where('id', '!=', $this->gig->id)
            ->with(['freelancer', 'category'])
            ->withCount('reviews')
            ->limit(4)
            ->get();

        return view('livewire.gig.gig-show', [
            'gig'             => $this->gig,
            'selectedPackage' => $selectedPackage,
            'similarGigs'     => $similarGigs,
            'isFavorited'     => $this->isFavorited(),
        ])->layout('layouts.afritask');
    }
}

