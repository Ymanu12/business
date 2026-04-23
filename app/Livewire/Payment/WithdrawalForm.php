<?php

namespace App\Livewire\Payment;

use App\Enums\WithdrawalStatus;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;


class WithdrawalForm extends Component
{
    public string $amount = '';
    public string $method = 'mobile_money';
    public string $accountDetails = '';

    public function submit(): void
    {
        $wallet = auth()->user()->getOrCreateWallet();
        $min    = config('afritask.min_withdrawal', 5000);

        $validated = $this->validate([
            'amount'         => ['required', 'numeric', "min:{$min}", "max:{$wallet->balance}"],
            'method'         => ['required', Rule::in(['mobile_money', 'bank_transfer', 'paypal'])],
            'accountDetails' => ['required', 'string', 'max:500'],
        ]);

        $ref = 'WD-' . strtoupper(uniqid());
        WalletService::debit(
            $wallet,
            (float) $validated['amount'],
            'Demande de retrait',
            $ref
        );

        Withdrawal::create([
            'user_id'        => auth()->id(),
            'amount'         => $validated['amount'],
            'currency'       => $wallet->currency,
            'method'         => $validated['method'],
            'account_details'=> ['info' => $validated['accountDetails']],
            'status'         => WithdrawalStatus::Pending,
        ]);

        session()->flash('success', 'Votre demande de retrait a été soumise. Traitement sous 24-48h.');
        $this->redirect(route('wallet'), navigate: true);
    }

    public function render(): View
    {
        $wallet = auth()->user()->getOrCreateWallet();

        return view('livewire.payment.withdrawal-form', compact('wallet'))->layout('layouts.afritask');
    }
}
