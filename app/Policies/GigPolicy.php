<?php

namespace App\Policies;

use App\Enums\GigStatus;
use App\Models\{Gig, User};

class GigPolicy
{
    public function viewAny(?User $user): bool { return true; }

    public function view(?User $user, Gig $gig): bool
    {
        if ($gig->status === GigStatus::Published) return true;
        return $user?->id === $gig->freelancer_id || $user?->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isFreelancer() && $user->isActive();
    }

    public function update(User $user, Gig $gig): bool
    {
        return $user->id === $gig->freelancer_id && $gig->status !== GigStatus::Rejected;
    }

    public function delete(User $user, Gig $gig): bool
    {
        return $user->id === $gig->freelancer_id
            && $gig->orders()->active()->doesntExist();
    }

    public function publish(User $user, Gig $gig): bool
    {
        return $user->id === $gig->freelancer_id
            && $gig->status === GigStatus::Draft
            && $gig->packages()->count() >= 1;
    }
}
