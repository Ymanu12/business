<?php

namespace App\Livewire\Message;

use App\Events\MessageSent;
use App\Events\NotificationSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewMessageNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConversationPage extends Component
{
    use WithFileUploads;

    public Conversation $conversation;

    public string $newMessage = '';

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[] */
    public array $attachments = [];

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
        $this->validate([
            'newMessage' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => [
                'file',
                'max:51200',
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar',
            ],
        ]);

        if (blank($this->newMessage) && empty($this->attachments)) {
            return;
        }

        $storedAttachments = [];
        foreach ($this->attachments as $file) {
            $path = $file->storePublicly("chat/{$this->conversation->id}", 'public');
            $storedAttachments[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ];
        }

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => auth()->id(),
            'body' => filled($this->newMessage) ? $this->newMessage : null,
            'attachments' => empty($storedAttachments) ? null : $storedAttachments,
        ]);

        $this->conversation->touch();
        $message->load(['sender', 'conversation.users']);

        $bodyPreview = filled($this->newMessage)
            ? Str::limit($this->newMessage, 100)
            : count($storedAttachments) . ' fichier(s) joint(s)';

        $this->newMessage = '';
        $this->attachments = [];
        $this->lastMessageId = $message->id;

        $this->conversation->load('messages.sender');
        broadcast(new MessageSent($message));
        $this->dispatch('message-sent');

        $recipients = $this->conversation->users->where('id', '!=', auth()->id());
        foreach ($recipients as $recipient) {
            $recipient->notify(new NewMessageNotification($message));
            broadcast(new NotificationSent(
                $recipient->id,
                'Nouveau message de ' . auth()->user()->name,
                $bodyPreview,
            ));
        }
    }

    public function removeAttachment(int $index): void
    {
        array_splice($this->attachments, $index, 1);
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
            'other' => $this->conversation->otherUser(),
        ])->layout('layouts.afritask');
    }
}
