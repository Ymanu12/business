<?php

namespace App\Notifications;

use App\Models\Gig;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GigSubmittedForReviewNotification extends Notification
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
        return [
            'title' => 'Nouveau service a valider',
            'body' => "{$this->gig->freelancer->name} a soumis le service \"{$this->gig->title}\" pour revision.",
            'action_url' => route('dashboard.admin'),
            'action_label' => 'Voir les services en attente',
            'gig_id' => $this->gig->id,
            'gig_title' => $this->gig->title,
            'type' => 'gig_submitted_for_review',
        ];
    }
}
