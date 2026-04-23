<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- En-tête catégorie --}}
    <section class="overflow-hidden rounded-[2.2rem] border border-white/70 bg-zinc-950 px-6 py-8 text-white shadow-2xl shadow-zinc-900/15 lg:px-10 lg:py-10 dark:border-white/15">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex size-14 items-center justify-center rounded-2xl"
                         style="background-color: {{ ($category->color ?? '#14b8a6') }}25;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" style="color: {{ $category->color ?? '#14b8a6' }};">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.26em] text-teal-200">Catégorie</p>
                        <h1 class="mt-1 text-3xl font-black leading-none sm:text-4xl">{{ $category->name }}</h1>
                    </div>
                </div>
                @if ($category->description)
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-zinc-300">{{ $category->description }}</p>
                @endif
            </div>

            <div class="shrink-0 text-right">
                <div class="text-4xl font-black text-amber-300">{{ $gigs->total() }}</div>
                <div class="mt-1 text-sm text-zinc-400">service{{ $gigs->total() > 1 ? 's' : '' }} disponible{{ $gigs->total() > 1 ? 's' : '' }}</div>
            </div>
        </div>

        {{-- Sous-catégories --}}
        @if ($category->children->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($category->children as $child)
                    <a href="{{ route('categories.show', $child->slug) }}" wire:navigate
                       class="rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-semibold text-zinc-100 transition hover:bg-white/20">
                        {{ $child->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Filtres & tri --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-zinc-500 dark:text-zinc-400">
            Affichage de <strong class="text-zinc-900 dark:text-zinc-100">{{ $gigs->count() }}</strong>
            / <strong class="text-zinc-900 dark:text-zinc-100">{{ $gigs->total() }}</strong> services
        </p>
        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Trier&nbsp;:</label>
            <select wire:model.live="sortBy"
                    class="rounded-full border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 outline-none focus:border-teal-400 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700">
                <option value="popular">Plus populaires</option>
                <option value="rating">Meilleures notes</option>
                <option value="price_asc">Prix croissant</option>
                <option value="newest">Plus récents</option>
            </select>
        </div>
    </div>

    @if ($gigs->isEmpty())
        <div class="mt-10 rounded-[2rem] border border-zinc-200 bg-white/90 px-6 py-16 text-center shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-stone-100 text-zinc-400 dark:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
            </div>
            <h3 class="mt-5 text-lg font-bold text-zinc-900 dark:text-zinc-100">Aucun service dans cette catégorie</h3>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Soyez le premier à proposer un service ici !</p>
            @auth
                @if (auth()->user()->isFreelancer())
                    <a href="{{ route('seller.gigs.create') }}" wire:navigate
                       class="mt-6 inline-flex items-center rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Créer un service
                    </a>
                @endif
            @endauth
        </div>
    @else
        <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($gigs as $gig)
                @include('components.gig-card', ['gig' => $gig])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $gigs->links() }}
        </div>
    @endif
</div>
