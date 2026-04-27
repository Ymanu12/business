<div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- ── Actions (non imprimées) ── --}}
    <div class="mb-6 flex items-center justify-between print:hidden">
        <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
           class="flex items-center gap-2 text-sm font-semibold text-zinc-600 hover:text-teal-700 dark:text-zinc-400 dark:hover:text-teal-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Voir ma commande
        </a>
        <button onclick="window.print()"
                class="flex items-center gap-2 rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 dark:bg-zinc-700 dark:hover:bg-teal-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.054 48.054 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
            </svg>
            Imprimer le reçu
        </button>
    </div>

    {{-- ── Reçu ── --}}
    <div class="overflow-hidden rounded-[1.75rem] border border-zinc-200 bg-white shadow-2xl shadow-zinc-900/10 dark:bg-zinc-800 dark:border-zinc-700 print:rounded-none print:shadow-none print:border-0">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-zinc-900 to-teal-900 px-8 py-8 print:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-black tracking-tight text-white">AfriTask</div>
                    <div class="mt-1 text-xs text-teal-300 tracking-widest uppercase">Plateforme de freelance</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-400">Reçu de paiement</div>
                    <div class="mt-1 font-mono text-sm font-bold text-white">{{ $payment->transaction_ref }}</div>
                </div>
            </div>

            {{-- Badge succès --}}
            <div class="mt-6 flex items-center gap-3">
                <div class="flex size-10 items-center justify-center rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-black text-white">Paiement confirmé</div>
                    <div class="text-xs text-zinc-400">
                        {{ $payment->paid_at?->translatedFormat('d F Y à H:i') ?? $payment->created_at->translatedFormat('d F Y à H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Corps du reçu --}}
        <div class="px-8 py-8">

            {{-- Service commandé --}}
            <section>
                <h3 class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Service commandé</h3>
                <div class="mt-3 flex items-center gap-4">
                    @if ($order->gig)
                        <img src="{{ $order->gig->thumbnailUrl() }}" alt="{{ $order->title }}"
                             class="h-14 w-20 shrink-0 rounded-xl object-cover print:hidden">
                    @endif
                    <div>
                        <div class="font-bold text-zinc-950 dark:text-white">{{ $order->title }}</div>
                        @if ($order->package)
                            <span class="mt-1 inline-block rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">
                                Package {{ $order->package->typeLabel() }}
                            </span>
                        @endif
                    </div>
                </div>
            </section>

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Parties --}}
            <div class="grid gap-6 sm:grid-cols-2">
                <section>
                    <h3 class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Client</h3>
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ $order->client->avatarUrl() }}" alt="{{ $order->client->name }}"
                             class="size-9 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-700 print:hidden">
                        <div>
                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $order->client->name }}</div>
                            <div class="text-xs text-zinc-400">{{ $order->client->email }}</div>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Freelance</h3>
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ $order->freelancer->avatarUrl() }}" alt="{{ $order->freelancer->name }}"
                             class="size-9 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-700 print:hidden">
                        <div>
                            <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $order->freelancer->name }}</div>
                            <div class="text-xs text-zinc-400">Prestataire</div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Détail financier --}}
            <section>
                <h3 class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Détail du paiement</h3>
                <div class="mt-3 space-y-2.5">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500 dark:text-zinc-400">Sous-total service</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ number_format($order->price * 0.90, 0, ',', ' ') }} {{ $order->currency }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500 dark:text-zinc-400">Commission plateforme (10%)</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ number_format($order->price * 0.10, 0, ',', ' ') }} {{ $order->currency }}</span>
                    </div>
                    <div class="flex justify-between rounded-xl bg-zinc-50 px-4 py-3 text-sm font-black dark:bg-zinc-900">
                        <span class="text-zinc-950 dark:text-white">Total payé</span>
                        <span class="text-teal-700 text-base">{{ $order->formattedPrice() }}</span>
                    </div>
                </div>
            </section>

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Méthode de paiement --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Méthode</div>
                    <div class="mt-1.5 flex items-center gap-2">
                        @php
                            $methodIcons = [
                                'wallet'       => ['text-teal-600', 'M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3'],
                                'mtn_momo'     => ['text-amber-500', 'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3'],
                                'orange_money' => ['text-orange-500', 'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3'],
                                'stripe'       => ['text-blue-600', 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z'],
                            ];
                            $methodKey = $payment->method->value;
                            [$iconColor, $iconPath] = $methodIcons[$methodKey] ?? $methodIcons['wallet'];
                        @endphp
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                        </svg>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $payment->method->label() }}</span>
                    </div>
                </div>

                <div>
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Référence</div>
                    <div class="mt-1.5 font-mono text-xs text-zinc-600 dark:text-zinc-400 break-all">
                        {{ $payment->transaction_ref }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Délai de livraison</div>
                    <div class="mt-1.5 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $order->delivery_days }} jour{{ $order->delivery_days > 1 ? 's' : '' }}
                        <span class="ml-1 text-xs font-normal text-zinc-400">
                            (avant le {{ $order->due_date?->translatedFormat('d M Y') ?? now()->addDays($order->delivery_days)->translatedFormat('d M Y') }})
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Statut paiement</div>
                    <div class="mt-1.5 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        Payé — Fonds sécurisés
                    </div>
                </div>
            </div>

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Note d'information --}}
            <div class="rounded-2xl border border-zinc-100 bg-zinc-50 px-5 py-4 text-sm text-zinc-500 dark:bg-zinc-900 dark:border-zinc-700 dark:text-zinc-400">
                <p>Les fonds sont sécurisés dans notre système escrow et ne seront libérés au freelance qu'après votre validation de la livraison. En cas de litige, notre équipe interviendra sous 48h.</p>
            </div>
        </div>

        {{-- Pied de page --}}
        <div class="border-t border-zinc-100 bg-zinc-50 px-8 py-4 dark:bg-zinc-900 dark:border-zinc-700">
            <div class="flex items-center justify-between text-[11px] text-zinc-400">
                <span>AfriTask — Numéro de commande : {{ $order->uuid }}</span>
                <span>{{ $payment->created_at->translatedFormat('d M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- ── Prochaines étapes (non imprimées) ── --}}
    <div class="mt-6 rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60 print:hidden">
        <h2 class="text-base font-black text-zinc-950 dark:text-white">Prochaines étapes</h2>
        <div class="mt-4 grid gap-3">
            @foreach ([
                ['Commande en cours', 'Le freelance a été notifié et commencera votre projet.', 'emerald'],
                ['Suivi du projet', 'Suivez l\'avancement depuis votre tableau de bord.', 'teal'],
                ['Livraison & Validation', 'Vous recevrez une notification quand le travail sera livré.', 'blue'],
            ] as $i => [$title, $desc, $color])
                <div class="flex items-start gap-4">
                    <div class="flex size-7 shrink-0 items-center justify-center rounded-full bg-{{ $color }}-100 text-xs font-black text-{{ $color }}-700 dark:bg-{{ $color }}-950/40 dark:text-{{ $color }}-400">
                        {{ $i + 1 }}
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $desc }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
           class="mt-5 flex w-full items-center justify-center rounded-full bg-zinc-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-700 dark:bg-zinc-700 dark:hover:bg-teal-600">
            Voir ma commande
        </a>
    </div>

</div>

{{-- ── CSS Print ── --}}
<style>
@media print {
    body { background: white !important; }
    .print\:hidden { display: none !important; }
    .print\:rounded-none { border-radius: 0 !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:border-0 { border: none !important; }
    .dark\:bg-zinc-800 { background: white !important; }
    .dark\:text-white, .dark\:text-zinc-100 { color: #18181b !important; }
}
</style>
