<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-10 lg:flex-row lg:items-start lg:gap-10">

        {{-- COLONNE PRINCIPALE --}}
        <div class="min-w-0 flex-1">

            {{-- Breadcrumb --}}
            <nav class="mb-5 flex items-center gap-2 text-xs text-zinc-400">
                <a href="{{ route('home') }}" wire:navigate class="transition hover:text-teal-700">Accueil</a>
                <span>/</span>
                @if ($gig->category)
                    <a href="{{ route('categories.show', $gig->category->slug) }}" wire:navigate class="transition hover:text-teal-700">{{ $gig->category->name }}</a>
                    <span>/</span>
                @endif
                <span class="truncate text-zinc-600 dark:text-zinc-400">{{ Str::limit($gig->title, 50) }}</span>
            </nav>

            {{-- Titre --}}
            <h1 class="text-3xl font-black leading-tight text-zinc-950 sm:text-4xl dark:text-white">{{ $gig->title }}</h1>

            {{-- Freelance info + stats --}}
            <div class="mt-5 flex flex-wrap items-center gap-4">
                <a href="{{ route('profile.show', $gig->freelancer->username ?? $gig->freelancer->id) }}" wire:navigate
                   class="flex items-center gap-2.5 transition hover:opacity-80">
                    <img src="{{ $gig->freelancer->avatarUrl() }}" alt="{{ $gig->freelancer->name }}"
                         class="size-9 rounded-full object-cover ring-2 ring-teal-100">
                    <div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $gig->freelancer->name }}</div>
                        @if ($gig->freelancer->freelancerProfile?->seller_level)
                            <div class="text-[11px] uppercase tracking-[0.22em] text-zinc-400">
                                {{ $gig->freelancer->freelancerProfile->levelLabel() }}
                            </div>
                        @endif
                    </div>
                </a>

                @if ($gig->avg_rating > 0)
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($gig->avg_rating, 1) }}</span>
                        <span class="text-sm text-zinc-400">({{ $gig->reviews->count() }} avis)</span>
                    </div>
                @endif

                <div class="flex items-center gap-1 text-sm text-zinc-500 dark:text-zinc-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($gig->views_count ?? 0) }} vues
                </div>
            </div>

            {{-- Image principale --}}
            <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-zinc-100 shadow-sm dark:bg-zinc-800 dark:border-zinc-700">
                <img src="{{ $gig->thumbnailUrl() }}" alt="{{ $gig->title }}"
                     class="aspect-video w-full object-cover">
            </div>

            {{-- Galerie --}}
            @if ($gig->gallery->isNotEmpty())
                <div class="mt-4 flex gap-3 overflow-x-auto pb-2">
                    @foreach ($gig->gallery as $img)
                        <img src="{{ asset('storage/' . $img->file_path) }}" alt="Galerie"
                             class="h-20 w-32 shrink-0 cursor-pointer rounded-xl object-cover ring-2 ring-transparent transition hover:ring-teal-400">
                    @endforeach
                </div>
            @endif

            {{-- Description --}}
            <section class="mt-8">
                <h2 class="text-xl font-black text-zinc-950 dark:text-white">À propos de ce service</h2>
                <div class="prose prose-sm prose-zinc mt-4 max-w-none leading-7 text-zinc-600 dark:text-zinc-400">
                    {!! nl2br(e($gig->description)) !!}
                </div>
            </section>

            {{-- Tags --}}
            @if ($gig->tags->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($gig->tags as $tag)
                        <a href="{{ route('search', ['q' => $tag->name]) }}" wire:navigate
                           class="rounded-full border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:border-teal-300 hover:text-teal-700 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- FAQ --}}
            @if ($gig->faqs->isNotEmpty())
                <section class="mt-10">
                    <h2 class="text-xl font-black text-zinc-950 dark:text-white">Questions fréquentes</h2>
                    <div class="mt-4 grid gap-3" x-data="{ open: null }">
                        @foreach ($gig->faqs as $i => $faq)
                            <div class="rounded-2xl border border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700">
                                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                                        class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $faq->question }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         :class="open === {{ $i }} ? 'rotate-180' : ''"
                                         class="size-4 shrink-0 text-zinc-400 transition"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open === {{ $i }}" x-cloak
                                     class="border-t border-zinc-100 px-5 pb-4 pt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-400 dark:border-zinc-800">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Avis --}}
            @if ($gig->reviews->isNotEmpty())
                <section class="mt-10">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-black text-zinc-950 dark:text-white">
                            Avis clients <span class="text-teal-700">({{ $gig->reviews->count() }})</span>
                        </h2>
                        @if ($gig->avg_rating > 0)
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-lg font-black text-zinc-950 dark:text-white">{{ number_format($gig->avg_rating, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 grid gap-4">
                        @foreach ($gig->reviews as $review)
                            <article class="rounded-2xl border border-zinc-200 bg-white p-5 dark:bg-zinc-800 dark:border-zinc-700">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->reviewer->avatarUrl() }}" alt="{{ $review->reviewer->name }}"
                                         class="size-9 rounded-full object-cover">
                                    <div>
                                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $review->reviewer->name }}</div>
                                        <div class="mt-0.5 flex gap-0.5">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="size-3.5 {{ $s <= $review->rating ? 'text-amber-400' : 'text-zinc-200' }}"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="ml-auto text-xs text-zinc-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if ($review->comment)
                                    <p class="mt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-400">{{ $review->comment }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- SIDEBAR COMMANDE --}}
        <aside class="w-full lg:w-80 lg:shrink-0 xl:w-96">
            <div class="sticky top-24">

                {{-- Packages --}}
                @if ($gig->packages->isNotEmpty())
                    <div class="overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-xl shadow-zinc-900/5 dark:bg-zinc-800 dark:border-zinc-700">
                        <div class="flex border-b border-zinc-100 dark:border-zinc-800">
                            @foreach ($gig->packages as $i => $package)
                                <button wire:click="$set('selectedPackageIndex', {{ $i }})"
                                        class="flex-1 px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.2em] transition {{ $selectedPackageIndex === $i ? 'bg-zinc-950 text-white' : 'text-zinc-500 hover:bg-stone-50' }}">
                                    {{ $package->typeLabel() }}
                                </button>
                            @endforeach
                        </div>

                        @if ($selectedPackage)
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-lg font-black text-zinc-950 dark:text-white">{{ $selectedPackage->name }}</h3>
                                        @if ($selectedPackage->description)
                                            <p class="mt-1.5 text-sm leading-6 text-zinc-500 dark:text-zinc-400">{{ $selectedPackage->description }}</p>
                                        @endif
                                    </div>
                                    <div class="shrink-0 text-2xl font-black text-teal-700">{{ $selectedPackage->formattedPrice() }}</div>
                                </div>

                                <div class="mt-5 grid gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Livraison en {{ $selectedPackage->delivery_days }} jour{{ $selectedPackage->delivery_days > 1 ? 's' : '' }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        {{ $selectedPackage->revision_count > 0 ? $selectedPackage->revision_count . ' révision' . ($selectedPackage->revision_count > 1 ? 's' : '') : 'Sans révision' }}
                                    </div>
                                </div>

                                @if ($selectedPackage->features && count($selectedPackage->features) > 0)
                                    <ul class="mt-5 grid gap-2">
                                        @foreach ($selectedPackage->features as $feature)
                                            <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @auth
                                    @if (auth()->id() !== $gig->freelancer_id)
                                        <a href="{{ route('orders.create', $gig->slug) }}" wire:navigate
                                           class="mt-6 flex w-full items-center justify-center rounded-full bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700">
                                            Commander ce service
                                        </a>
                                    @else
                                        <a href="{{ route('seller.gigs.edit', $gig->id) }}" wire:navigate
                                           class="mt-6 flex w-full items-center justify-center rounded-full border border-zinc-300 px-6 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                                            Modifier ce service
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" wire:navigate
                                       class="mt-6 flex w-full items-center justify-center rounded-full bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700">
                                        Se connecter pour commander
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </div>

                @else
                    {{-- Pas de packages : prix de base --}}
                    <div class="overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white p-6 shadow-xl shadow-zinc-900/5 dark:bg-zinc-800 dark:border-zinc-700">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-zinc-400">À partir de</p>
                        <div class="mt-2 text-3xl font-black text-teal-700">{{ $gig->formattedPrice() }}</div>
                        <div class="mt-4 flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Livraison en {{ $gig->delivery_days }} jour{{ $gig->delivery_days > 1 ? 's' : '' }}
                        </div>
                        @auth
                            @if (auth()->id() !== $gig->freelancer_id)
                                <a href="{{ route('orders.create', $gig->slug) }}" wire:navigate
                                   class="mt-5 flex w-full items-center justify-center rounded-full bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700">
                                    Commander ce service
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" wire:navigate
                               class="mt-5 flex w-full items-center justify-center rounded-full bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700">
                                Connexion pour commander
                            </a>
                        @endauth
                    </div>
                @endif

                {{-- Bouton favori --}}
                <div class="mt-3 flex gap-2">
                    <button wire:click="toggleFavorite"
                            class="flex flex-1 items-center justify-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="size-4 {{ $isFavorited ? 'text-rose-500 fill-rose-500' : '' }}"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" fill="{{ $isFavorited ? 'currentColor' : 'none' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        {{ $isFavorited ? 'Sauvegardé' : 'Sauvegarder' }}
                    </button>
                </div>

                {{-- Profil du freelance --}}
                <div class="mt-4 overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-sm dark:bg-zinc-800 dark:border-zinc-700">
                    <div class="bg-[linear-gradient(135deg,_#0f172a,_#115e59)] px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $gig->freelancer->avatarUrl() }}" alt="{{ $gig->freelancer->name }}"
                                 class="size-12 rounded-full object-cover ring-2 ring-white/20">
                            <div class="text-white">
                                <div class="font-semibold">{{ $gig->freelancer->name }}</div>
                                @if ($gig->freelancer->freelancerProfile)
                                    <div class="mt-0.5 text-xs text-teal-200">{{ $gig->freelancer->freelancerProfile->levelLabel() }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        @if ($gig->freelancer->freelancerProfile?->tagline)
                            <p class="text-sm leading-6 text-zinc-600 dark:text-zinc-400">{{ $gig->freelancer->freelancerProfile->tagline }}</p>
                        @endif

                        @if ($gig->freelancer->freelancerProfile)
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-xl bg-stone-50 p-3 dark:bg-zinc-900">
                                    <div class="text-lg font-black text-zinc-950 dark:text-white">{{ $gig->freelancer->freelancerProfile->avg_rating ? number_format($gig->freelancer->freelancerProfile->avg_rating, 1) : '—' }}</div>
                                    <div class="mt-0.5 text-[10px] text-zinc-400">Note</div>
                                </div>
                                <div class="rounded-xl bg-stone-50 p-3 dark:bg-zinc-900">
                                    <div class="text-lg font-black text-zinc-950 dark:text-white">{{ $gig->freelancer->freelancerProfile->completed_orders ?? 0 }}</div>
                                    <div class="mt-0.5 text-[10px] text-zinc-400">Commandes</div>
                                </div>
                                <div class="rounded-xl bg-stone-50 p-3 dark:bg-zinc-900">
                                    <div class="text-lg font-black text-zinc-950 dark:text-white">{{ $gig->freelancer->freelancerProfile->completionRate() }}%</div>
                                    <div class="mt-0.5 text-[10px] text-zinc-400">Livraisons</div>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('profile.show', $gig->freelancer->username ?? $gig->freelancer->id) }}" wire:navigate
                           class="mt-4 flex w-full items-center justify-center rounded-full border border-zinc-200 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                            Voir le profil
                        </a>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    {{-- Services similaires --}}
    @if ($similarGigs->isNotEmpty())
        <section class="mt-14">
            <h2 class="text-2xl font-black text-zinc-950 dark:text-white">Services similaires</h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($similarGigs as $similar)
                    @include('components.gig-card', ['gig' => $similar])
                @endforeach
            </div>
        </section>
    @endif
</div>
