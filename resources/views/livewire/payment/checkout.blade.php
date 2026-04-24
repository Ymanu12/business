<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Paiement sécurisé</p>
        <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Finaliser la commande</h1>
    </div>

    @if (session('error'))
        <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_300px]">

        <form wire:submit="pay" class="grid gap-6">
            {{-- Récap commande --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <h2 class="text-base font-black text-zinc-950 dark:text-white">Récapitulatif</h2>

                @if ($order->gig)
                    <div class="mt-4 flex items-center gap-4">
                        <img src="{{ $order->gig->thumbnailUrl() }}" alt="{{ $order->gig->title }}"
                             class="h-16 w-24 shrink-0 rounded-xl object-cover">
                        <div>
                            <div class="text-sm font-bold text-zinc-950 dark:text-white">{{ $order->gig->title }}</div>
                            <div class="mt-1 flex items-center gap-2">
                                <img src="{{ $order->freelancer->avatarUrl() }}" class="size-5 rounded-full object-cover" alt="">
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $order->freelancer->name }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-5 grid gap-2 text-sm">
                    @if ($order->package)
                        <div class="flex justify-between text-zinc-600 dark:text-zinc-400">
                            <span>Package {{ $order->package->typeLabel() }}</span>
                            <span>{{ $order->package->formattedPrice() }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t border-zinc-100 pt-3 font-black text-zinc-950 dark:text-white dark:border-zinc-800">
                        <span>Total</span>
                        <span class="text-teal-700 text-lg">{{ $order->formattedPrice() }}</span>
                    </div>
                </div>
            </div>

            {{-- Méthode de paiement --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <h2 class="text-base font-black text-zinc-950 dark:text-white">Mode de paiement</h2>

                <div class="mt-4 grid gap-3">
                    @php
                        $methods = [
                            'wallet'       => ['Wallet AfriTask', "Solde : {$wallet->formattedBalance()}", $wallet->balance >= $order->price],
                            'mobile_money' => ['Mobile Money', 'MTN, Orange, Moov, Flooz', true],
                            'stripe'       => ['Carte bancaire', 'Visa, Mastercard via Stripe', true],
                        ];
                    @endphp
                    @foreach ($methods as $key => [$label, $desc, $available])
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border p-4 transition {{ $paymentMethod === $key ? 'border-zinc-950 bg-zinc-950 text-white dark:border-teal-600 dark:bg-teal-700' : 'border-zinc-200 bg-white hover:border-teal-300 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200' }} {{ !$available ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <input type="radio" wire:model.live="paymentMethod" value="{{ $key }}"
                                   {{ !$available ? 'disabled' : '' }} class="sr-only">
                            <div class="flex-1">
                                <div class="text-sm font-semibold">{{ $label }}</div>
                                <div class="mt-0.5 text-xs {{ $paymentMethod === $key ? 'text-zinc-300' : 'text-zinc-500' }}">
                                    {{ $desc }}
                                    @if ($key === 'mobile_money' || $key === 'stripe')
                                        <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Bientôt</span>
                                    @endif
                                </div>
                            </div>
                            <div class="size-4 shrink-0 rounded-full border-2 {{ $paymentMethod === $key ? 'border-white bg-teal-400' : 'border-zinc-300' }}"></div>
                        </label>
                    @endforeach
                </div>
                @error('paymentMethod') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            {{-- Escrow info --}}
            <div class="rounded-2xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800">
                <strong>Paiement sécurisé par escrow :</strong> votre paiement est bloqué et libéré uniquement après votre validation de la livraison.
            </div>

            <flux:button type="submit" variant="primary" class="w-full py-4 text-base">
                Payer {{ $order->formattedPrice() }}
            </flux:button>
        </form>

        {{-- Sidebar --}}
        <aside>
            <div class="sticky top-24 grid gap-4">
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Délai de livraison</p>
                    <div class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">{{ $order->delivery_days }} jours</div>
                    <p class="mt-1 text-xs text-zinc-400">
                        Livraison prévue le {{ now()->addDays($order->delivery_days)->format('d M Y') }}
                    </p>
                </div>
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Révisions incluses</p>
                    <div class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">{{ $order->revisions_allowed }}</div>
                </div>
            </div>
        </aside>
    </div>
</div>
