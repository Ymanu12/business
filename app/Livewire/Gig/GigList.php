<?php

namespace App\Livewire\Gig;

use App\Enums\GigStatus;
use App\Models\Gig;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class GigList extends Component
{
    public function togglePause(int $gigId): void
    {
        $gig = Gig::where('id', $gigId)->where('freelancer_id', auth()->id())->firstOrFail();

        $gig->update([
            'status' => $gig->status === GigStatus::Paused ? GigStatus::Published : GigStatus::Paused,
        ]);
    }

    public function render(): View
    {
        $gigs = auth()->user()
            ->gigs()
            ->with(['category'])
            ->withCount(['orders', 'reviews'])
            ->latest()
            ->get();

        return view('livewire.gig.gig-list', ['gigs' => $gigs])->layout('layouts.afritask');
    }
}
