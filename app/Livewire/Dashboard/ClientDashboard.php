<?php

namespace App\Livewire\Dashboard;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClientDashboard extends Component
{
    public function mount(): void
    {
        if (! auth()->user()?->isClient()) {
            $this->redirectRoute('dashboard', navigate: true);
        }
    }

    public function render(): View
    {
        $user = auth()->user();

        $activeStatuses = [
            OrderStatus::Paid,
            OrderStatus::InProgress,
            OrderStatus::Delivered,
            OrderStatus::RevisionRequested,
        ];

        $activeOrders = Order::where('client_id', $user->id)
            ->whereIn('status', $activeStatuses)
            ->with(['gig', 'freelancer', 'escrow'])
            ->latest()
            ->get();

        $pendingDeliveries = $activeOrders->where('status', OrderStatus::Delivered)->count();
        $escrowTotal       = $activeOrders->sum(fn ($o) => $o->escrow?->amount ?? 0);
        $wallet            = $user->getOrCreateWallet();

        return view('livewire.dashboard.client-dashboard', [
            'activeOrders'      => $activeOrders,
            'activeOrdersCount' => $activeOrders->count(),
            'pendingDeliveries' => $pendingDeliveries,
            'escrowTotal'       => $escrowTotal,
            'wallet'            => $wallet,
        ])->layout('layouts.afritask');
    }
}
