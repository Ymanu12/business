<div>
    {{-- Bannière + avatar --}}
    <div class="relative h-44 bg-[linear-gradient(135deg,_#0f172a_0%,_#0f766e_60%,_#f59e0b_140%)] sm:h-56">
        @if ($user->banner)
            <img src="{{ asset('storage/' . $user->banner) }}" alt="Bannière"
                 class="h-full w-full object-cover">
        @endif
    </div>

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="relative -mt-14 mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-end gap-4">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                     class="size-24 rounded-[1.25rem] object-cover ring-4 ring-white shadow-xl">
                <div class="pb-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-black text-zinc-950 dark:text-white">{{ $user->name }}</h1>
                        @if ($user->is_verified)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-teal-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                    @if ($user->freelancerProfile?->tagline)
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $user->freelancerProfile->tagline }}</p>
                    @endif
                </div>
            </div>

            @auth
                @if (auth()->id() === $user->id)
                    <a href="{{ route('profile.edit') }}" wire:navigate
                       class="rounded-full border border-zinc-200 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                        Modifier le profil
                    </a>
                @else
                    <button
                        wire:click="contacter"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-full bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 active:scale-95 disabled:opacity-60"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.812L3 20l1.063-3.188A7.477 7.477 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span wire:loading.remove wire:target="contacter">Contacter</span>
                        <span wire:loading wire:target="contacter">Connexion…</span>
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-full bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.812L3 20l1.063-3.188A7.477 7.477 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Contacter
                </a>
            @endauth
        </div>

        <div class="flex flex-col gap-8 lg:flex-row lg:items-start">

            {{-- Sidebar infos --}}
            <aside class="w-full lg:w-72 lg:shrink-0">
                @if ($user->freelancerProfile)
                    @php $fp = $user->freelancerProfile; @endphp

                    {{-- Stats --}}
                    <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-stone-50 p-4 text-center dark:bg-zinc-900">
                                <div class="text-2xl font-black text-zinc-950 dark:text-white">
                                    {{ $fp->avg_rating ? number_format($fp->avg_rating, 1) : '—' }}
                                </div>
                                <div class="mt-1 text-[11px] text-zinc-400">Note moyenne</div>
                            </div>
                            <div class="rounded-2xl bg-stone-50 p-4 text-center dark:bg-zinc-900">
                                <div class="text-2xl font-black text-zinc-950 dark:text-white">{{ $fp->completed_orders ?? 0 }}</div>
                                <div class="mt-1 text-[11px] text-zinc-400">Commandes</div>
                            </div>
                            <div class="rounded-2xl bg-stone-50 p-4 text-center dark:bg-zinc-900">
                                <div class="text-2xl font-black text-zinc-950 dark:text-white">{{ $fp->completionRate() }}%</div>
                                <div class="mt-1 text-[11px] text-zinc-400">Livraisons à temps</div>
                            </div>
                            <div class="rounded-2xl bg-stone-50 p-4 text-center dark:bg-zinc-900">
                                <div class="text-xl font-black text-zinc-950 dark:text-white">{{ $fp->levelLabel() }}</div>
                                <div class="mt-1 text-[11px] text-zinc-400">Niveau</div>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-2">
                            @if ($user->city)
                                <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $user->city }}{{ $user->country_code ? ', ' . $user->country_code : '' }}
                                </div>
                            @endif

                            @php
                                $availability = [
                                    'available'   => ['Disponible', 'text-emerald-700 bg-emerald-100'],
                                    'busy'        => ['Occupé', 'text-amber-700 bg-amber-100'],
                                    'unavailable' => ['Indisponible', 'text-zinc-600 bg-zinc-100'],
                                ];
                                [$label, $cls] = $availability[$fp->availability] ?? ['—', 'text-zinc-400 bg-zinc-100'];
                            @endphp
                            <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold {{ $cls }}">
                                {{ $label }}
                            </span>
                        </div>

                        {{-- Compétences --}}
                        @if ($fp->skills && count($fp->skills) > 0)
                            <div class="mt-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-zinc-400">Compétences</p>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach (array_slice($fp->skills, 0, 10) as $skill)
                                        <span class="rounded-full border border-zinc-200 bg-white px-2.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Langues --}}
                        @if ($fp->languages && count($fp->languages) > 0)
                            <div class="mt-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-zinc-400">Langues</p>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach ($fp->languages as $lang)
                                        <span class="rounded-full border border-zinc-200 bg-white px-2.5 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700">{{ $lang }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Liens --}}
                        @if ($fp->portfolio_url || $fp->linkedin_url || $fp->github_url)
                            <div class="mt-4 grid gap-2">
                                @if ($fp->portfolio_url)
                                    <a href="{{ $fp->portfolio_url }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-2 text-sm font-medium text-teal-700 transition hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Portfolio
                                    </a>
                                @endif
                                @if ($fp->linkedin_url)
                                    <a href="{{ $fp->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-2 text-sm font-medium text-sky-700 transition hover:underline">
                                        LinkedIn
                                    </a>
                                @endif
                                @if ($fp->github_url)
                                    <a href="{{ $fp->github_url }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-2 text-sm font-medium text-zinc-700 transition hover:underline dark:text-zinc-300">
                                        GitHub
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Badges --}}
                    @if ($user->badges->isNotEmpty())
                        <div class="mt-4 rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-zinc-400">Badges</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($user->badges as $badge)
                                    <div class="flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white px-3 py-1.5 dark:bg-zinc-800 dark:border-zinc-700">
                                        <span class="text-base">{{ $badge->icon }}</span>
                                        <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ $badge->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </aside>

            {{-- Services --}}
            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-black text-zinc-950 dark:text-white">
                    Services de {{ $user->name }}
                    <span class="text-zinc-400">({{ $user->gigs->count() }})</span>
                </h2>

                @if ($user->gigs->isEmpty())
                    <div class="mt-6 rounded-[2rem] border border-zinc-200 bg-white/90 px-6 py-12 text-center shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Aucun service publié pour le moment.</p>
                    </div>
                @else
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        @foreach ($user->gigs as $gig)
                            @include('components.gig-card', ['gig' => $gig])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
