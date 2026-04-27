<?php

namespace App\Livewire\Course;

use App\Models\{GigLesson, LessonProgress, Order};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CoursePage extends Component
{
    public Order $order;
    public int   $activeLessonId = 0;

    public function mount(Order $order): void
    {
        abort_unless($order->client_id === auth()->id(), 403);
        abort_unless($order->payment !== null, 403);

        $this->order = $order->load([
            'gig.lessons',
            'gig.freelancer',
        ]);

        $this->activeLessonId = $this->order->gig->lessons->first()?->id ?? 0;
    }

    public function selectLesson(int $id): void
    {
        $lesson = $this->order->gig->lessons->firstWhere('id', $id);
        abort_unless($lesson !== null, 404);

        $this->activeLessonId = $id;
    }

    public function toggleComplete(int $id): void
    {
        $lesson = GigLesson::where('id', $id)
            ->where('gig_id', $this->order->gig_id)
            ->firstOrFail();

        $progress = LessonProgress::firstOrNew([
            'user_id'   => auth()->id(),
            'lesson_id' => $id,
        ]);

        if ($progress->completed_at) {
            $progress->completed_at = null;
        } else {
            $progress->completed_at = now();
        }

        $progress->save();
    }

    public function render(): View
    {
        $lessons  = $this->order->gig->lessons;
        $userId   = auth()->id();

        $completed = LessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        $activeLesson = $lessons->firstWhere('id', $this->activeLessonId);

        $progressPct = $lessons->count()
            ? (int) (count($completed) / $lessons->count() * 100)
            : 0;

        return view('livewire.course.course-page', [
            'lessons'      => $lessons,
            'completed'    => $completed,
            'activeLesson' => $activeLesson,
            'progressPct'  => $progressPct,
        ])->layout('layouts.afritask');
    }
}
