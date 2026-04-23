<?php

namespace App\Services;

use App\Models\{Wallet, WalletTransaction};
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletService
{
    public static function credit(
        Wallet $wallet,
        float $amount,
        string $description,
        string $reference,
        array $metadata = []
    ): WalletTransaction {
        return DB::transaction(function () use ($wallet, $amount, $description, $reference, $metadata) {
            $wallet->lockForUpdate()->find($wallet->id);
            $before = $wallet->balance;

            $wallet->increment('balance', $amount);
            $wallet->refresh();

            return WalletTransaction::create([
                'wallet_id'      => $wallet->id,
                'type'           => 'credit',
                'amount'         => $amount,
                'balance_before' => $before,
                'balance_after'  => $wallet->balance,
                'description'    => $description,
                'reference'      => $reference,
                'metadata'       => $metadata,
            ]);
        });
    }

    public static function debit(
        Wallet $wallet,
        float $amount,
        string $description,
        string $reference,
        array $metadata = []
    ): WalletTransaction {
        return DB::transaction(function () use ($wallet, $amount, $description, $reference, $metadata) {
            $wallet->lockForUpdate()->find($wallet->id);

            if ($wallet->balance < $amount) {
                throw new RuntimeException('Solde insuffisant.');
            }

            $before = $wallet->balance;
            $wallet->decrement('balance', $amount);
            $wallet->refresh();

            return WalletTransaction::create([
                'wallet_id'      => $wallet->id,
                'type'           => 'debit',
                'amount'         => $amount,
                'balance_before' => $before,
                'balance_after'  => $wallet->balance,
                'description'    => $description,
                'reference'      => $reference,
                'metadata'       => $metadata,
            ]);
        });
    }

    public static function addPending(Wallet $wallet, float $amount): void
    {
        $wallet->increment('pending_balance', $amount);
    }

    public static function releasePending(Wallet $wallet, float $amount): void
    {
        $wallet->decrement('pending_balance', $amount);
    }
}
