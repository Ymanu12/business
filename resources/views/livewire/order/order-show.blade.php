<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- En-tête commande --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <nav class="flex items-center gap-2 text-xs text-zinc-400">
                <a href="{{ route('orders.index') }}" wire:navigate class="transition hover:text-teal-700">Mes commandes</a>
                <span>/</span>
                <span class="text-zinc-600 dark:text-zinc-400">{{ Str::limit($order->title ?? $order->gig?->title ?? 'Commande', 40) }}</span>
            </nav>
            <h1 class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">
                {{ $order->title ?? $order->gig?->title ?? 'Commande #' . $order->id }}
            </h1>
        </div>

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

        <div class="flex items-center gap-3">
            <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $statusColors[$statusVal] ?? 'bg-zinc-100 text-zinc-600' }}">
                {{ $statusLabels[$statusVal] ?? ucfirst($statusVal) }}
            </span>
            <button
                wire:click="ouvrirChat"
                wire:loading.attr="disabled"
                wire:target="ouvrirChat"
                class="inline-flex items-center gap-2 rounded-full bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 active:scale-95 disabled:opacity-60"
                title="Contacter via la messagerie"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.812L3 20l1.063-3.188A7.477 7.477 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span wire:loading.remove wire:target="ouvrirChat">Message</span>
                <span wire:loading wire:target="ouvrirChat">…</span>
            </button>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_320px]">

        {{-- Colonne principale --}}
        <div class="grid gap-6">

            {{-- Carte récapitulatif --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Prix total</p>
                        <p class="mt-2 text-xl font-black text-teal-700">{{ $order->formattedPrice() }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Délai</p>
                        <p class="mt-2 text-xl font-black text-zinc-950 dark:text-white">{{ $order->delivery_days }}j</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Date limite</p>
                        <p class="mt-2 text-base font-bold text-zinc-950 dark:text-white">
                            {{ $order->due_date ? $order->due_date->format('d M Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Révisions</p>
                        <p class="mt-2 text-xl font-black text-zinc-950 dark:text-white">
                            {{ $order->revisions_used }}/{{ $order->revisions_allowed }}
                        </p>
                    </div>
                </div>

                @if ($order->due_date && in_array($statusVal, ['in_progress', 'paid']))
                    <div class="mt-5 h-2 rounded-full bg-zinc-100 dark:bg-zinc-800">
                        @php
                            $total = max(1, $order->delivery_days);
                            $elapsed = $order->created_at->diffInDays(now());
                            $pct = min(100, (int)(($elapsed / $total) * 100));
                        @endphp
                        <div class="h-2 rounded-full {{ $pct > 80 ? 'bg-rose-500' : 'bg-teal-500' }}" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-zinc-400">
                        {{ $order->daysLeft() }} jour{{ $order->daysLeft() !== 1 ? 's' : '' }} restant{{ $order->daysLeft() !== 1 ? 's' : '' }}
                    </p>
                @endif
            </div>

            {{-- Livraisons --}}
            @if ($order->deliveries->isNotEmpty())
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Livraisons</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach ($order->deliveries as $delivery)
                            <div class="rounded-2xl border border-zinc-200 bg-stone-50 p-4 dark:bg-zinc-900 dark:border-zinc-700">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Livraison du {{ $delivery->created_at->format('d M Y à H:i') }}</div>
                                        @if ($delivery->message)
                                            <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">{{ $delivery->message }}</p>
                                        @endif
                                    </div>
                                    @if ($delivery->files ?? null)
                                        <a href="{{ asset('storage/' . $delivery->files) }}"
                                           class="shrink-0 rounded-full border border-teal-300 px-3 py-1.5 text-xs font-semibold text-teal-700 transition hover:bg-teal-50">
                                            Télécharger
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Messages --}}
            @if ($order->messages->isNotEmpty())
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Messages</h2>
                    <div class="mt-4 grid gap-3 max-h-80 overflow-y-auto">
                        @foreach ($order->messages->reverse() as $msg)
                            @php $isMe = $msg->sender_id === auth()->id(); @endphp
                            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs rounded-2xl px-4 py-3 text-sm {{ $isMe ? 'bg-zinc-950 text-white' : 'border border-zinc-200 bg-stone-50 text-zinc-800' }} dark:bg-zinc-900 dark:border-zinc-700">
                                    {{ $msg->message }}
                                    <div class="mt-1.5 text-[10px] {{ $isMe ? 'text-zinc-400' : 'text-zinc-400' }}">
                                        {{ $msg->created_at->format('d M, H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Avis --}}
            @if ($order->review)
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Avis laissé</h2>
                    <div class="mt-4">
                        <div class="flex gap-0.5">
                            @for ($s = 1; $s <= 5; $s++)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="size-4 {{ $s <= $order->review->rating ? 'text-amber-400' : 'text-zinc-200' }}"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        @if ($order->review->comment)
                            <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">{{ $order->review->comment }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="grid gap-5 self-start">

            {{-- Interlocuteur --}}
            @php $other = auth()->user()->isFreelancer() ? $order->client : $order->freelancer; @endphp
            @if ($other)
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">
                        {{ auth()->user()->isFreelancer() ? 'Client' : 'Freelance' }}
                    </p>
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ $other->avatarUrl() }}" alt="{{ $other->name }}"
                             class="size-10 rounded-full object-cover">
                        <div>
                            <div class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $other->name }}</div>
                            @if ($other->city)
                                <div class="text-xs text-zinc-400">{{ $other->city }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Service commandé --}}
            @if ($order->gig)
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Service</p>
                    <a href="{{ route('gigs.show', $order->gig->slug) }}" wire:navigate class="mt-3 block group">
                        <img src="{{ $order->gig->thumbnailUrl() }}" alt="{{ $order->gig->title }}"
                             class="aspect-video w-full rounded-xl object-cover transition group-hover:opacity-90">
                        <p class="mt-2 text-sm font-semibold text-zinc-900 line-clamp-2 group-hover:text-teal-700 dark:text-zinc-100">
                            {{ $order->gig->title }}
                        </p>
                    </a>
                </div>
            @endif

            {{-- Actions --}}
            <div class="grid gap-2">
                @if ($statusVal === 'completed' && !$order->review && auth()->id() === $order->client_id)
                    <a href="{{ route('reviews.create', $order->uuid) }}" wire:navigate
                       class="flex w-full items-center justify-center rounded-full bg-zinc-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Laisser un avis
                    </a>
                @endif

                <a href="{{ route('inbox') }}" wire:navigate
                   class="flex w-full items-center justify-center rounded-full border border-zinc-200 px-4 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                    Messagerie
                </a>
            </div>
        </aside>
    </div>
</div>
