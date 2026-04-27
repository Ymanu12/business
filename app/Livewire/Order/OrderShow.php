<?php

namespace App\Livewire\Order;

use App\Models\{Conversation, LessonProgress, Order};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless($order->isOwnedBy(auth()->user()), 403);

        $this->order = $order->load([
            'gig.lessons',
            'gig.quiz',
            'client',
            'freelancer',
            'package',
            'deliveries',
            'messages',
            'review',
            'escrow',
            'payment',
            'clientEvaluation',
        ]);
    }

    public function ouvrirChat(): void
    {
        $authUser = auth()->user();
        $otherId = $authUser->id === $this->order->client_id
            ? $this->order->freelancer_id
            : $this->order->client_id;

        $conversation = Conversation::findOrCreateBetweenUsers($authUser->id, $otherId);

        $this->redirectRoute('inbox.show', ['conversation' => $conversation->id], false, true);
    }

    public function render(): View
    {
        $lessons     = $this->order->gig?->lessons ?? collect();
        $lessonIds   = $lessons->pluck('id');

        $completedIds = LessonProgress::where('user_id', $this->order->client_id)
            ->whereIn('lesson_id', $lessonIds)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        $progressPct = $lessons->count()
            ? (int) (count($completedIds) / $lessons->count() * 100)
            : 0;

        return view('livewire.order.order-show', [
            'order'        => $this->order,
            'lessons'      => $lessons,
            'completedIds' => $completedIds,
            'progressPct'  => $progressPct,
        ])->layout('layouts.afritask');
    }
}
