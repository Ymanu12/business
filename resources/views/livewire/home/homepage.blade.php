<div>

    {{-- ═══════════════════════════════ HERO ═══════════════════════════════ --}}
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -left-20 top-0 h-96 w-96 rounded-full bg-teal-300/20 blur-[120px]"></div>
            <div class="absolute -right-20 top-32 h-80 w-80 rounded-full bg-amber-300/20 blur-[100px]"></div>
            <div class="absolute bottom-0 left-1/2 h-64 w-[60vw] -translate-x-1/2 rounded-full bg-zinc-200/40 blur-[80px]"></div>
        </div>

        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.15fr_0.85fr] lg:px-8 lg:py-24">
            <div class="relative">
                <div class="inline-flex items-center gap-2 rounded-full border border-teal-200 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.26em] text-teal-700 shadow-sm backdrop-blur dark:bg-zinc-800/80">
                    <span class="size-2 animate-pulse rounded-full bg-amber-400"></span>
                    Afrique freelance
                </div>

                <h1 class="mt-6 max-w-3xl text-4xl font-black leading-[1.08] tracking-tight text-zinc-950 sm:text-5xl lg:text-[3.5rem] dark:text-white">
                    Trouvez le bon freelance africain pour livrer
                    <span class="relative whitespace-nowrap text-teal-600">
                        <svg aria-hidden="true" viewBox="0 0 418 42" class="absolute left-0 top-full -mt-1 hidden h-[0.55em] w-full fill-teal-200/80 sm:block" preserveAspectRatio="none">
                            <path d="M203.371.916c-26.013-2.078-76.686 1.963-124.73 9.946L67.3 12.749C35.421 18.062 18.2 21.766 6.004 25.934 1.244 27.561.828 27.778.874 28.61c.07 1.214.828 1.121 9.595-1.176 9.072-2.377 17.15-3.92 39.246-7.496C123.565 7.986 157.869 4.492 195.942 5.046c7.461.108 19.25 1.696 19.17 2.582-.107 1.183-7.874 4.31-25.75 10.366-21.992 7.45-35.43 12.534-36.701 13.884-2.173 2.308-.202 4.407 4.442 4.734 2.654.187 3.263.157 15.593-.78 35.401-2.686 57.944-3.488 88.365-3.143 46.327.526 75.721 2.23 130.788 7.606 19.787 1.836 40.97 3.9 98.313 7.742 2.78.182 4.824.23 4.924.08.069-.103.026-.292-.323-.56L275.795 6.787C271.311 5.75 234.21 3.106 203.371.916z" />
                        </svg>
                        vite et bien.
                    </span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-8 text-zinc-600 sm:text-lg dark:text-zinc-400">
                    Design, dev, marketing, vidéo, support client — AfriTask rassemble des talents vérifiés avec des paiements adaptés au terrain et une expérience simple pour commander sans friction.
                </p>

                <div class="mt-8 max-w-2xl rounded-[2rem] border border-white/70 bg-white/90 p-3 shadow-2xl shadow-zinc-900/10 backdrop-blur dark:bg-zinc-800/90 dark:border-white/15">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input
                            wire:model.live.debounce.300ms="searchQuery"
                            wire:keydown.enter="search"
                            type="text"
                            placeholder="Ex: logo, site web, voice over, traduction..."
                            class="min-w-0 flex-1 rounded-[1.35rem] border border-zinc-200 bg-stone-50 px-5 py-4 text-sm text-zinc-800 outline-none transition placeholder:text-zinc-400 focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:text-zinc-200 dark:border-zinc-700 dark:placeholder:text-zinc-500"
                        >
                        <button
                            wire:click="search"
                            type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-[1.35rem] bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                            </svg>
                            Rechercher
                        </button>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach (['Logo design', 'Landing page', 'Montage vidéo', 'Traduction', 'Community manager', 'Rédaction SEO'] as $suggestion)
                            <button
                                wire:click="$set('searchQuery', '{{ $suggestion }}')"
                                type="button"
                                class="rounded-full border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:border-teal-300 hover:text-teal-700 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700"
                            >{{ $suggestion }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    @foreach ([
                        [
                            'value' => '5 000+',
                            'label' => 'freelances actifs',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0z"/>',
                        ],
                        [
                            'value' => '24/7',
                            'label' => 'support & médiation',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.955 11.955 0 0 0 3 10.5c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.25-8.25-3.286Z"/>',
                        ],
                        [
                            'value' => 'Escrow',
                            'label' => 'paiements sécurisés',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25z"/>',
                        ],
                    ] as $stat)
                        <div class="rounded-3xl border border-white/70 bg-white/75 p-5 shadow-lg shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/75 dark:border-white/15">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                {!! $stat['icon'] !!}
                            </svg>
                            <div class="mt-2 text-lg font-black text-zinc-950 dark:text-white">{{ $stat['value'] }}</div>
                            <div class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Dashboard mockup --}}
            <div class="relative hidden lg:block">
                <div class="absolute -right-8 top-6 h-28 w-28 rounded-full bg-amber-300/40 blur-3xl"></div>
                <div class="absolute left-0 top-1/2 h-36 w-36 -translate-y-1/2 rounded-full bg-teal-300/30 blur-3xl"></div>

                <div class="relative rounded-[2rem] border border-white/20 bg-zinc-950 p-6 text-white shadow-2xl shadow-zinc-900/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.28em] text-teal-300">Tableau de bord client</p>
                            <h2 class="mt-2 text-xl font-black">Commandez sans perdre de temps</h2>
                        </div>
                        <div class="rounded-full bg-amber-400/20 px-3 py-1 text-xs font-semibold text-amber-300">Escrow actif</div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-white/8 p-4 ring-1 ring-white/10 dark:bg-white/12">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold">Landing page SaaS</p>
                                    <p class="mt-0.5 text-xs text-zinc-400">Freelance: Awa Ndiaye</p>
                                </div>
                                <span class="rounded-full bg-teal-400/15 px-3 py-1 text-xs font-semibold text-teal-300">En cours</span>
                            </div>
                            <div class="mt-3 h-1.5 rounded-full bg-white/10">
                                <div class="h-1.5 w-3/4 rounded-full bg-amber-300"></div>
                            </div>
                            <p class="mt-1.5 text-right text-[10px] text-zinc-500 dark:text-zinc-400">75% complété</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-white p-4 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-100">
                                <p class="text-[10px] uppercase tracking-[0.22em] text-zinc-400">En escrow</p>
                                <p class="mt-2 text-2xl font-black text-teal-700">320k XOF</p>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Bloqué jusqu'à validation</p>
                            </div>
                            <div class="rounded-2xl bg-teal-600 p-4 text-white">
                                <p class="text-[10px] uppercase tracking-[0.22em] text-teal-100">Note moyenne</p>
                                <div class="mt-2 flex items-baseline gap-1">
                                    <span class="text-2xl font-black">4.9</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-300" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <p class="mt-1 text-xs text-teal-100">Talents vérifiés</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-3 gap-2">
                        @foreach (['MTN MoMo', 'Orange', 'Stripe'] as $pm)
                            <div class="rounded-xl border border-white/10 bg-white/5 py-2 text-center text-[11px] font-semibold text-zinc-300">{{ $pm }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ SOCIAL PROOF STRIP ════════════════════════ --}}
    <section class="border-y border-zinc-200/70 bg-white/60 py-5 backdrop-blur dark:bg-zinc-800/60">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-zinc-400">Ils font confiance à AfriTask</p>
                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8">
                    @foreach (['Startups', 'PME', 'Agences', 'NGOs', 'Créateurs'] as $type)
                        <span class="text-sm font-bold text-zinc-400 transition hover:text-zinc-700">{{ $type }}</span>
                    @endforeach
                    <span class="hidden h-5 w-px bg-zinc-200 sm:block"></span>
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            @foreach ([['code' => 'SN', 'bg' => 'bg-green-500'], ['code' => 'CI', 'bg' => 'bg-orange-500'], ['code' => 'BF', 'bg' => 'bg-red-600'], ['code' => 'BJ', 'bg' => 'bg-yellow-500'], ['code' => 'CM', 'bg' => 'bg-teal-600']] as $country)
                                <span class="flex size-7 items-center justify-center rounded-full border-2 border-white text-[9px] font-black text-white shadow-sm {{ $country['bg'] }}">{{ $country['code'] }}</span>
                            @endforeach
                        </div>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">+15 pays africains</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ CATEGORIES ════════════════════════════════ --}}
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-zinc-200/80 bg-white/85 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/85 dark:border-zinc-700/60">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Catégories</p>
                        <h2 class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">Explorez les services les plus recherchés</h2>
                    </div>
                    <a href="{{ route('search') }}" class="text-sm font-semibold text-teal-700 transition hover:text-zinc-950">
                        Toutes les catégories →
                    </a>
                </div>

                @php
                    $categoryIcons = [
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008z"/>',
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>',
                    ];
                @endphp

                <div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-8">
                    @foreach ($categories as $index => $category)
                        <a
                            href="{{ route('categories.show', $category->slug) }}"
                            class="group rounded-[1.6rem] border border-zinc-100 bg-stone-50 p-4 transition hover:-translate-y-1 hover:border-teal-200 hover:bg-white hover:shadow-lg dark:bg-zinc-900 dark:border-zinc-800"
                        >
                            <div class="flex size-14 items-center justify-center rounded-2xl" style="background-color: {{ ($category->color ?? '#14b8a6') }}18;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" style="color: {{ $category->color ?? '#0f766e' }};">
                                    {!! $categoryIcons[$index % count($categoryIcons)] !!}
                                </svg>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold leading-5 text-zinc-900 transition group-hover:text-teal-700 dark:text-zinc-100">{{ $category->name }}</h3>
                            <p class="mt-2 text-xs text-zinc-400">{{ $category->gigs_count }} offre{{ $category->gigs_count !== 1 ? 's' : '' }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ FEATURED GIGS ═════════════════════════════ --}}
    @if ($featuredGigs->isNotEmpty())
        <section class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Sélection</p>
                        <h2 class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">Services mis en avant</h2>
                    </div>
                    <a href="{{ route('search', ['featured' => 1]) }}" class="text-sm font-semibold text-teal-700 transition hover:text-zinc-950">Voir tout →</a>
                </div>
                <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($featuredGigs as $gig)
                        @include('components.gig-card', ['gig' => $gig])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ POPULAR GIGS ══════════════════════════════ --}}
    @if ($popularGigs->isNotEmpty())
        <section class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-amber-600">Top services</p>
                        <h2 class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">Les gigs qui convertissent le mieux</h2>
                    </div>
                    <a href="{{ route('search') }}" class="text-sm font-semibold text-teal-700 transition hover:text-zinc-950">Voir tout →</a>
                </div>
                <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($popularGigs as $gig)
                        @include('components.gig-card', ['gig' => $gig])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ HOW IT WORKS ══════════════════════════════ --}}
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Simple & transparent</p>
                <h2 class="mt-3 text-3xl font-black text-zinc-950 dark:text-white">Comment ça marche ?</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-zinc-500 dark:text-zinc-400">
                    De la recherche à la livraison, AfriTask fluidifie chaque étape de votre collaboration.
                </p>
            </div>

            <div class="relative grid gap-6 lg:grid-cols-4">
                <div class="absolute left-[12.5%] top-14 hidden h-0.5 w-3/4 bg-gradient-to-r from-teal-200 via-amber-200 to-teal-200 lg:block"></div>

                @foreach ([
                    [
                        'step'  => '01',
                        'title' => 'Choisissez un talent',
                        'text'  => 'Portfolio, note, délai, spécialité — tout est visible avant de commander.',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>',
                        'color' => 'bg-teal-50 text-teal-700 ring-teal-200',
                    ],
                    [
                        'step'  => '02',
                        'title' => 'Payez en escrow',
                        'text'  => 'Le paiement est réservé et libéré uniquement quand la livraison est validée.',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25z"/>',
                        'color' => 'bg-amber-50 text-amber-700 ring-amber-200',
                    ],
                    [
                        'step'  => '03',
                        'title' => 'Suivez la production',
                        'text'  => 'Messagerie, révisions et statut de commande gardent le projet sous contrôle.',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>',
                        'color' => 'bg-sky-50 text-sky-700 ring-sky-200',
                    ],
                    [
                        'step'  => '04',
                        'title' => 'Validez & laissez un avis',
                        'text'  => 'Approuvez la livraison finale, libérez le paiement et notez le freelance.',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                        'color' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                    ],
                ] as $step)
                    <article class="relative rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-lg shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                        <div class="flex size-14 items-center justify-center rounded-2xl ring-1 {{ $step['color'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                {!! $step['icon'] !!}
                            </svg>
                        </div>
                        <p class="absolute right-5 top-5 text-xs font-bold text-zinc-200">{{ $step['step'] }}</p>
                        <h3 class="mt-5 text-lg font-black text-zinc-950 dark:text-white">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400">{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ TRUST / BENEFITS ═══════════════════════════ --}}
    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-zinc-200/80 bg-white/85 shadow-xl shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/85 dark:border-zinc-700/60">
                <div class="grid gap-0 divide-y lg:grid-cols-3 lg:divide-x lg:divide-y-0 divide-zinc-100 dark:divide-zinc-800">
                    @foreach ([
                        [
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25z"/>',
                            'icolor'=> 'text-teal-700',
                            'ibg'   => 'bg-teal-50 ring-teal-200',
                            'title' => 'Paiement 100% sécurisé',
                            'text'  => "Votre argent est bloqué en escrow et libéré uniquement une fois votre livraison validée. Zéro risque.",
                            'badge' => 'Escrow',
                            'bc'    => 'bg-teal-50 text-teal-700',
                        ],
                        [
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.955 11.955 0 0 0 3 10.5c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.25-8.25-3.286Z"/>',
                            'icolor'=> 'text-amber-700',
                            'ibg'   => 'bg-amber-50 ring-amber-200',
                            'title' => 'Freelances vérifiés',
                            'text'  => "Chaque professionnel passe par un processus de vérification d'identité et de compétences avant d'être visible.",
                            'badge' => 'Vérifié',
                            'bc'    => 'bg-amber-50 text-amber-700',
                        ],
                        [
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.955 11.955 0 0 0 3 10.5c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.25-8.25-3.286Z"/>',
                            'icolor'=> 'text-sky-700',
                            'ibg'   => 'bg-sky-50 ring-sky-200',
                            'title' => 'Support & médiation 24/7',
                            'text'  => "Un litige ? Notre équipe intervient pour trouver une solution équitable entre client et freelance.",
                            'badge' => 'Support',
                            'bc'    => 'bg-sky-50 text-sky-700',
                        ],
                    ] as $benefit)
                        <div class="flex flex-col gap-4 p-8">
                            <div class="flex items-center justify-between">
                                <div class="flex size-12 items-center justify-center rounded-2xl ring-1 {{ $benefit['ibg'] }} {{ $benefit['icolor'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        {!! $benefit['icon'] !!}
                                    </svg>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $benefit['bc'] }}">{{ $benefit['badge'] }}</span>
                            </div>
                            <h3 class="text-lg font-black text-zinc-950 dark:text-white">{{ $benefit['title'] }}</h3>
                            <p class="text-sm leading-7 text-zinc-500 dark:text-zinc-400">{{ $benefit['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ PAYMENT STRIP ══════════════════════════════ --}}
    <section class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] bg-zinc-950 px-8 py-10 text-white shadow-2xl shadow-zinc-900/20">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-300">Paiements africains & internationaux</p>
                        <h2 class="mt-3 text-3xl font-black">Mobile money, cartes et flux hybrides.</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-zinc-400">
                            Payez avec les méthodes que vous utilisez au quotidien. MTN MoMo, Orange Money, Flooz, Moov Money, Stripe — tout est intégré, sans friction.
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3">
                        @foreach ([
                            ['label' => 'MTN MoMo',     'class' => 'bg-amber-400 text-zinc-900',  'mobile' => true],
                            ['label' => 'Orange Money',  'class' => 'bg-orange-500 text-white',    'mobile' => true],
                            ['label' => 'Flooz',         'class' => 'bg-sky-500 text-white',       'mobile' => true],
                            ['label' => 'Moov Money',    'class' => 'bg-emerald-500 text-white',   'mobile' => true],
                            ['label' => 'Wave',          'class' => 'bg-cyan-400 text-zinc-900',   'mobile' => true],
                            ['label' => 'Stripe',        'class' => 'bg-indigo-500 text-white',    'mobile' => false],
                        ] as $pm)
                            <div class="flex items-center gap-2.5 rounded-2xl px-4 py-3 {{ $pm['class'] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    @if ($pm['mobile'])
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 15.75h3"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5z"/>
                                    @endif
                                </svg>
                                <span class="text-sm font-bold">{{ $pm['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ FREELANCER CTA (guests) ════════════════════ --}}
    @guest
        <section class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-2">

                    {{-- Client CTA --}}
                    <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-8 shadow-lg shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                        <div class="flex size-14 items-center justify-center rounded-2xl bg-teal-50 ring-1 ring-teal-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.918-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z"/>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-2xl font-black text-zinc-950 dark:text-white">Trouvez un freelance maintenant</h3>
                        <p class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400">
                            Parcourez des centaines de services vérifiés, comparez les profils et passez commande en quelques minutes avec paiement sécurisé.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('search') }}"
                               class="inline-flex items-center justify-center rounded-full bg-zinc-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                                Explorer les services
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center rounded-full border border-zinc-200 bg-white px-6 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700">
                                Créer un compte client
                            </a>
                        </div>
                    </div>

                    {{-- Freelancer CTA --}}
                    <div class="relative overflow-hidden rounded-[2rem] border border-teal-200/60 bg-[linear-gradient(135deg,_rgba(20,184,166,0.1),_rgba(245,158,11,0.1))] p-8 shadow-lg shadow-zinc-900/5 backdrop-blur">
                        <div class="absolute -right-6 -top-6 size-32 rounded-full bg-teal-300/20 blur-2xl"></div>
                        <div class="absolute -bottom-8 -left-4 size-28 rounded-full bg-amber-300/20 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-teal-600 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <h3 class="mt-5 text-2xl font-black text-zinc-950 dark:text-white">Vendez vos compétences</h3>
                            <p class="mt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-400">
                                Créez votre vitrine, publiez vos services et recevez des paiements en mobile money ou par carte. AfriTask s'occupe de la sécurité.
                            </p>
                            <ul class="mt-5 grid gap-2">
                                @foreach (['Profil vérifié et mis en avant', 'Paiements en XOF, XAF, USD', 'Messagerie & révisions intégrées'] as $perk)
                                    <li class="flex items-center gap-2.5 text-sm text-zinc-700 dark:text-zinc-300">
                                        <span class="flex size-5 shrink-0 items-center justify-center rounded-full bg-teal-100 ring-1 ring-teal-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        </span>
                                        {{ $perk }}
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('register', ['role' => 'freelance']) }}"
                               class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-600/30 transition hover:bg-teal-700">
                                Devenir freelance
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endguest

    {{-- ═══════════════════════════ FOOTER CTA ════════════════════════════════ --}}
    <section class="pb-10 pt-4">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-zinc-200/60 bg-zinc-950 px-8 py-12 text-center text-white shadow-2xl shadow-zinc-900/20">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-300">Rejoignez AfriTask</p>
                <h2 class="mt-3 text-3xl font-black sm:text-4xl">L'Afrique mérite une marketplace à sa hauteur.</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-zinc-400">
                    Talents locaux, paiements adaptés, confiance garantie — tout ce qu'il faut pour que vos projets avancent sans friction.
                </p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-full bg-teal-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/30 transition hover:bg-teal-500">
                            Accéder à mon tableau de bord
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-full bg-teal-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-teal-600/30 transition hover:bg-teal-500">
                            Créer un compte gratuit
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('search') }}"
                           class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-8 py-3.5 text-sm font-semibold text-white transition hover:bg-white/20">
                            Explorer les services
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

</div>
