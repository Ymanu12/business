<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:gap-8">

        {{-- Sidebar filtres --}}
        <aside class="w-full shrink-0 md:w-64">
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <h2 class="text-sm font-semibold uppercase tracking-[0.24em] text-zinc-700 dark:text-zinc-300">Filtres</h2>

                <div class="mt-4 grid gap-4">
                    <div>
                        <label class="text-xs font-semibold text-zinc-500 uppercase tracking-[0.2em] dark:text-zinc-400">Catégorie</label>
                        <div class="mt-2 grid gap-1.5">
                            <label class="flex items-center gap-2 cursor-pointer rounded-xl px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-zinc-700/50 {{ $categoryId === null ? 'bg-teal-50 text-teal-700 font-semibold dark:bg-teal-900/30 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400' }}">
                                <input type="radio" wire:model.live="categoryId" value="" class="sr-only">
                                <span class="text-sm">Toutes</span>
                            </label>
                            @foreach ($categories as $cat)
                                <label class="flex items-center justify-between gap-2 cursor-pointer rounded-xl px-3 py-2 transition hover:bg-stone-50 dark:hover:bg-zinc-700/50 {{ (int)$categoryId === $cat->id ? 'bg-teal-50 text-teal-700 font-semibold dark:bg-teal-900/30 dark:text-teal-400' : 'text-zinc-600 dark:text-zinc-400' }}">
                                    <input type="radio" wire:model.live="categoryId" value="{{ $cat->id }}" class="sr-only">
                                    <span class="text-sm">{{ $cat->name }}</span>
                                    <span class="text-xs text-zinc-400">{{ $cat->gigs_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-zinc-500 uppercase tracking-[0.2em] dark:text-zinc-400">Prix (XOF)</label>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <input wire:model.live.debounce.400ms="minPrice" type="number" placeholder="Min" min="0"
                                class="rounded-xl border border-zinc-200 bg-stone-50 px-3 py-2 text-sm text-zinc-700 outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-700">
                            <input wire:model.live.debounce.400ms="maxPrice" type="number" placeholder="Max" min="0"
                                class="rounded-xl border border-zinc-200 bg-stone-50 px-3 py-2 text-sm text-zinc-700 outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:text-zinc-300 dark:border-zinc-700">
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Résultats --}}
        <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-black text-zinc-950 dark:text-white">
                        @if ($query)
                            Résultats pour <span class="text-teal-700">« {{ $query }} »</span>
                        @else
                            Tous les services
                        @endif
                    </h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $gigs->total() }} service{{ $gigs->total() > 1 ? 's' : '' }} trouvé{{ $gigs->total() > 1 ? 's' : '' }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Trier&nbsp;:</label>
                    <select wire:model.live="sortBy"
                        class="rounded-full border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 outline-none focus:border-teal-400 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700">
                        <option value="popular">Plus populaires</option>
                        <option value="rating">Meilleures notes</option>
                        <option value="price_asc">Prix croissant</option>
                        <option value="price_desc">Prix décroissant</option>
                        <option value="newest">Plus récents</option>
                    </select>
                </div>
            </div>

            @if ($gigs->isEmpty())
                <div class="mt-12 rounded-[2rem] border border-zinc-200 bg-white/90 px-6 py-16 text-center shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-stone-100 text-zinc-400 dark:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                    </svg>
                </div>
                    <h3 class="mt-5 text-lg font-bold text-zinc-900 dark:text-zinc-100">Aucun résultat trouvé</h3>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Essayez d'autres mots-clés ou élargissez les filtres.</p>
                    <a href="{{ route('search') }}" wire:navigate class="mt-6 inline-flex items-center rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Voir tous les services
                    </a>
                </div>
            @else
                <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($gigs as $gig)
                        @include('components.gig-card', ['gig' => $gig])
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $gigs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
