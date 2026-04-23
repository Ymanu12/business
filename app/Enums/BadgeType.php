<?php

namespace App\Enums;

enum BadgeType: string
{
    case NewSeller        = 'new_seller';
    case QuickResponse    = 'quick_response';
    case Verified         = 'verified';
    case Level1           = 'level1';
    case Level2           = 'level2';
    case TopRated         = 'top_rated';
    case HighSatisfaction = 'high_satisfaction';

    public function label(): string
    {
        return match ($this) {
            self::NewSeller        => 'Nouveau vendeur',
            self::QuickResponse    => 'Réponse rapide',
            self::Verified         => 'Identité vérifiée',
            self::Level1           => 'Vendeur Niveau 1',
            self::Level2           => 'Vendeur Niveau 2',
            self::TopRated         => 'Top Vendeur',
            self::HighSatisfaction => 'Haute satisfaction',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::NewSeller        => '🆕',
            self::QuickResponse    => '⚡',
            self::Verified         => '✓',
            self::Level1           => '⭐',
            self::Level2           => '⭐⭐',
            self::TopRated         => '🏆',
            self::HighSatisfaction => '💎',
        };
    }
}
