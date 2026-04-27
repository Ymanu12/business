<?php

namespace App\Livewire\Quiz;

use App\Models\{Gig, Quiz, QuizChoice, QuizQuestion};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuizBuilder extends Component
{
    public Gig $gig;

    // Quiz meta
    public string $quizTitle      = '';
    public string $instructions   = '';
    public int    $passingScore   = 70;

    // Question form
    public int    $editingId      = 0;
    public string $questionText   = '';
    public string $questionType   = 'multiple_choice';
    public string $correctAnswer  = '';   // for text type
    public array  $choices        = [     // for multiple_choice
        ['label' => '', 'is_correct' => false],
        ['label' => '', 'is_correct' => false],
        ['label' => '', 'is_correct' => false],
        ['label' => '', 'is_correct' => false],
    ];

    public ?string $successMessage = null;

    public function mount(Gig $gig): void
    {
        $this->gig = $gig->load(['quiz.questions.choices']);

        if ($gig->quiz) {
            $this->quizTitle    = $gig->quiz->title;
            $this->instructions = $gig->quiz->instructions ?? '';
            $this->passingScore = $gig->quiz->passing_score;
        }
    }

    public function saveMeta(): void
    {
        $this->validate([
            'quizTitle'    => ['required', 'string', 'max:150'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'passingScore' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'quizTitle.required' => 'Le titre du quiz est obligatoire.',
        ]);

        $quiz = Quiz::updateOrCreate(
            ['gig_id' => $this->gig->id],
            [
                'title'        => $this->quizTitle,
                'instructions' => $this->instructions ?: null,
                'passing_score'=> $this->passingScore,
            ]
        );

        $this->gig->setRelation('quiz', $quiz->load('questions.choices'));
        $this->successMessage = 'Quiz enregistré.';
    }

    public function addChoice(): void
    {
        if (count($this->choices) < 6) {
            $this->choices[] = ['label' => '', 'is_correct' => false];
        }
    }

    public function removeChoice(int $index): void
    {
        if (count($this->choices) > 2) {
            array_splice($this->choices, $index, 1);
            $this->choices = array_values($this->choices);
        }
    }

    public function saveQuestion(): void
    {
        $rules = [
            'questionText' => ['required', 'string', 'max:500'],
            'questionType' => ['required', 'in:multiple_choice,text'],
        ];

        if ($this->questionType === 'multiple_choice') {
            $rules['choices']             = ['required', 'array', 'min:2'];
            $rules['choices.*.label']     = ['required', 'string', 'max:200'];
            $rules['choices.*.is_correct']= ['boolean'];
        } else {
            $rules['correctAnswer'] = ['nullable', 'string', 'max:500'];
        }

        $this->validate($rules, [
            'questionText.required'       => 'La question est obligatoire.',
            'choices.*.label.required'    => 'Chaque choix doit avoir un texte.',
        ]);

        if ($this->questionType === 'multiple_choice') {
            $hasCorrect = collect($this->choices)->some(fn($c) => $c['is_correct']);
            if (! $hasCorrect) {
                $this->addError('choices', 'Marquez au moins un choix comme correct.');
                return;
            }
        }

        $quiz = $this->gig->quiz ?? Quiz::create([
            'gig_id'       => $this->gig->id,
            'title'        => $this->quizTitle ?: ($this->gig->title . ' — Quiz'),
            'passing_score'=> $this->passingScore,
        ]);

        if ($this->editingId) {
            $question = QuizQuestion::find($this->editingId);
            $question->update([
                'question'      => $this->questionText,
                'type'          => $this->questionType,
                'correct_answer'=> $this->questionType === 'text' ? ($this->correctAnswer ?: null) : null,
            ]);
            $question->choices()->delete();
        } else {
            $position = $quiz->questions()->max('position') + 1;
            $question = $quiz->questions()->create([
                'question'      => $this->questionText,
                'type'          => $this->questionType,
                'correct_answer'=> $this->questionType === 'text' ? ($this->correctAnswer ?: null) : null,
                'position'      => $position,
            ]);
        }

        if ($this->questionType === 'multiple_choice') {
            foreach ($this->choices as $i => $choice) {
                QuizChoice::create([
                    'question_id' => $question->id,
                    'label'       => $choice['label'],
                    'is_correct'  => (bool) ($choice['is_correct'] ?? false),
                    'position'    => $i,
                ]);
            }
        }

        $this->resetQuestionForm();
        $this->gig->load('quiz.questions.choices');
        $this->successMessage = 'Question enregistrée.';
    }

    public function editQuestion(int $id): void
    {
        $question = QuizQuestion::with('choices')->find($id);

        $this->editingId     = $question->id;
        $this->questionText  = $question->question;
        $this->questionType  = $question->type;
        $this->correctAnswer = $question->correct_answer ?? '';

        if ($question->type === 'multiple_choice' && $question->choices->isNotEmpty()) {
            $this->choices = $question->choices->map(fn($c) => [
                'label'      => $c->label,
                'is_correct' => $c->is_correct,
            ])->toArray();
        } else {
            $this->choices = [
                ['label' => '', 'is_correct' => false],
                ['label' => '', 'is_correct' => false],
            ];
        }

        $this->successMessage = null;
    }

    public function deleteQuestion(int $id): void
    {
        QuizQuestion::where('id', $id)
            ->whereHas('quiz', fn($q) => $q->where('gig_id', $this->gig->id))
            ->delete();

        $this->gig->load('quiz.questions.choices');
        $this->successMessage = 'Question supprimée.';
    }

    public function cancelEdit(): void
    {
        $this->resetQuestionForm();
        $this->successMessage = null;
    }

    private function resetQuestionForm(): void
    {
        $this->editingId     = 0;
        $this->questionText  = '';
        $this->questionType  = 'multiple_choice';
        $this->correctAnswer = '';
        $this->choices = [
            ['label' => '', 'is_correct' => false],
            ['label' => '', 'is_correct' => false],
            ['label' => '', 'is_correct' => false],
            ['label' => '', 'is_correct' => false],
        ];
        $this->resetErrorBag();
    }

    public function render(): View
    {
        return view('livewire.quiz.quiz-builder', [
            'quiz'      => $this->gig->quiz,
            'questions' => $this->gig->quiz?->questions ?? collect(),
        ]);
    }
}
