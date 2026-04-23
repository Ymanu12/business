<?php

namespace App\Livewire\Message;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Inbox extends Component
{
    public function render(): View
    {
        $conversations = auth()->user()
            ->conversations()
            ->with(['users', 'lastMessage'])
            ->latest('updated_at')
            ->get();

        return view('livewire.message.inbox', compact('conversations'))->layout('layouts.afritask');
    }
}
