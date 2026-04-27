<?php

namespace App\Livewire\Gig;

use App\Models\{Gig, GigLesson};
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class LessonManager extends Component
{
    use WithFileUploads;

    public Gig $gig;

    // Form fields
    public int    $editingId   = 0;
    public string $title       = '';
    public string $description = '';
    public bool   $isPreview   = false;
    public        $videoFile   = null;
    public        $attachment  = null;

    public ?string $successMessage = null;

    public function mount(Gig $gig): void
    {
        $this->gig = $gig;
    }

    public function save(): void
    {
        $this->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'videoFile'   => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:716800'],
            'attachment'  => ['nullable', 'file', 'max:204800'],
        ], [
            'title.required' => 'Le titre de la leçon est obligatoire.',
            'videoFile.mimetypes' => 'Seuls les formats MP4, WebM et MOV sont acceptés.',
        ]);

        $data = [
            'title'       => $this->title,
            'description' => $this->description,
            'is_preview'  => $this->isPreview,
        ];

        if ($this->videoFile) {
            $data['video_path'] = $this->videoFile->store("lessons/{$this->gig->id}/videos", 'public');
        }

        if ($this->attachment) {
            $data['file_path'] = $this->attachment->store("lessons/{$this->gig->id}/files", 'public');
        }

        if ($this->editingId) {
            GigLesson::where('id', $this->editingId)->where('gig_id', $this->gig->id)->update($data);
        } else {
            $position = $this->gig->lessons()->max('position') + 1;
            $this->gig->lessons()->create(array_merge($data, ['position' => $position]));
        }

        $this->resetForm();
        $this->gig->refresh();
        $this->successMessage = 'Leçon enregistrée.';
    }

    public function edit(int $id): void
    {
        $lesson = GigLesson::where('id', $id)->where('gig_id', $this->gig->id)->firstOrFail();

        $this->editingId   = $lesson->id;
        $this->title       = $lesson->title;
        $this->description = $lesson->description ?? '';
        $this->isPreview   = $lesson->is_preview;
        $this->videoFile   = null;
        $this->attachment  = null;
        $this->successMessage = null;
    }

    public function delete(int $id): void
    {
        GigLesson::where('id', $id)->where('gig_id', $this->gig->id)->delete();
        $this->gig->refresh();
        $this->successMessage = 'Leçon supprimée.';
    }

    public function moveUp(int $id): void
    {
        $this->swapPositions($id, 'up');
    }

    public function moveDown(int $id): void
    {
        $this->swapPositions($id, 'down');
    }

    private function swapPositions(int $id, string $direction): void
    {
        $lessons = $this->gig->lessons()->orderBy('position')->get();
        $index   = $lessons->search(fn($l) => $l->id === $id);

        if ($index === false) {
            return;
        }

        $swapIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($swapIndex < 0 || $swapIndex >= $lessons->count()) {
            return;
        }

        $a = $lessons[$index];
        $b = $lessons[$swapIndex];

        [$a->position, $b->position] = [$b->position, $a->position];
        $a->save();
        $b->save();

        $this->gig->refresh();
    }

    private function resetForm(): void
    {
        $this->editingId   = 0;
        $this->title       = '';
        $this->description = '';
        $this->isPreview   = false;
        $this->videoFile   = null;
        $this->attachment  = null;
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
        $this->successMessage = null;
    }

    public function render(): View
    {
        return view('livewire.gig.lesson-manager', [
            'lessons' => $this->gig->lessons,
        ]);
    }
}
