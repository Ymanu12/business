<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $client = $this->order->client;

        return [
            'title'        => 'Nouvelle commande reçue',
            'body'         => "{$client->name} a commandé \"{$this->order->title}\".",
            'action_url'   => route('orders.show', $this->order->uuid),
            'action_label' => 'Voir la commande',
            'type'         => 'new_order',
            'order_id'     => $this->order->id,
            'order_title'  => $this->order->title,
            'client_name'  => $client->name,
        ];
    }
}
