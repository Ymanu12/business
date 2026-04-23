<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Espace commandes</p>
            <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Mes commandes</h1>
        </div>
        @if (auth()->user()->isClient())
            <a href="{{ route('search') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-full bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                Commander un service
            </a>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="mt-6 flex gap-1 rounded-2xl border border-zinc-200 bg-stone-50 p-1 dark:bg-zinc-900 dark:border-zinc-700">
        <button wire:click="$set('tab', 'active')"
                class="flex-1 rounded-xl px-4 py-2.5 text-sm transition {{ $tab === 'active' ? 'bg-white shadow-sm font-semibold text-zinc-950' : 'text-zinc-500 hover:text-zinc-700' }}">
            En cours <span class="ml-1.5 rounded-full bg-teal-100 px-2 py-0.5 text-xs font-semibold text-teal-700">{{ $activeOrders->count() }}</span>
        </button>
        <button wire:click="$set('tab', 'history')"
                class="flex-1 rounded-xl px-4 py-2.5 text-sm transition {{ $tab === 'history' ? 'bg-white shadow-sm font-semibold text-zinc-950' : 'text-zinc-500 hover:text-zinc-700' }}">
            Historique <span class="ml-1.5 rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-semibold text-zinc-600 dark:text-zinc-400">{{ $completedOrders->count() }}</span>
        </button>
    </div>

    @php $orders = $tab === 'active' ? $activeOrders : $completedOrders; @endphp

    @if ($orders->isEmpty())
        <div class="mt-10 rounded-[2rem] border border-zinc-200 bg-white/90 px-6 py-14 text-center shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-stone-100 text-zinc-400 dark:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
            </div>
            <h3 class="mt-5 text-lg font-bold text-zinc-900 dark:text-zinc-100">
                {{ $tab === 'active' ? 'Aucune commande en cours' : 'Aucun historique' }}
            </h3>
            @if ($tab === 'active' && auth()->user()->isClient())
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Trouvez un freelance et passez votre première commande.</p>
                <a href="{{ route('search') }}" wire:navigate
                   class="mt-6 inline-flex items-center rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                    Explorer les services
                </a>
            @endif
        </div>
    @else
        <div class="mt-6 grid gap-4">
            @foreach ($orders as $order)
                @php
                    $other = auth()->user()->isFreelancer() ? $order->client : $order->freelancer;
                @endphp
                <article class="group overflow-hidden rounded-[1.75rem] border border-zinc-200/80 bg-white shadow-sm transition hover:shadow-md dark:border-zinc-700/60 dark:bg-zinc-800">
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
                        {{-- Thumbnail --}}
                        @if ($order->gig)
                            <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
                               class="relative h-20 w-32 shrink-0 overflow-hidden rounded-xl">
                                <img src="{{ $order->gig->thumbnailUrl() }}" alt="{{ $order->gig->title }}"
                                     class="h-full w-full object-cover transition group-hover:scale-105">
                            </a>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                @php
                                    $statusColors = [
                                        'paid'               => 'bg-sky-100 text-sky-700',
                                        'in_progress'        => 'bg-teal-100 text-teal-700',
                                        'delivered'          => 'bg-amber-100 text-amber-700',
                                        'revision_requested' => 'bg-orange-100 text-orange-700',
                                        'completed'          => 'bg-emerald-100 text-emerald-700',
                                        'cancelled'          => 'bg-zinc-100 text-zinc-600',
                                        'disputed'           => 'bg-rose-100 text-rose-700',
                                        'refunded'           => 'bg-purple-100 text-purple-700',
                                    ];
                                    $statusLabels = [
                                        'paid'               => 'Payé',
                                        'in_progress'        => 'En cours',
                                        'delivered'          => 'Livré',
                                        'revision_requested' => 'Révision demandée',
                                        'completed'          => 'Terminé',
                                        'cancelled'          => 'Annulé',
                                        'disputed'           => 'En litige',
                                        'refunded'           => 'Remboursé',
                                    ];
                                    $statusVal = $order->status->value;
                                @endphp
                                <span class="rounded-full px-2.5 py-1 font-semibold {{ $statusColors[$statusVal] ?? 'bg-zinc-100 text-zinc-600' }}">
                                    {{ $statusLabels[$statusVal] ?? $statusVal }}
                                </span>
                                <span class="text-zinc-400">{{ $order->created_at->format('d M Y') }}</span>
                            </div>

                            <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
                               class="mt-1.5 block text-base font-bold text-zinc-950 transition line-clamp-1 hover:text-teal-700 dark:text-white">
                                {{ $order->title ?? $order->gig?->title ?? 'Commande #' . $order->id }}
                            </a>

                            <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                @if ($other)
                                    <span class="flex items-center gap-1.5">
                                        <img src="{{ $other->avatarUrl() }}" alt="{{ $other->name }}"
                                             class="size-5 rounded-full object-cover">
                                        {{ $other->name }}
                                    </span>
                                @endif
                                <span class="font-semibold text-teal-700">{{ $order->formattedPrice() }}</span>
                                @if ($order->due_date && in_array($statusVal, ['in_progress', 'paid']))
                                    <span class="flex items-center gap-1 {{ $order->daysLeft() <= 1 ? 'text-rose-600 font-semibold' : '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $order->daysLeft() === 0 ? 'Dû aujourd\'hui' : $order->daysLeft() . ' j restants' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
                           class="shrink-0 rounded-full border border-zinc-200 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                            Voir
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
