<?php

namespace App\Livewire\Dashboard;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class DashboardPage extends Component
{
    public function mount(): void
    {
        $user = auth()->user();

        if ($user?->isFreelancer()) {
            if (! $user->freelancerProfile()->exists()) {
                $this->redirectRoute('freelancer.onboarding', navigate: true);

                return;
            }

            $this->redirectRoute('dashboard.freelancer', navigate: true);

            return;
        }

        $this->redirectRoute('dashboard.client', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.dashboard.dashboard-page')
            ->layout('layouts.afritask');
    }
}
