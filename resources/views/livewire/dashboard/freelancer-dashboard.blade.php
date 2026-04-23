<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Hero --}}
    <section class="relative overflow-hidden rounded-[2.2rem] border border-white/70 bg-[linear-gradient(135deg,_#111827_0%,_#0f766e_40%,_#1d4ed8_120%)] px-6 py-8 text-white shadow-2xl shadow-zinc-900/15 lg:px-8 lg:py-10 dark:border-white/15">
        <div class="absolute -right-10 top-10 h-40 w-40 rounded-full bg-sky-400/15 blur-3xl"></div>
        <div class="absolute -left-10 bottom-0 h-44 w-44 rounded-full bg-amber-300/15 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-50 backdrop-blur">
                    Espace freelance — {{ auth()->user()->name }}
                </div>
                <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl">
                    @if ($activeOrdersCount > 0)
                        {{ $activeOrdersCount }} commande{{ $activeOrdersCount > 1 ? 's' : '' }} active{{ $activeOrdersCount > 1 ? 's' : '' }}
                        @if ($urgentCount > 0)
                            · <span class="text-amber-300">{{ $urgentCount }} urgente{{ $urgentCount > 1 ? 's' : '' }}</span>
                        @endif
                    @else
                        Aucune commande active pour le moment.
                    @endif
                </h1>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('seller.gigs.create') }}" wire:navigate
                       class="inline-flex items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-zinc-950 transition hover:bg-amber-300 dark:bg-zinc-800 dark:text-white">
                        Créer un service
                    </a>
                    <a href="{{ route('seller.gigs.index') }}" wire:navigate
                       class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/15">
                        Gérer mes services
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 sm:grid-cols-1 sm:gap-4 sm:w-56 sm:shrink-0">
                <article class="rounded-[1.6rem] border border-white/12 bg-white/10 p-4 backdrop-blur text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-100/70">Commandes</p>
                    <div class="mt-3 text-3xl font-black">{{ $activeOrdersCount }}</div>
                </article>
                <article class="rounded-[1.6rem] border border-white/12 bg-white/10 p-4 backdrop-blur text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-100/70">Terminées</p>
                    <div class="mt-3 text-3xl font-black">{{ $profile?->completed_orders ?? 0 }}</div>
                </article>
                <article class="rounded-[1.6rem] border border-white/12 bg-white/10 p-4 backdrop-blur text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-100/70">Note</p>
                    <div class="mt-3 text-2xl font-black">{{ number_format($profile?->avg_rating ?? 0, 1) }}</div>
                </article>
            </div>
        </div>
    </section>

    <div class="mt-8 grid gap-6 xl:grid-cols-[1fr_320px]">

        {{-- Commandes actives --}}
        <div class="grid gap-4">
            @if ($activeOrders->isEmpty())
                <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-10 text-center shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-zinc-400 text-sm">Aucune commande active.</p>
                    <a href="{{ route('seller.gigs.create') }}" wire:navigate
                       class="mt-4 inline-flex items-center justify-center rounded-full bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Créer un service
                    </a>
                </div>
            @else
                <h2 class="text-base font-black text-zinc-950 dark:text-white">Commandes à traiter</h2>
                @foreach ($activeOrders as $order)
                    <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
                       class="block rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-teal-300 hover:shadow-md dark:bg-zinc-800/90 dark:border-zinc-700/60">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-zinc-950 dark:text-white">{{ $order->title ?? $order->gig?->title }}</p>
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    Client : {{ $order->client->name }}
                                    · Livraison {{ $order->due_date?->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                            <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $order->status->tailwindBg() }}">
                                {{ $order->status->label() }}
                            </span>
                        </div>

                        @php
                            $total   = max(1, $order->delivery_days);
                            $elapsed = $order->created_at->diffInDays(now());
                            $pct     = min(100, (int)(($elapsed / $total) * 100));
                        @endphp
                        <div class="mt-4 flex items-center gap-3">
                            <div class="h-1.5 flex-1 rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <div class="h-1.5 rounded-full {{ $pct > 80 ? 'bg-rose-400' : 'bg-teal-500' }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs {{ $order->daysLeft() <= 1 ? 'font-bold text-rose-600' : 'text-zinc-400' }}">
                                {{ $order->daysLeft() }}j restant{{ $order->daysLeft() !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        <div class="mt-3 text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $order->formattedPrice() }}
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="grid gap-4 self-start">
            {{-- Wallet --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Wallet disponible</p>
                <div class="mt-3 text-2xl font-black text-teal-700">{{ $wallet->formattedBalance() }}</div>
                @if ($wallet->pending_balance > 0)
                    <p class="mt-1 text-xs text-zinc-400">+ {{ number_format($wallet->pending_balance, 0, ',', ' ') }} {{ $wallet->currency }} en attente</p>
                @endif
                <div class="mt-4 grid gap-2">
                    <a href="{{ route('wallet') }}" wire:navigate
                       class="rounded-full bg-zinc-950 px-4 py-2.5 text-center text-xs font-semibold text-white transition hover:bg-teal-700">
                        Voir les paiements
                    </a>
                    @if ($wallet->balance > 0)
                        <a href="{{ route('wallet.withdraw') }}" wire:navigate
                           class="rounded-full border border-zinc-200 px-4 py-2.5 text-center text-xs font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                            Faire un retrait
                        </a>
                    @endif
                </div>
            </div>

            {{-- Stats profil --}}
            @if ($profile)
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Performance</p>
                    <div class="mt-4 grid gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">Taux de réponse</span>
                            <span class="text-sm font-bold text-zinc-950 dark:text-white">{{ $profile->response_rate ?? '—' }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">Commandes terminées</span>
                            <span class="text-sm font-bold text-zinc-950 dark:text-white">{{ $profile->completed_orders }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-zinc-600 dark:text-zinc-400">Note moyenne</span>
                            <span class="text-sm font-bold text-zinc-950 dark:text-white">{{ number_format($profile->avg_rating, 1) }} / 5</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Actions</p>
                <div class="mt-4 grid gap-2">
                    <a href="{{ route('orders.index') }}" wire:navigate
                       class="rounded-[1.2rem] border border-zinc-200 bg-stone-50 px-4 py-3 text-sm font-semibold text-zinc-800 transition hover:border-teal-300 hover:text-teal-700 dark:bg-zinc-900 dark:text-zinc-200 dark:border-zinc-700">
                        Toutes les commandes
                    </a>
                    <a href="{{ route('inbox') }}" wire:navigate
                       class="rounded-[1.2rem] border border-zinc-200 bg-stone-50 px-4 py-3 text-sm font-semibold text-zinc-800 transition hover:border-teal-300 hover:text-teal-700 dark:bg-zinc-900 dark:text-zinc-200 dark:border-zinc-700">
                        Messagerie
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
