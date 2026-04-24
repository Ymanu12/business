<?php

namespace App\Livewire\Dashboard;

use App\Enums\GigStatus;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Enums\WithdrawalStatus;
use App\Models\Gig;
use App\Models\Order;
use App\Models\User;
use App\Models\Withdrawal;
use App\Notifications\GigStatusUpdatedNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function mount(): void
    {
        if (! auth()->user()?->isAdmin()) {
            $this->redirectRoute('dashboard', navigate: true);

            return;
        }
    }

    public function approveGig(int $gigId): void
    {
        $this->ensureAdmin();

        $gig = $this->findPendingGig($gigId);

        $gig->update([
            'status' => GigStatus::Published,
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        $gig->freelancer->notify(new GigStatusUpdatedNotification($gig->fresh()));

        session()->flash('success', 'Le service a ete publie.');
    }

    public function rejectGig(int $gigId): void
    {
        $this->ensureAdmin();

        $gig = $this->findPendingGig($gigId);

        $gig->update([
            'status' => GigStatus::Rejected,
            'published_at' => null,
        ]);

        $gig->freelancer->notify(new GigStatusUpdatedNotification($gig->fresh()));

        session()->flash('success', 'Le service a ete rejete.');
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    private function findPendingGig(int $gigId): Gig
    {
        return Gig::query()
            ->whereKey($gigId)
            ->where('status', GigStatus::Pending)
            ->firstOrFail();
    }

    public function render(): View
    {
        $totalUsers = User::count();
        $totalClients = User::where('role', UserRole::Client)->count();
        $totalFreelancers = User::where('role', UserRole::Freelancer)->count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        $totalOrders = Order::count();
        $activeOrders = Order::whereIn('status', [
            OrderStatus::Paid,
            OrderStatus::InProgress,
            OrderStatus::Delivered,
            OrderStatus::RevisionRequested,
        ])->count();
        $completedOrders = Order::where('status', OrderStatus::Completed)->count();
        $disputedOrders = Order::where('status', OrderStatus::Disputed)->count();

        $revenueTotal = Order::where('status', OrderStatus::Completed)->sum('platform_fee');
        $revenueMonth = Order::where('status', OrderStatus::Completed)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('platform_fee');

        $gigsPending = Gig::where('status', GigStatus::Pending)->count();
        $gigsPublished = Gig::where('status', GigStatus::Published)->count();

        $pendingWithdrawals = Withdrawal::where('status', WithdrawalStatus::Pending)->count();

        $recentOrders = Order::with(['client', 'freelancer', 'gig'])
            ->latest()
            ->limit(8)
            ->get();

        $pendingGigs = Gig::where('status', GigStatus::Pending)
            ->with('freelancer')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.dashboard.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'totalClients' => $totalClients,
            'totalFreelancers' => $totalFreelancers,
            'newUsersToday' => $newUsersToday,
            'totalOrders' => $totalOrders,
            'activeOrders' => $activeOrders,
            'completedOrders' => $completedOrders,
            'disputedOrders' => $disputedOrders,
            'revenueTotal' => $revenueTotal,
            'revenueMonth' => $revenueMonth,
            'gigsPending' => $gigsPending,
            'gigsPublished' => $gigsPublished,
            'pendingWithdrawals' => $pendingWithdrawals,
            'recentOrders' => $recentOrders,
            'pendingGigs' => $pendingGigs,
        ])->layout('layouts.afritask');
    }
}
