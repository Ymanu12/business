<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending           = 'pending';
    case Paid              = 'paid';
    case InProgress        = 'in_progress';
    case Delivered         = 'delivered';
    case RevisionRequested = 'revision_requested';
    case Completed         = 'completed';
    case Cancelled         = 'cancelled';
    case Disputed          = 'disputed';
    case Refunded          = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending           => 'En attente',
            self::Paid              => 'Payé',
            self::InProgress        => 'En cours',
            self::Delivered         => 'Livré',
            self::RevisionRequested => 'Révision demandée',
            self::Completed         => 'Terminé',
            self::Cancelled         => 'Annulé',
            self::Disputed          => 'Litige',
            self::Refunded          => 'Remboursé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending           => 'yellow',
            self::Paid              => 'blue',
            self::InProgress        => 'blue',
            self::Delivered         => 'purple',
            self::RevisionRequested => 'orange',
            self::Completed         => 'green',
            self::Cancelled         => 'red',
            self::Disputed          => 'red',
            self::Refunded          => 'gray',
        };
    }

    public function tailwindBg(): string
    {
        return match ($this) {
            self::Pending           => 'bg-yellow-100 text-yellow-800',
            self::Paid              => 'bg-blue-100 text-blue-800',
            self::InProgress        => 'bg-blue-100 text-blue-800',
            self::Delivered         => 'bg-purple-100 text-purple-800',
            self::RevisionRequested => 'bg-orange-100 text-orange-800',
            self::Completed         => 'bg-green-100 text-green-800',
            self::Cancelled         => 'bg-red-100 text-red-800',
            self::Disputed          => 'bg-red-100 text-red-800',
            self::Refunded          => 'bg-gray-100 text-gray-800',
        };
    }

    public function isCancellable(): bool
    {
        return in_array($this, [self::Pending, self::Paid]);
    }

    public function isActive(): bool
    {
        return in_array($this, [self::Paid, self::InProgress, self::Delivered, self::RevisionRequested]);
    }
}
