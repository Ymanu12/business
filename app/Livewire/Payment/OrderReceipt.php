<?php

namespace App\Livewire\Payment;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderReceipt extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless($order->client_id === auth()->id(), 403);
        abort_unless($order->payment !== null, 404);

        $this->order = $order->load([
            'gig',
            'client',
            'freelancer',
            'package',
            'payment',
        ]);
    }

    public function render(): View
    {
        return view('livewire.payment.order-receipt', [
            'order'   => $this->order,
            'payment' => $this->order->payment,
        ])->layout('layouts.afritask');
    }
}
