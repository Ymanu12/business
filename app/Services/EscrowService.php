<?php

namespace App\Services;

use App\Enums\{EscrowStatus, OrderStatus};
use App\Models\{Commission, EscrowAccount, Order};
use Illuminate\Support\Facades\DB;

class EscrowService
{
    public function hold(Order $order): EscrowAccount
    {
        $rate             = $this->getCommissionRate($order->gig->category_id);
        $platformFee      = round($order->price * $rate, 2);
        $freelancerAmount = round($order->price - $platformFee, 2);

        $order->update([
            'platform_fee'      => $platformFee,
            'freelancer_amount' => $freelancerAmount,
        ]);

        return EscrowAccount::create([
            'order_id'         => $order->id,
            'amount'           => $order->price,
            'platform_fee'     => $platformFee,
            'freelancer_amount'=> $freelancerAmount,
            'status'           => EscrowStatus::Held,
            'held_at'          => now(),
        ]);
    }

    public function release(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $escrow     = $order->escrow;
            $freelancer = $order->freelancer;

            $wallet = $freelancer->getOrCreateWallet();

            WalletService::credit(
                wallet: $wallet,
                amount: $escrow->freelancer_amount,
                description: "Paiement commande #{$order->uuid}",
                reference: $order->uuid,
                metadata: ['order_id' => $order->id],
            );

            $escrow->update([
                'status'      => EscrowStatus::Released,
                'released_at' => now(),
            ]);

            $order->update([
                'status'       => OrderStatus::Completed,
                'completed_at' => now(),
            ]);

            $profile = $freelancer->freelancerProfile;
            if ($profile) {
                $profile->increment('completed_orders');
                $profile->increment('total_earnings', $escrow->freelancer_amount);
            }
        });
    }

    public function refund(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $escrow = $order->escrow;
            $client = $order->client;

            $wallet = $client->getOrCreateWallet();

            WalletService::credit(
                wallet: $wallet,
                amount: $escrow->amount,
                description: "Remboursement commande #{$order->uuid}",
                reference: $order->uuid,
                metadata: ['order_id' => $order->id],
            );

            $escrow->update(['status' => EscrowStatus::Refunded]);
            $order->update(['status' => OrderStatus::Refunded]);
        });
    }

    private function getCommissionRate(int $categoryId): float
    {
        return Commission::where('category_id', $categoryId)->where('is_active', true)->value('rate')
            ?? Commission::whereNull('category_id')->where('is_active', true)->value('rate')
            ?? config('afritask.default_commission', 0.20);
    }
}
