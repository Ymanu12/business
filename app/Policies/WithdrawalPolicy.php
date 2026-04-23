<?php

namespace App\Policies;

use App\Models\User;

class WithdrawalPolicy
{
    public function create(User $user): bool
    {
        return $user->isFreelancer()
            && ($user->wallet?->balance ?? 0) >= config('afritask.min_withdrawal', 5000);
    }
}
