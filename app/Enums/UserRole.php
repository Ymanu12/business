<?php

namespace App\Enums;

enum UserRole: string
{
    case Client     = 'client';
    case Freelancer = 'freelance';
    case Agency     = 'agency';
    case Admin      = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Client     => 'Client',
            self::Freelancer => 'Freelance',
            self::Agency     => 'Agence',
            self::Admin      => 'Administrateur',
        };
    }
}
