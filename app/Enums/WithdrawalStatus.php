<?php

namespace App\Enums;

enum WithdrawalStatus: string
{
    case Pending   = 'pending';
    case Approved  = 'approved';
    case Processed = 'processed';
    case Rejected  = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'En attente',
            self::Approved  => 'Approuvé',
            self::Processed => 'Traité',
            self::Rejected  => 'Refusé',
        };
    }
}
