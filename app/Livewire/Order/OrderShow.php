<?php

namespace App\Livewire\Order;

use App\Models\Conversation;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless($order->isOwnedBy(auth()->user()), 403);

        $this->order = $order->load([
            'gig',
            'client',
            'freelancer',
            'package',
            'deliveries',
            'messages',
            'review',
            'escrow',
        ]);
    }

    public function ouvrirChat(): void
    {
        $authUser = auth()->user();
        $otherId = $authUser->id === $this->order->client_id
            ? $this->order->freelancer_id
            : $this->order->client_id;

        $conversation = Conversation::findOrCreateBetweenUsers($authUser->id, $otherId);

        $this->redirectRoute('inbox.show', $conversation->id, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.order.order-show', ['order' => $this->order])->layout('layouts.afritask');
    }
}
