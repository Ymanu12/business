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

    // Mobile Money
    public string $momoPhone   = '';
    public string $momoPin     = '';
    public string $momoNetwork = 'mtn_momo';

    // Carte bancaire
    public string $cardNumber  = '';
    public string $cardExpiry  = '';
    public string $cardCvv     = '';
    public string $cardHolder  = '';

    // État de traitement
    public bool   $processing  = false;
    public string $step        = 'form'; // form | processing | done

    public function mount(Order $order): void
    {
        abort_unless($order->client_id === auth()->id(), 403);
        abort_unless($order->status === OrderStatus::Pending, 403);

        $this->order       = $order->load(['gig', 'freelancer', 'package']);
        $this->cardHolder  = auth()->user()->name;
    }

    public function updatedPaymentMethod(): void
    {
        $this->step = 'form';
        $this->resetErrorBag();
    }

    public function pay(EscrowService $escrow): void
    {
        $this->validate([
            'paymentMethod' => ['required', Rule::in(['wallet', 'mobile_money', 'stripe'])],
        ]);

        match ($this->paymentMethod) {
            'wallet'       => $this->payWithWallet($escrow),
            'mobile_money' => $this->payWithMobileMoney($escrow),
            'stripe'       => $this->payWithCard($escrow),
        };
    }

    // ── Paiement Wallet ───────────────────────────────────────────

    private function payWithWallet(EscrowService $escrow): void
    {
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

        $this->finalizePayment($escrow, PaymentMethod::Wallet, $ref, [
            'wallet_balance_before' => $wallet->balance,
        ]);
    }

    // ── Simulation Mobile Money ───────────────────────────────────

    private function payWithMobileMoney(EscrowService $escrow): void
    {
        $this->validate([
            'momoPhone'   => ['required', 'string', 'min:8', 'max:15', 'regex:/^[0-9+\s]+$/'],
            'momoPin'     => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/'],
            'momoNetwork' => ['required', Rule::in(['mtn_momo', 'orange_money', 'flooz', 'tmoney', 'moov_money'])],
        ], [
            'momoPhone.required' => 'Le numéro de téléphone est obligatoire.',
            'momoPhone.regex'    => 'Numéro de téléphone invalide.',
            'momoPin.required'   => 'Le code PIN est obligatoire.',
            'momoPin.size'       => 'Le code PIN doit être à 4 chiffres.',
            'momoPin.regex'      => 'Le code PIN ne doit contenir que des chiffres.',
        ]);

        $method = PaymentMethod::from($this->momoNetwork);
        $last4  = substr(preg_replace('/\D/', '', $this->momoPhone), -4);
        $ref    = strtoupper($method->name) . '-' . $last4 . '-' . strtoupper(uniqid());

        $this->finalizePayment($escrow, $method, $ref, [
            'phone'   => $this->momoPhone,
            'network' => $this->momoNetwork,
        ]);
    }

    // ── Simulation Carte bancaire ─────────────────────────────────

    private function payWithCard(EscrowService $escrow): void
    {
        $this->validate([
            'cardNumber' => ['required', 'string', 'regex:/^[0-9\s]{13,19}$/'],
            'cardExpiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'],
            'cardCvv'    => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            'cardHolder' => ['required', 'string', 'min:3', 'max:100'],
        ], [
            'cardNumber.required' => 'Le numéro de carte est obligatoire.',
            'cardNumber.regex'    => 'Numéro de carte invalide.',
            'cardExpiry.required' => 'La date d\'expiration est obligatoire.',
            'cardExpiry.regex'    => 'Format d\'expiration invalide (MM/AA).',
            'cardCvv.required'    => 'Le CVV est obligatoire.',
            'cardCvv.regex'       => 'CVV invalide.',
        ]);

        $last4 = substr(preg_replace('/\D/', '', $this->cardNumber), -4);
        $ref   = 'CARD-' . $last4 . '-' . strtoupper(uniqid());

        $this->finalizePayment($escrow, PaymentMethod::Stripe, $ref, [
            'card_last4'  => $last4,
            'card_holder' => $this->cardHolder,
            'card_expiry' => $this->cardExpiry,
        ]);
    }

    // ── Finalisation commune ──────────────────────────────────────

    private function finalizePayment(EscrowService $escrow, PaymentMethod $method, string $ref, array $meta = []): void
    {
        Payment::create([
            'order_id'        => $this->order->id,
            'user_id'         => auth()->id(),
            'amount'          => $this->order->price,
            'currency'        => $this->order->currency,
            'method'          => $method,
            'status'          => PaymentStatus::Paid,
            'transaction_ref' => $ref,
            'paid_at'         => now(),
            'metadata'        => array_merge($meta, ['simulated' => true]),
        ]);

        $escrow->hold($this->order);

        $this->order->update([
            'status'          => OrderStatus::InProgress,
            'platform_fee'    => round($this->order->price * 0.10, 2),
            'freelancer_amount'=> round($this->order->price * 0.90, 2),
        ]);

        $this->redirect(route('orders.receipt', $this->order->uuid), navigate: true);
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
