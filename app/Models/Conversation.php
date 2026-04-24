<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    public static function findOrCreateBetweenUsers(int $firstUserId, int $secondUserId): self
    {
        $conversation = static::query()
            ->whereHas('users', fn ($query) => $query->where('users.id', $firstUserId))
            ->whereHas('users', fn ($query) => $query->where('users.id', $secondUserId))
            ->first();

        if ($conversation) {
            return $conversation;
        }

        $conversation = static::query()->create();
        $conversation->users()->attach([$firstUserId, $secondUserId]);

        return $conversation->load('users');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user')
            ->withPivot('last_read_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function otherUser(?int $currentUserId = null): ?User
    {
        $uid = $currentUserId ?? auth()->id();

        return $this->users->firstWhere('id', '!=', $uid);
    }

    public function unreadCount(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
