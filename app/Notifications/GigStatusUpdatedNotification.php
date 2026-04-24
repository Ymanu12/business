<?php

namespace App\Notifications;

use App\Enums\GigStatus;
use App\Models\Gig;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GigStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Gig $gig) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $isPublished = $this->gig->status === GigStatus::Published;

        return [
            'title' => $isPublished ? 'Service publie' : 'Service rejete',
            'body' => $isPublished
                ? "Votre service \"{$this->gig->title}\" est maintenant en ligne."
                : "Votre service \"{$this->gig->title}\" a ete rejete par l'administration.",
            'action_url' => route('seller.gigs.edit', $this->gig->id),
            'action_label' => $isPublished ? 'Voir mon service' : 'Corriger le service',
            'gig_id' => $this->gig->id,
            'gig_title' => $this->gig->title,
            'gig_status' => $this->gig->status->value,
            'type' => 'gig_status_updated',
        ];
    }
}
