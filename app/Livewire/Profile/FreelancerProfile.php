<?php

namespace App\Livewire\Profile;

use App\Models\{Conversation, User};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FreelancerProfile extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load([
            'freelancerProfile',
            'badges',
            'gigs' => fn ($q) => $q->published()->with(['category'])->withCount('reviews')->latest(),
        ]);
    }

    public function contacter(): void
    {
        $authUser = auth()->user();

        abort_unless($authUser && $authUser->id !== $this->user->id, 403);

        $conversation = $authUser->conversations()
            ->whereHas('users', fn ($q) => $q->where('users.id', $this->user->id))
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([$authUser->id, $this->user->id]);
        }

        $this->redirectRoute('inbox.show', $conversation->id, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.profile.freelancer-profile', ['user' => $this->user])->layout('layouts.afritask');
    }
}
