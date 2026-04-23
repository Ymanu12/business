<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'tagline', 'skills', 'languages', 'education', 'certifications',
    'portfolio_url', 'linkedin_url', 'github_url', 'twitter_url',
    'availability', 'response_time', 'response_rate', 'completed_orders',
    'cancelled_orders', 'avg_rating', 'reviews_count', 'total_earnings',
    'seller_level', 'id_verified', 'id_document',
])]
class FreelancerProfile extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'skills'         => 'array',
            'languages'      => 'array',
            'education'      => 'array',
            'certifications' => 'array',
            'id_verified'    => 'boolean',
            'avg_rating'     => 'float',
            'total_earnings' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function levelLabel(): string
    {
        return match ($this->seller_level) {
            'level1'    => 'Vendeur Niveau 1',
            'level2'    => 'Vendeur Niveau 2',
            'top_rated' => 'Top Vendeur',
            default     => 'Nouveau vendeur',
        };
    }

    public function completionRate(): int
    {
        $total = $this->completed_orders + $this->cancelled_orders;
        if ($total === 0) return 100;

        return (int) round(($this->completed_orders / $total) * 100);
    }
}
