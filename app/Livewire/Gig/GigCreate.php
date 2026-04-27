<?php

namespace App\Livewire\Gig;

use App\Enums\GigStatus;
use App\Models\Category;
use App\Models\Gig;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GigCreate extends Component
{
    public string $title = '';

    public string $description = '';

    public string $tagsInput = '';

    public string $currency = 'XOF';

    public string $startingPrice = '25000';

    public string $deliveryDays = '3';

    public string $revisionCount = '1';

    public ?int $categoryId = null;

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $profile = $user->freelancerProfile;

        if ($profile) {
            $this->title = $this->suggestTitle($profile->tagline);
            $this->description = $this->suggestDescription($profile->tagline, $profile->skills ?? [], $profile->languages ?? []);
            $this->tagsInput = implode(', ', $profile->skills ?? []);
            $this->categoryId = $this->guessCategoryId($profile->tagline ?? '', $profile->skills ?? []);
        }

        $this->categoryId ??= Category::parents()->value('id');
    }

    public function saveGig(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'min:12', 'max:255'],
            'description' => ['required', 'string', 'min:80'],
            'tagsInput' => ['nullable', 'string', 'max:1000'],
            'currency' => ['required', Rule::in(['XOF', 'EUR', 'USD'])],
            'startingPrice' => ['required', 'numeric', 'min:5000'],
            'deliveryDays' => ['required', 'integer', 'min:1', 'max:90'],
            'revisionCount' => ['required', 'integer', 'min:0', 'max:20'],
            'categoryId' => ['required', 'integer', Rule::exists(Category::class, 'id')],
        ]);

        /** @var User $user */
        $user = auth()->user();

        $gig = Gig::query()->create([
            'freelancer_id' => $user->id,
            'category_id' => $validated['categoryId'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'starting_price' => $validated['startingPrice'],
            'currency' => $validated['currency'],
            'delivery_days' => $validated['deliveryDays'],
            'revision_count' => $validated['revisionCount'],
            'status' => GigStatus::Draft,
            'seo_title' => Str::limit($validated['title'], 60, ''),
            'seo_description' => Str::limit(strip_tags($validated['description']), 155, ''),
        ]);

        $tagIds = collect($this->normalizeTags($validated['tagsInput']))
            ->map(function (string $tagName): int {
                return Tag::query()->firstOrCreate([
                    'slug' => Str::slug($tagName),
                ], [
                    'name' => $tagName,
                ])->id;
            })
            ->all();

        if ($tagIds !== []) {
            $gig->tags()->sync($tagIds);
        }

        session()->flash('success', 'Votre service a ete cree en brouillon. Vous pouvez maintenant le completer.');

        $this->redirectRoute('seller.gigs.edit', ['gig' => $gig->id], false, true);
    }

    /**
     * @return array<int, string>
     */
    private function normalizeTags(?string $value): array
    {
        if (! filled($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->unique(fn (string $tag): string => Str::slug($tag))
            ->values()
            ->all();
    }

    private function suggestTitle(?string $tagline): string
    {
        if (blank($tagline)) {
            return '';
        }

        $normalizedTagline = Str::of($tagline)
            ->trim()
            ->rtrim('.')
            ->replaceMatches('/^je\s+/iu', '')
            ->replaceMatches('/^j[\'’]/iu', '');

        return Str::limit('Je vais '.Str::lower((string) $normalizedTagline), 255, '');
    }

    /**
     * @param  array<int, string>  $skills
     * @param  array<int, string>  $languages
     */
    private function suggestDescription(?string $tagline, array $skills, array $languages): string
    {
        $lines = collect([
            $tagline ? 'Positionnement: '.trim($tagline).'.' : null,
            $skills !== [] ? 'Competences clefs: '.implode(', ', $skills).'.' : null,
            $languages !== [] ? 'Langues de travail: '.implode(', ', $languages).'.' : null,
            'Cette offre sera personnalisee selon le brief, les delais et les objectifs de votre projet.',
        ])->filter();

        return $lines->implode("\n\n");
    }

    /**
     * @param  array<int, string>  $skills
     */
    private function guessCategoryId(string $tagline, array $skills): ?int
    {
        $haystack = Str::lower(trim($tagline.' '.implode(' ', $skills)));

        /** @var Collection<int, Category> $categories */
        $categories = Category::parents()->get();

        foreach ($categories as $category) {
            if (Str::contains($haystack, Str::lower($category->name))) {
                return $category->id;
            }
        }

        return $categories->first()?->id;
    }

    public function render(): View
    {
        return view('livewire.gig.gig-create', [
            'categories' => Category::parents()->get(),
        ])->layout('layouts.afritask');
    }
}
