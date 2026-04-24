<?php

namespace App\Livewire\Message;

use App\Enums\UserRole;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;

class Inbox extends Component
{
    public function startConversation(int $userId): void
    {
        $authUser = auth()->user();

        abort_unless($authUser && $authUser->id !== $userId, 403);

        $targetUser = User::query()
            ->whereKey($userId)
            ->where('is_active', true)
            ->firstOrFail();

        $conversation = Conversation::findOrCreateBetweenUsers($authUser->id, $targetUser->id);

        $this->redirectRoute('inbox.show', $conversation->id, navigate: true);
    }

    public function render(): View
    {
        $user = auth()->user();

        $conversations = $user
            ->conversations()
            ->with(['users', 'lastMessage'])
            ->latest('updated_at')
            ->get();

        $suggestedContacts = $this->suggestedContacts($user->id, $conversations);

        return view('livewire.message.inbox', compact('conversations', 'suggestedContacts'))->layout('layouts.afritask');
    }

    /**
     * @param  Collection<int, Conversation>  $conversations
     * @return Collection<int, User>
     */
    private function suggestedContacts(int $currentUserId, Collection $conversations): Collection
    {
        $existingContactIds = $conversations
            ->flatMap(fn (Conversation $conversation) => $conversation->users->pluck('id'))
            ->reject(fn (int $userId) => $userId === $currentUserId)
            ->unique()
            ->values()
            ->all();

        $baseQuery = User::query()
            ->where('id', '!=', $currentUserId)
            ->where('is_active', true)
            ->whereNotIn('id', $existingContactIds)
            ->select(['id', 'name', 'username', 'avatar', 'role']);

        $currentUser = auth()->user();

        if ($currentUser?->isFreelancer()) {
            return (clone $baseQuery)
                ->where('role', UserRole::Admin)
                ->orderBy('name')
                ->limit(3)
                ->get();
        }

        return (clone $baseQuery)
            ->where('role', UserRole::Freelancer)
            ->whereHas('gigs', fn (Builder $query) => $query->published())
            ->with('freelancerProfile')
            ->orderBy('name')
            ->limit(3)
            ->get();
    }
}
