<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Espace freelance</p>
            <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Mes services</h1>
        </div>
        <a href="{{ route('seller.gigs.create') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-full bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Créer un service
        </a>
    </div>

    @if ($gigs->isEmpty())
        <div class="mt-12 rounded-[2rem] border border-zinc-200 bg-white/90 px-6 py-16 text-center shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-stone-100 text-zinc-400 dark:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                </svg>
            </div>
            <h3 class="mt-5 text-lg font-bold text-zinc-900 dark:text-zinc-100">Aucun service créé</h3>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Publiez votre premier service pour commencer à recevoir des commandes.</p>
            <a href="{{ route('seller.gigs.create') }}" wire:navigate
               class="mt-6 inline-flex items-center rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                Créer mon premier service
            </a>
        </div>
    @else
        <div class="mt-8 grid gap-4">
            @foreach ($gigs as $gig)
                <article class="group flex flex-col gap-4 overflow-hidden rounded-[1.75rem] border border-zinc-200/80 bg-white shadow-sm transition hover:shadow-md sm:flex-row sm:items-center dark:border-zinc-700/60 dark:bg-zinc-800">
                    <a href="{{ route('gigs.show', $gig->slug) }}" wire:navigate
                       class="relative block aspect-video shrink-0 overflow-hidden sm:h-28 sm:w-44 sm:aspect-auto">
                        <img src="{{ $gig->thumbnailUrl() }}" alt="{{ $gig->title }}"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    </a>

                    <div class="flex min-w-0 flex-1 flex-col gap-3 p-4 sm:flex-row sm:items-center sm:py-0 sm:pr-5">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] @switch($gig->status->value) @case('published') bg-emerald-100 text-emerald-700 @break @case('draft') bg-zinc-100 text-zinc-600 @break @case('pending') bg-amber-100 text-amber-700 @break @case('paused') bg-orange-100 text-orange-700 @break @case('rejected') bg-rose-100 text-rose-700 @break @default bg-zinc-100 text-zinc-600 @endswitch dark:bg-zinc-800 dark:text-zinc-400">
                                    {{ ucfirst($gig->status->value) }}
                                </span>
                                @if ($gig->category)
                                    <span class="text-xs text-zinc-400">{{ $gig->category->name }}</span>
                                @endif
                            </div>

                            <a href="{{ route('gigs.show', $gig->slug) }}" wire:navigate
                               class="mt-1.5 block text-base font-bold leading-snug text-zinc-950 transition line-clamp-2 hover:text-teal-700 dark:text-white">
                                {{ $gig->title }}
                            </a>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <span>{{ $gig->orders_count }} commande{{ $gig->orders_count !== 1 ? 's' : '' }}</span>
                                @if ($gig->avg_rating > 0)
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($gig->avg_rating, 1) }} ({{ $gig->reviews_count }})
                                    </span>
                                @endif
                                <span class="font-semibold text-teal-700">{{ $gig->formattedPrice() }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('seller.gigs.edit', $gig->id) }}" wire:navigate
                               class="rounded-full border border-zinc-200 px-3 py-2 text-xs font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                                Modifier
                            </a>
                            @if (in_array($gig->status->value, ['published', 'paused']))
                                <button wire:click="togglePause({{ $gig->id }})" wire:confirm="Changer le statut de ce service ?"
                                        class="rounded-full border border-zinc-200 px-3 py-2 text-xs font-semibold text-zinc-700 transition hover:border-orange-300 hover:text-orange-700 dark:text-zinc-300 dark:border-zinc-700">
                                    {{ $gig->status->value === 'paused' ? 'Réactiver' : 'Mettre en pause' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
