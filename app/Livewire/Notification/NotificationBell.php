<?php

namespace App\Livewire\Notification;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $userId;
    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->userId     = auth()->id();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    #[On('echo-private:users.{userId},notification.sent')]
    public function onNotificationReceived(): void
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->unreadCount = 0;
    }

    public function render(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.notification.notification-bell', compact('notifications'));
    }
}
