<?php

namespace App\Services;

use App\Enums\BadgeType;
use App\Models\{Badge, User};

class BadgeService
{
    public static function evaluate(User $user): void
    {
        if (! $user->isFreelancer()) return;

        $profile = $user->freelancerProfile;
        if (! $profile) return;

        // Badge Quick Response (réponse < 60 min, taux >= 90%)
        if ($profile->response_time > 0 && $profile->response_time <= 60 && $profile->response_rate >= 90) {
            self::award($user, BadgeType::QuickResponse);
        }

        // Badge Level 1 (10+ commandes, note >= 4.0)
        if ($profile->completed_orders >= 10 && $profile->avg_rating >= 4.0) {
            self::award($user, BadgeType::Level1);
            $profile->update(['seller_level' => 'level1']);
        }

        // Badge Level 2 (50+ commandes, note >= 4.5)
        if ($profile->completed_orders >= 50 && $profile->avg_rating >= 4.5) {
            self::award($user, BadgeType::Level2);
            $profile->update(['seller_level' => 'level2']);
        }

        // Badge Top Rated (100+ commandes, note >= 4.8, réponse >= 95%)
        if ($profile->completed_orders >= 100
            && $profile->avg_rating >= 4.8
            && $profile->response_rate >= 95) {
            self::award($user, BadgeType::TopRated);
            $profile->update(['seller_level' => 'top_rated']);
        }

        // Badge High Satisfaction (20+ avis, note >= 4.5)
        if ($profile->reviews_count >= 20 && $profile->avg_rating >= 4.5) {
            self::award($user, BadgeType::HighSatisfaction);
        }

        // Badge Verified (identité vérifiée)
        if ($profile->id_verified && $user->is_verified) {
            self::award($user, BadgeType::Verified);
        }
    }

    public static function updateRating(User $freelancer): void
    {
        $avg = $freelancer->reviewsReceived()
            ->where('is_hidden', false)
            ->avg('rating') ?? 0;

        $count = $freelancer->reviewsReceived()
            ->where('is_hidden', false)
            ->count();

        $profile = $freelancer->freelancerProfile;
        if ($profile) {
            $profile->update([
                'avg_rating'    => round($avg, 2),
                'reviews_count' => $count,
            ]);
        }

        self::evaluate($freelancer);
    }

    private static function award(User $user, BadgeType $type): void
    {
        $badge = Badge::where('type', $type->value)->first();
        if (! $badge) return;

        $user->badges()->syncWithoutDetaching([
            $badge->id => ['earned_at' => now()],
        ]);
    }
}
