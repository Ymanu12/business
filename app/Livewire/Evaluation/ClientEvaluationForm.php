<?php

namespace App\Livewire\Evaluation;

use App\Models\{ClientEvaluation, Order};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class ClientEvaluationForm extends Component
{
    public Order $order;

    public int    $rating    = 5;
    public string $comment   = '';
    public bool   $withCert  = true;

    public function mount(Order $order): void
    {
        abort_unless($order->freelancer_id === auth()->id(), 403);
        abort_unless($order->clientEvaluation === null, 403);

        $this->order = $order->load(['client', 'gig', 'clientEvaluation']);
    }

    public function submit(): void
    {
        $this->validate([
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'comment'  => ['nullable', 'string', 'max:1000'],
            'withCert' => ['boolean'],
        ]);

        $ref = $this->withCert
            ? 'CERT-' . strtoupper(Str::random(10)) . '-' . now()->year
            : null;

        ClientEvaluation::create([
            'order_id'            => $this->order->id,
            'freelancer_id'       => auth()->id(),
            'client_id'           => $this->order->client_id,
            'rating'              => $this->rating,
            'comment'             => $this->comment ?: null,
            'certificate_issued'  => $this->withCert,
            'certificate_ref'     => $ref,
        ]);

        $this->redirect(route('orders.show', $this->order->uuid), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.evaluation.client-evaluation-form', [
            'order' => $this->order,
        ])->layout('layouts.afritask');
    }
}
