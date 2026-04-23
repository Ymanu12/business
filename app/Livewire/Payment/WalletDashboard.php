<?php

namespace App\Livewire\Payment;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class WalletDashboard extends Component
{
    public function render(): View
    {
        $user   = auth()->user();
        $wallet = $user->getOrCreateWallet();

        $transactions = $wallet->transactions()->latest()->limit(20)->get();

        return view('livewire.payment.wallet-dashboard', compact('wallet', 'transactions'))->layout('layouts.afritask');
    }
}
