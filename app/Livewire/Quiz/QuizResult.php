<?php

namespace App\Livewire\Quiz;

use App\Models\{Order, QuizAttempt};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuizResult extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        abort_unless(
            in_array(auth()->id(), [$order->client_id, $order->freelancer_id]),
            403
        );

        $this->order = $order->load([
            'gig.quiz.questions.choices',
            'gig.freelancer',
            'client',
        ]);

        abort_unless($this->order->gig?->quiz !== null, 404);
    }

    public function render(): View
    {
        $quiz = $this->order->gig->quiz;

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('order_id', $this->order->id)
            ->whereNotNull('submitted_at')
            ->with('answers.choice', 'answers.question.choices')
            ->latest('submitted_at')
            ->first();

        return view('livewire.quiz.quiz-result', [
            'order'   => $this->order,
            'quiz'    => $quiz,
            'attempt' => $attempt,
        ])->layout('layouts.afritask');
    }
}
