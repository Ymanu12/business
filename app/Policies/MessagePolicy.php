<?php

namespace App\Policies;

use App\Models\{Conversation, User};

class MessagePolicy
{
    public function send(User $user, Conversation $conversation): bool
    {
        return $conversation->users()->where('user_id', $user->id)->exists();
    }
}
