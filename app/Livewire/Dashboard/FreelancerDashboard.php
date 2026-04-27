<?php

namespace App\Livewire\Dashboard;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FreelancerDashboard extends Component
{
    public function mount(): void
    {
        $user = auth()->user();

        if (! $user?->isFreelancer()) {
            $this->redirectRoute('dashboard', [], false, true);

            return;
        }

        if (! $user->freelancerProfile()->exists()) {
            $this->redirectRoute('freelancer.onboarding', [], false, true);
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

        $activeOrders = Order::where('freelancer_id', $user->id)
            ->whereIn('status', $activeStatuses)
            ->with(['gig', 'client'])
            ->latest()
            ->get();

        $urgentOrders = $activeOrders->filter(
            fn ($o) => $o->due_date && $o->due_date->diffInHours(now()) <= 48
        );

        $profile = $user->freelancerProfile;
        $wallet = $user->getOrCreateWallet();

        return view('livewire.dashboard.freelancer-dashboard', [
            'activeOrders' => $activeOrders,
            'activeOrdersCount' => $activeOrders->count(),
            'urgentCount' => $urgentOrders->count(),
            'profile' => $profile,
            'wallet' => $wallet,
        ])->layout('layouts.afritask');
    }
}
