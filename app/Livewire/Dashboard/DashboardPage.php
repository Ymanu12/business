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
            $this->redirectRoute('login', [], false, true);

            return;
        }

        if ($user?->isAdmin()) {
            $this->redirectRoute('dashboard.admin', [], false, true);

            return;
        }

        if ($user?->isFreelancer()) {
            if (! $user->freelancerProfile()->exists()) {
                $this->redirectRoute('freelancer.onboarding', [], false, true);

                return;
            }

            $this->redirectRoute('dashboard.freelancer', [], false, true);

            return;
        }

        if ($user->isClient()) {
            $this->redirectRoute('dashboard.client', [], false, true);

            return;
        }

        session()->flash('error', 'Votre compte ne dispose pas encore d\'un tableau de bord.');

        $this->redirectRoute('home', [], false, true);
    }

    public function render(): View
    {
        return view('livewire.dashboard.dashboard-page')
            ->layout('layouts.afritask');
    }
}
