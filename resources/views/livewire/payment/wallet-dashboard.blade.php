<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Paiements</p>
            <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Mon wallet</h1>
        </div>
        @if (auth()->user()->isFreelancer())
            <a href="{{ route('wallet.withdraw') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-full bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                Demander un retrait
            </a>
        @endif
    </div>

    {{-- Cartes solde --}}
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="overflow-hidden rounded-[1.75rem] bg-[linear-gradient(135deg,_#0f172a,_#0f766e)] p-6 text-white shadow-2xl shadow-zinc-900/15">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-100">Solde disponible</p>
            <div class="mt-4 text-4xl font-black">{{ $wallet->formattedBalance() }}</div>
            <p class="mt-2 text-xs text-teal-200">Fonds disponibles immédiatement</p>
        </div>

        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-zinc-400">En attente</p>
            <div class="mt-4 text-3xl font-black text-zinc-950 dark:text-white">
                {{ number_format($wallet->pending_balance, 0, ',', ' ') }} {{ $wallet->currency }}
            </div>
            <p class="mt-2 text-xs text-zinc-400">Bloqué sous escrow</p>
        </div>

        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-zinc-400">Total créé</p>
            <div class="mt-4 text-3xl font-black text-zinc-950 dark:text-white">
                {{ number_format(($wallet->balance ?? 0) + ($wallet->pending_balance ?? 0), 0, ',', ' ') }} {{ $wallet->currency }}
            </div>
            <p class="mt-2 text-xs text-zinc-400">Disponible + en attente</p>
        </div>
    </div>

    {{-- Transactions --}}
    <div class="mt-8 rounded-[1.75rem] border border-zinc-200/80 bg-white/90 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
        <div class="flex items-center justify-between gap-4 border-b border-zinc-100 px-6 py-4 dark:border-zinc-800">
            <h2 class="text-base font-black text-zinc-950 dark:text-white">Transactions récentes</h2>
        </div>

        @if ($transactions->isEmpty())
            <div class="px-6 py-12 text-center">
                <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-stone-100 text-zinc-400 dark:bg-zinc-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5z"/>
                    </svg>
                </div>
                <p class="mt-4 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Aucune transaction</p>
                <p class="mt-1 text-xs text-zinc-400">Vos paiements apparaîtront ici une fois les commandes passées.</p>
            </div>
        @else
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach ($transactions as $tx)
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-full {{ $tx->type === 'credit' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            @if ($tx->type === 'credit')
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $tx->description ?? ucfirst($tx->type) }}</div>
                            <div class="mt-0.5 text-xs text-zinc-400">{{ $tx->created_at->format('d M Y à H:i') }}</div>
                        </div>

                        <div class="shrink-0 text-right">
                            <div class="text-sm font-black {{ $tx->type === 'credit' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', ' ') }} {{ $wallet->currency }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
