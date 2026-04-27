<?php

namespace App\Livewire\Evaluation;

use App\Models\{ClientEvaluation, Order};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CertificatePage extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless(
            in_array(auth()->id(), [$order->client_id, $order->freelancer_id]),
            403
        );

        $this->order = $order->load([
            'client',
            'freelancer',
            'gig',
            'clientEvaluation',
        ]);

        abort_unless($this->order->clientEvaluation?->certificate_issued, 404);
    }

    public function render(): View
    {
        return view('livewire.evaluation.certificate-page', [
            'order'      => $this->order,
            'evaluation' => $this->order->clientEvaluation,
        ])->layout('layouts.afritask');
    }
}
