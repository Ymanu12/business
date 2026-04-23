<?php

namespace App\Livewire\Gig;

use App\Enums\GigStatus;
use App\Models\{Category, Gig, GigPackage, Tag};
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GigEdit extends Component
{
    public Gig $gig;

    public string $title = '';
    public string $description = '';
    public string $tagsInput = '';
    public string $currency = 'XOF';
    public string $startingPrice = '25000';
    public string $deliveryDays = '3';
    public string $revisionCount = '1';
    public ?int $categoryId = null;

    public array $packages = [];

    public function mount(Gig $gig): void
    {
        abort_unless($gig->freelancer_id === auth()->id(), 403);

        $this->gig = $gig->load(['packages', 'tags', 'category']);

        $this->title        = $gig->title;
        $this->description  = $gig->description ?? '';
        $this->currency     = $gig->currency ?? 'XOF';
        $this->startingPrice = (string) (int) $gig->starting_price;
        $this->deliveryDays  = (string) $gig->delivery_days;
        $this->revisionCount = (string) $gig->revision_count;
        $this->categoryId   = $gig->category_id;
        $this->tagsInput    = $gig->tags->pluck('name')->implode(', ');

        $this->packages = $gig->packages->map(fn (GigPackage $p) => [
            'id'            => $p->id,
            'type'          => $p->type,
            'name'          => $p->name ?? '',
            'description'   => $p->description ?? '',
            'price'         => (string) (int) $p->price,
            'delivery_days' => (string) $p->delivery_days,
            'revision_count'=> (string) $p->revision_count,
        ])->toArray();

        if (empty($this->packages)) {
            $this->addDefaultPackages();
        }
    }

    private function addDefaultPackages(): void
    {
        foreach (['basic' => 'Basique', 'standard' => 'Standard', 'premium' => 'Premium'] as $type => $name) {
            $this->packages[] = [
                'id'             => null,
                'type'           => $type,
                'name'           => $name,
                'description'    => '',
                'price'          => $this->startingPrice,
                'delivery_days'  => $this->deliveryDays,
                'revision_count' => '1',
            ];
        }
    }

    public function saveBasicInfo(): void
    {
        $validated = $this->validate([
            'title'          => ['required', 'string', 'min:12', 'max:255'],
            'description'    => ['required', 'string', 'min:80'],
            'tagsInput'      => ['nullable', 'string', 'max:1000'],
            'currency'       => ['required', Rule::in(['XOF', 'EUR', 'USD'])],
            'startingPrice'  => ['required', 'numeric', 'min:1000'],
            'deliveryDays'   => ['required', 'integer', 'min:1', 'max:90'],
            'revisionCount'  => ['required', 'integer', 'min:0', 'max:20'],
            'categoryId'     => ['required', 'integer', Rule::exists(Category::class, 'id')],
        ]);

        $this->gig->update([
            'title'          => $validated['title'],
            'description'    => $validated['description'],
            'category_id'    => $validated['categoryId'],
            'starting_price' => $validated['startingPrice'],
            'currency'       => $validated['currency'],
            'delivery_days'  => $validated['deliveryDays'],
            'revision_count' => $validated['revisionCount'],
            'seo_title'      => Str::limit($validated['title'], 60, ''),
            'seo_description'=> Str::limit(strip_tags($validated['description']), 155, ''),
        ]);

        $tagIds = collect(explode(',', $validated['tagsInput'] ?? ''))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->unique(fn ($t) => Str::slug($t))
            ->map(fn ($t) => Tag::firstOrCreate(['slug' => Str::slug($t)], ['name' => $t])->id)
            ->toArray();
        $this->gig->tags()->sync($tagIds);

        session()->flash('success', 'Informations mises à jour.');
    }

    public function savePackages(): void
    {
        $rules = [];
        foreach ($this->packages as $i => $pkg) {
            $rules["packages.{$i}.name"]           = ['required', 'string', 'max:100'];
            $rules["packages.{$i}.price"]          = ['required', 'numeric', 'min:1000'];
            $rules["packages.{$i}.delivery_days"]  = ['required', 'integer', 'min:1', 'max:90'];
            $rules["packages.{$i}.revision_count"] = ['required', 'integer', 'min:0', 'max:20'];
        }
        $this->validate($rules);

        foreach ($this->packages as $data) {
            GigPackage::updateOrCreate(
                ['id' => $data['id'] ?: 0, 'gig_id' => $this->gig->id],
                [
                    'gig_id'         => $this->gig->id,
                    'type'           => $data['type'],
                    'name'           => $data['name'],
                    'description'    => $data['description'],
                    'price'          => $data['price'],
                    'delivery_days'  => $data['delivery_days'],
                    'revision_count' => $data['revision_count'],
                    'is_active'      => true,
                ]
            );
        }

        session()->flash('success', 'Packages sauvegardés.');
    }

    public function publish(): void
    {
        abort_unless($this->gig->packages()->exists(), 422);

        $this->gig->update(['status' => GigStatus::Pending]);
        session()->flash('success', 'Votre service a été soumis pour révision.');
    }

    public function render(): View
    {
        return view('livewire.gig.gig-edit', [
            'gig'        => $this->gig,
            'categories' => Category::parents()->get(),
        ])->layout('layouts.afritask');
    }
}
