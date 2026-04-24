<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Hero --}}
    <section class="relative overflow-hidden rounded-[2.2rem] border border-white/70 bg-[linear-gradient(135deg,_#1e1b4b_0%,_#4f46e5_50%,_#7c3aed_120%)] px-6 py-8 text-white shadow-2xl shadow-zinc-900/15 lg:px-8 lg:py-10 dark:border-white/15">
        <div class="absolute -right-10 top-10 h-40 w-40 rounded-full bg-violet-400/15 blur-3xl"></div>
        <div class="absolute -left-10 bottom-0 h-44 w-44 rounded-full bg-indigo-300/15 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-violet-100 backdrop-blur">
                    Administration — {{ auth()->user()->name }}
                </div>
                <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl">
                    Tableau de bord admin
                </h1>
                <p class="mt-2 text-sm text-violet-200/80">Vue globale de la plateforme AfriTask</p>

                @if ($disputedOrders > 0 || $pendingWithdrawals > 0 || $gigsPending > 0)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if ($disputedOrders > 0)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/20 border border-red-400/30 px-3 py-1.5 text-xs font-semibold text-red-200">
                                {{ $disputedOrders }} litige{{ $disputedOrders > 1 ? 's' : '' }} ouvert{{ $disputedOrders > 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if ($pendingWithdrawals > 0)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/20 border border-amber-400/30 px-3 py-1.5 text-xs font-semibold text-amber-200">
                                {{ $pendingWithdrawals }} retrait{{ $pendingWithdrawals > 1 ? 's' : '' }} en attente
                            </span>
                        @endif
                        @if ($gigsPending > 0)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-500/20 border border-sky-400/30 px-3 py-1.5 text-xs font-semibold text-sky-200">
                                {{ $gigsPending }} service{{ $gigsPending > 1 ? 's' : '' }} à valider
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- KPI rapides --}}
            <div class="grid grid-cols-2 gap-3 sm:w-64 sm:shrink-0">
                <article class="rounded-[1.6rem] border border-white/12 bg-white/10 p-4 backdrop-blur text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-violet-100/70">Utilisateurs</p>
                    <div class="mt-2 text-3xl font-black">{{ number_format($totalUsers) }}</div>
                    @if ($newUsersToday > 0)
                        <p class="mt-1 text-[10px] text-violet-200/60">+{{ $newUsersToday }} aujourd'hui</p>
                    @endif
                </article>
                <article class="rounded-[1.6rem] border border-white/12 bg-white/10 p-4 backdrop-blur text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-violet-100/70">Commandes</p>
                    <div class="mt-2 text-3xl font-black">{{ number_format($totalOrders) }}</div>
                    <p class="mt-1 text-[10px] text-violet-200/60">{{ $activeOrders }} actives</p>
                </article>
                <article class="col-span-2 rounded-[1.6rem] border border-white/12 bg-white/10 p-4 backdrop-blur text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-violet-100/70">Revenus ce mois</p>
                    <div class="mt-2 text-2xl font-black">{{ number_format($revenueMonth, 0, ',', ' ') }} XAF</div>
                    <p class="mt-1 text-[10px] text-violet-200/60">Total : {{ number_format($revenueTotal, 0, ',', ' ') }} XAF</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Stats détaillées --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Clients</p>
            <div class="mt-3 text-3xl font-black text-zinc-950 dark:text-white">{{ number_format($totalClients) }}</div>
            <p class="mt-1 text-xs text-zinc-400">acheteurs inscrits</p>
        </div>

        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Freelancers</p>
            <div class="mt-3 text-3xl font-black text-zinc-950 dark:text-white">{{ number_format($totalFreelancers) }}</div>
            <p class="mt-1 text-xs text-zinc-400">vendeurs actifs</p>
        </div>

        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Services publiés</p>
            <div class="mt-3 text-3xl font-black text-zinc-950 dark:text-white">{{ number_format($gigsPublished) }}</div>
            <p class="mt-1 text-xs text-amber-500 font-semibold">{{ $gigsPending }} en attente de validation</p>
        </div>

        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Commandes terminées</p>
            <div class="mt-3 text-3xl font-black text-zinc-950 dark:text-white">{{ number_format($completedOrders) }}</div>
            @if ($disputedOrders > 0)
                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $disputedOrders }} litige{{ $disputedOrders > 1 ? 's' : '' }}</p>
            @else
                <p class="mt-1 text-xs text-zinc-400">aucun litige</p>
            @endif
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1fr_320px]">

        {{-- Commandes récentes --}}
        <div>
            <h2 class="mb-4 text-base font-black text-zinc-950 dark:text-white">Commandes récentes</h2>
            @if ($recentOrders->isEmpty())
                <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-10 text-center shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-sm text-zinc-400">Aucune commande pour le moment.</p>
                </div>
            @else
                <div class="grid gap-3">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
                           class="block rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-4 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-md dark:bg-zinc-800/90 dark:border-zinc-700/60">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-zinc-950 dark:text-white">
                                        {{ $order->title ?? $order->gig?->title ?? '#' . $order->id }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $order->client?->name ?? '—' }}
                                        <span class="mx-1 text-zinc-300">→</span>
                                        {{ $order->freelancer?->name ?? '—' }}
                                        · {{ $order->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 flex-col items-end gap-1.5">
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $order->status->tailwindBg() }}">
                                        {{ $order->status->label() }}
                                    </span>
                                    <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                        {{ number_format($order->price, 0, ',', ' ') }} {{ $order->currency }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="grid gap-4 self-start">

            {{-- Services en attente de validation --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Services à valider</p>
                @if ($pendingGigs->isEmpty())
                    <p class="mt-4 text-sm text-zinc-400">Aucun service en attente.</p>
                @else
                    <div class="mt-4 grid gap-3">
                        @foreach ($pendingGigs as $gig)
                            <div class="rounded-2xl border border-zinc-200/70 bg-zinc-50/80 p-3 dark:border-zinc-700/60 dark:bg-zinc-900/40">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-zinc-900 dark:text-white">{{ $gig->title }}</p>
                                        <p class="text-xs text-zinc-400">{{ $gig->freelancer?->name ?? 'Freelance inconnu' }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-yellow-100 px-2 py-0.5 text-[10px] font-semibold text-yellow-800">
                                        En attente
                                    </span>
                                </div>

                                <div class="mt-3 grid gap-2">
                                    <a
                                        href="{{ route('gigs.show', $gig->slug) }}"
                                        target="_blank"
                                        class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2 text-xs font-semibold text-zinc-700 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        Voir le contenu complet
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Alertes --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Alertes</p>
                <div class="mt-4 grid gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Litiges ouverts</span>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $disputedOrders > 0 ? 'bg-red-100 text-red-700' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' }}">
                            {{ $disputedOrders }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Retraits en attente</span>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $pendingWithdrawals > 0 ? 'bg-amber-100 text-amber-700' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' }}">
                            {{ $pendingWithdrawals }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Services à modérer</span>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $gigsPending > 0 ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' }}">
                            {{ $gigsPending }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Revenus --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Revenus plateforme</p>
                <div class="mt-4 grid gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Ce mois</span>
                        <span class="text-sm font-bold text-violet-700 dark:text-violet-400">{{ number_format($revenueMonth, 0, ',', ' ') }} XAF</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Total cumulé</span>
                        <span class="text-sm font-bold text-zinc-950 dark:text-white">{{ number_format($revenueTotal, 0, ',', ' ') }} XAF</span>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</div>
