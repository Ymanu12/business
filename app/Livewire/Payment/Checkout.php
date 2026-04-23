<?php

namespace App\Livewire\Payment;

use App\Enums\{OrderStatus, PaymentMethod, PaymentStatus};
use App\Models\{Order, Payment};
use App\Services\{EscrowService, WalletService};
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Checkout extends Component
{
    public Order $order;
    public string $paymentMethod = 'wallet';

    public function mount(Order $order): void
    {
        abort_unless($order->client_id === auth()->id(), 403);
        abort_unless(in_array($order->status->value, ['pending']), 403);

        $this->order = $order->load(['gig', 'freelancer', 'package']);
    }

    public function pay(EscrowService $escrowService): void
    {
        $this->validate([
            'paymentMethod' => ['required', Rule::in(['wallet', 'mobile_money', 'stripe'])],
        ]);

        if ($this->paymentMethod === 'wallet') {
            $wallet = auth()->user()->getOrCreateWallet();

            if ($wallet->balance < $this->order->price) {
                $this->addError('paymentMethod', 'Solde insuffisant. Rechargez votre wallet.');
                return;
            }

            $ref = 'WALLET-' . strtoupper(uniqid());
            WalletService::debit(
                $wallet,
                $this->order->price,
                "Paiement commande #{$this->order->uuid}",
                $ref
            );

            Payment::create([
                'order_id'       => $this->order->id,
                'user_id'        => auth()->id(),
                'amount'         => $this->order->price,
                'currency'       => $this->order->currency,
                'method'         => PaymentMethod::Wallet,
                'status'         => PaymentStatus::Paid,
                'transaction_ref'=> $ref,
            ]);

            $escrowService->hold($this->order);

            $this->order->update([
                'status' => OrderStatus::InProgress,
            ]);

            session()->flash('success', 'Paiement effectué. Le freelance a été notifié.');
            $this->redirect(route('orders.show', $this->order->uuid), navigate: true);
        } else {
            session()->flash('error', 'Ce mode de paiement sera disponible prochainement.');
        }
    }

    public function render(): View
    {
        $wallet = auth()->user()->getOrCreateWallet();

        return view('livewire.payment.checkout', [
            'order'  => $this->order,
            'wallet' => $wallet,
        ])->layout('layouts.afritask');
    }
}
