<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\{Order, User};

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $order->isOwnedBy($user) || $user->isAdmin();
    }

    public function deliver(User $user, Order $order): bool
    {
        return $user->id === $order->freelancer_id
            && $order->status === OrderStatus::InProgress;
    }

    public function requestRevision(User $user, Order $order): bool
    {
        return $user->id === $order->client_id
            && $order->status === OrderStatus::Delivered
            && $order->hasRevisionsLeft();
    }

    public function complete(User $user, Order $order): bool
    {
        return $user->id === $order->client_id
            && $order->status === OrderStatus::Delivered;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $order->isOwnedBy($user)
            && $order->status->isCancellable();
    }

    public function dispute(User $user, Order $order): bool
    {
        return $order->isOwnedBy($user)
            && in_array($order->status, [OrderStatus::Delivered, OrderStatus::InProgress])
            && $order->dispute()->doesntExist();
    }

    public function review(User $user, Order $order): bool
    {
        return $user->id === $order->client_id
            && $order->status === OrderStatus::Completed
            && $order->review()->doesntExist();
    }
}
