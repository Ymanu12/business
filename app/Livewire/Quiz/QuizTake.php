<?php

namespace App\Livewire\Quiz;

use App\Models\{Order, QuizAnswer, QuizAttempt};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuizTake extends Component
{
    public Order $order;
    public array $answers = []; // [question_id => choice_id|text]

    public function mount(Order $order): void
    {
        abort_unless($order->client_id === auth()->id(), 403);
        abort_unless($order->payment !== null, 403);

        $this->order = $order->load([
            'gig.quiz.questions.choices',
            'gig.freelancer',
        ]);

        abort_unless($this->order->gig?->quiz !== null, 404);

        // Pre-fill if a previous unsubmitted attempt exists
        $attempt = QuizAttempt::where('quiz_id', $this->order->gig->quiz->id)
            ->where('order_id', $this->order->id)
            ->where('user_id', auth()->id())
            ->whereNull('submitted_at')
            ->with('answers')
            ->first();

        if ($attempt) {
            foreach ($attempt->answers as $ans) {
                $this->answers[$ans->question_id] = $ans->choice_id ?? $ans->text_answer ?? '';
            }
        }
    }

    public function submit(): void
    {
        $quiz      = $this->order->gig->quiz;
        $questions = $quiz->questions->load('choices');

        // Delete any unsubmitted attempt
        QuizAttempt::where('quiz_id', $quiz->id)
            ->where('order_id', $this->order->id)
            ->where('user_id', auth()->id())
            ->whereNull('submitted_at')
            ->delete();

        $attempt = QuizAttempt::create([
            'quiz_id'      => $quiz->id,
            'order_id'     => $this->order->id,
            'user_id'      => auth()->id(),
            'submitted_at' => now(),
        ]);

        $correct = 0;
        $total   = 0;

        foreach ($questions as $question) {
            $given = $this->answers[$question->id] ?? null;

            if ($question->type === 'multiple_choice') {
                $total++;
                $choiceId  = $given ? (int) $given : null;
                $isCorrect = $choiceId
                    ? $question->choices->firstWhere('id', $choiceId)?->is_correct ?? false
                    : false;

                QuizAnswer::create([
                    'attempt_id'  => $attempt->id,
                    'question_id' => $question->id,
                    'choice_id'   => $choiceId,
                    'is_correct'  => $isCorrect,
                ]);

                if ($isCorrect) {
                    $correct++;
                }
            } else {
                QuizAnswer::create([
                    'attempt_id'  => $attempt->id,
                    'question_id' => $question->id,
                    'text_answer' => $given ?: null,
                    'is_correct'  => null, // graded manually by freelancer
                ]);
            }
        }

        $score  = $total > 0 ? (int) round($correct / $total * 100) : null;
        $passed = $score !== null ? $score >= $quiz->passing_score : null;

        $attempt->update([
            'score'  => $score,
            'passed' => $passed,
        ]);

        $this->redirect(route('orders.quiz-result', $this->order->uuid), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.quiz.quiz-take', [
            'order'     => $this->order,
            'quiz'      => $this->order->gig->quiz,
            'questions' => $this->order->gig->quiz->questions,
        ])->layout('layouts.afritask');
    }
}
