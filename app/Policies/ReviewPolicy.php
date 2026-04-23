<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\{Order, User};

class ReviewPolicy
{
    public function create(User $user, Order $order): bool
    {
        return $user->id === $order->client_id
            && $order->status === OrderStatus::Completed
            && $order->review()->doesntExist();
    }
}
