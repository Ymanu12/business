<?php

namespace App\Livewire\Order;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderList extends Component
{
    public string $tab = 'active';

    public function render(): View
    {
        $user = auth()->user();

        if ($user->isFreelancer()) {
            $baseQuery = $user->ordersAsFreelancer()->with(['client', 'gig']);
        } else {
            $baseQuery = $user->ordersAsClient()->with(['freelancer', 'gig']);
        }

        $activeOrders = (clone $baseQuery)
            ->whereIn('status', ['paid', 'in_progress', 'delivered', 'revision_requested'])
            ->latest()
            ->get();

        $completedOrders = (clone $baseQuery)
            ->whereIn('status', ['completed', 'cancelled', 'refunded', 'disputed'])
            ->latest()
            ->get();

        return view('livewire.order.order-list', compact('activeOrders', 'completedOrders'))->layout('layouts.afritask');
    }
}
