<?php

namespace App\Livewire\Review;

use App\Enums\OrderStatus;
use App\Models\{Order, Review};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ReviewForm extends Component
{
    public Order $order;
    public int $rating = 5;
    public string $comment = '';
    public bool $recommend = true;

    public function mount(Order $order): void
    {
        abort_unless($order->client_id === auth()->id(), 403);
        abort_unless($order->status === OrderStatus::Completed, 403);
        abort_if($order->review()->exists(), 403);

        $this->order = $order->load(['gig', 'freelancer']);
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'rating'    => ['required', 'integer', 'min:1', 'max:5'],
            'comment'   => ['required', 'string', 'min:20', 'max:2000'],
            'recommend' => ['boolean'],
        ]);

        Review::create([
            'order_id'    => $this->order->id,
            'gig_id'      => $this->order->gig_id,
            'reviewer_id' => auth()->id(),
            'reviewed_id' => $this->order->freelancer_id,
            'rating'      => $validated['rating'],
            'comment'     => $validated['comment'],
            'recommend'   => $validated['recommend'],
            'is_verified' => true,
        ]);

        $this->order->freelancer->freelancerProfile?->increment('reviews_count');

        session()->flash('success', 'Merci pour votre avis !');
        $this->redirect(route('orders.show', $this->order->uuid), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.review.review-form', ['order' => $this->order])->layout('layouts.afritask');
    }
}
