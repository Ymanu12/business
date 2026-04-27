<?php

namespace App\Livewire\Freelancer;

use App\Enums\UserRole;
use App\Models\Conversation;
use App\Models\FreelancerProfile;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Onboarding extends Component
{
    public string $tagline = '';

    public string $skillsInput = '';

    public string $languagesInput = '';

    public string $availability = 'available';

    public string $portfolioUrl = '';

    public string $linkedinUrl = '';

    public string $githubUrl = '';

    public ?User $adminContact = null;

    public function mount(): void
    {
        $user = auth()->user();

        if (! $user || $user->role !== UserRole::Freelancer) {
            $this->redirectRoute('dashboard', [], false, true);

            return;
        }

        $profile = $user->freelancerProfile;

        if (! $profile) {
            return;
        }

        $this->tagline = $profile->tagline ?? '';
        $this->skillsInput = implode(', ', $profile->skills ?? []);
        $this->languagesInput = implode(', ', $profile->languages ?? []);
        $this->availability = $profile->availability ?? 'available';
        $this->portfolioUrl = $profile->portfolio_url ?? '';
        $this->linkedinUrl = $profile->linkedin_url ?? '';
        $this->githubUrl = $profile->github_url ?? '';
    }

    public function openAdminChat(): void
    {
        $user = auth()->user();

        abort_unless($user?->isFreelancer(), 403);

        $admin = $this->adminContact ?? User::query()
            ->where('role', UserRole::Admin)
            ->where('is_active', true)
            ->orderBy('name')
            ->firstOrFail();

        $conversation = Conversation::findOrCreateBetweenUsers($user->id, $admin->id);

        $this->redirectRoute('inbox.show', ['conversation' => $conversation->id], false, true);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'tagline' => ['required', 'string', 'max:255'],
            'skillsInput' => ['required', 'string', 'max:1000'],
            'languagesInput' => ['nullable', 'string', 'max:1000'],
            'availability' => ['required', Rule::in(['available', 'busy', 'unavailable'])],
            'portfolioUrl' => ['nullable', 'url', 'max:500'],
            'linkedinUrl' => ['nullable', 'url', 'max:500'],
            'githubUrl' => ['nullable', 'url', 'max:500'],
        ]);

        /** @var User $user */
        $user = auth()->user();

        FreelancerProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'tagline' => $validated['tagline'],
                'skills' => $this->normalizeList($validated['skillsInput']),
                'languages' => $this->normalizeList($validated['languagesInput']),
                'availability' => $validated['availability'],
                'portfolio_url' => $validated['portfolioUrl'] ?: null,
                'linkedin_url' => $validated['linkedinUrl'] ?: null,
                'github_url' => $validated['githubUrl'] ?: null,
            ],
        );

        session()->flash('success', 'Votre profil freelance est pret. Vous pouvez maintenant publier votre premier service.');

        $this->redirectRoute('seller.gigs.create', [], false, true);
    }

    /**
     * @return list<string>
     */
    private function normalizeList(string $value): array
    {
        return collect(explode(',', $value))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function render(): View
    {
        $this->adminContact = User::query()
            ->where('role', UserRole::Admin)
            ->where('is_active', true)
            ->orderBy('name')
            ->first();

        return view('livewire.freelancer.onboarding', [
            'adminContact' => $this->adminContact,
        ])->layout('layouts.afritask');
    }
}
