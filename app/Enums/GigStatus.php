<?php

namespace App\Enums;

enum GigStatus: string
{
    case Draft     = 'draft';
    case Pending   = 'pending';
    case Published = 'published';
    case Rejected  = 'rejected';
    case Paused    = 'paused';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Brouillon',
            self::Pending   => 'En attente',
            self::Published => 'Publié',
            self::Rejected  => 'Rejeté',
            self::Paused    => 'Suspendu',
        };
    }

    public function tailwindBg(): string
    {
        return match ($this) {
            self::Draft     => 'bg-gray-100 text-gray-700',
            self::Pending   => 'bg-yellow-100 text-yellow-800',
            self::Published => 'bg-green-100 text-green-800',
            self::Rejected  => 'bg-red-100 text-red-800',
            self::Paused    => 'bg-orange-100 text-orange-800',
        };
    }
}
