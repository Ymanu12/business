<?php

namespace App\Livewire\Gig;

use App\Enums\GigStatus;
use App\Models\{Favorite, Gig};
use App\Notifications\GigStatusUpdatedNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GigShow extends Component
{
    public Gig $gig;
    public int $selectedPackageIndex = 0;

    #[Validate('required|string|min:10|max:500')]
    public string $rejectionReason = '';
    public bool $showRejectForm = false;

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

    public function approveGig(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        abort_unless($this->gig->status === GigStatus::Pending, 422);

        $this->gig->update([
            'status'           => GigStatus::Published,
            'published_at'     => now(),
            'rejection_reason' => null,
        ]);

        $this->gig->freelancer->notify(new GigStatusUpdatedNotification($this->gig->fresh()));

        session()->flash('success', 'Service approuvé et publié.');
        $this->redirectRoute('dashboard.admin', navigate: true);
    }

    public function rejectGig(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        abort_unless($this->gig->status === GigStatus::Pending, 422);

        $this->validateOnly('rejectionReason');

        $this->gig->update([
            'status'           => GigStatus::Rejected,
            'published_at'     => null,
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->gig->freelancer->notify(new GigStatusUpdatedNotification($this->gig->fresh()));

        session()->flash('success', 'Service rejeté. Le freelance a été notifié.');
        $this->redirectRoute('dashboard.admin', navigate: true);
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

