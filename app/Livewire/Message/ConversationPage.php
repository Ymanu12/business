<?php

namespace App\Livewire\Message;

use App\Models\{Conversation, Message};
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ConversationPage extends Component
{
    public Conversation $conversation;
    public string $newMessage = '';
    public int $lastMessageId = 0;

    public function mount(Conversation $conversation): void
    {
        abort_unless(
            $conversation->users->pluck('id')->contains(auth()->id()),
            403
        );

        $this->conversation = $conversation->load(['users', 'messages.sender']);
        $this->lastMessageId = $this->conversation->messages->last()?->id ?? 0;

        $this->markRead();
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => ['required', 'string', 'max:2000']]);

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => auth()->id(),
            'body'            => $this->newMessage,
        ]);

        $this->conversation->touch();
        $this->newMessage = '';
        $this->lastMessageId = $message->id;

        $this->conversation->load('messages.sender');
        $this->dispatch('message-sent');
    }

    public function refreshMessages(): void
    {
        $this->conversation->load('messages.sender');

        $newLast = $this->conversation->messages->last()?->id ?? 0;
        if ($newLast > $this->lastMessageId) {
            $this->lastMessageId = $newLast;
            $this->markRead();
            $this->dispatch('new-message-arrived');
        }
    }

    private function markRead(): void
    {
        $this->conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render(): View
    {
        return view('livewire.message.conversation-page', [
            'messages' => $this->conversation->messages,
            'other'    => $this->conversation->otherUser(),
        ])->layout('layouts.afritask');
    }
}
