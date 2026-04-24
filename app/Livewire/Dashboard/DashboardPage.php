<?php

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class DashboardPage extends Component
{
    public function mount(): void
    {
        $user = auth()->user();

        if (! $user) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        if ($user?->isAdmin()) {
            $this->redirectRoute('dashboard.admin', navigate: true);

            return;
        }

        if ($user?->isFreelancer()) {
            if (! $user->freelancerProfile()->exists()) {
                $this->redirectRoute('freelancer.onboarding', navigate: true);

                return;
            }

            $this->redirectRoute('dashboard.freelancer', navigate: true);

            return;
        }

        if ($user->isClient()) {
            $this->redirectRoute('dashboard.client', navigate: true);

            return;
        }

        session()->flash('error', 'Votre compte ne dispose pas encore d\'un tableau de bord.');

        $this->redirectRoute('home', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.dashboard.dashboard-page')
            ->layout('layouts.afritask');
    }
}
