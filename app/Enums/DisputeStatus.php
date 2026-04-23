<?php

namespace App\Enums;

enum DisputeStatus: string
{
    case Open                = 'open';
    case Investigating       = 'investigating';
    case ResolvedClient      = 'resolved_client';
    case ResolvedFreelancer  = 'resolved_freelancer';
    case ResolvedPartial     = 'resolved_partial';

    public function label(): string
    {
        return match ($this) {
            self::Open               => 'Ouvert',
            self::Investigating      => 'En cours d\'instruction',
            self::ResolvedClient     => 'Résolu (client)',
            self::ResolvedFreelancer => 'Résolu (freelance)',
            self::ResolvedPartial    => 'Résolu (partiel)',
        };
    }
}
