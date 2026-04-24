<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Message $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $sender = $this->message->sender;

        return [
            'title'        => "Nouveau message de {$sender->name}",
            'body'         => Str::limit($this->message->body, 100),
            'action_url'   => route('inbox.show', $this->message->conversation_id),
            'action_label' => 'Voir le message',
            'type'         => 'new_message',
            'sender_id'    => $sender->id,
            'sender_name'  => $sender->name,
        ];
    }
}
