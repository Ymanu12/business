<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">

    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Paiement sécurisé</p>
        <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Finaliser la commande</h1>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_300px]">

        <form wire:submit="pay" class="grid gap-6">

            {{-- ── Récapitulatif commande ── --}}
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
                            @if ($order->package)
                                <span class="mt-1 inline-block rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300">
                                    Package {{ $order->package->typeLabel() }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-5 space-y-2 text-sm">
                    @if ($order->package)
                        <div class="flex justify-between text-zinc-600 dark:text-zinc-400">
                            <span>Package {{ $order->package->typeLabel() }}</span>
                            <span>{{ $order->package->formattedPrice() }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-500 dark:text-zinc-400 text-xs">
                        <span>Commission plateforme (10%)</span>
                        <span>{{ number_format($order->price * 0.10, 0, ',', ' ') }} {{ $order->currency }}</span>
                    </div>
                    <div class="flex justify-between border-t border-zinc-100 pt-3 font-black text-zinc-950 dark:text-white dark:border-zinc-800">
                        <span>Total à payer</span>
                        <span class="text-teal-700 text-lg">{{ $order->formattedPrice() }}</span>
                    </div>
                </div>
            </div>

            {{-- ── Choix de la méthode ── --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <h2 class="text-base font-black text-zinc-950 dark:text-white">Mode de paiement</h2>

                <div class="mt-4 grid gap-3">

                    {{-- Wallet --}}
                    <label class="flex cursor-pointer items-center gap-4 rounded-2xl border p-4 transition
                        {{ $paymentMethod === 'wallet' ? 'border-teal-600 bg-teal-600 text-white' : 'border-zinc-200 bg-white hover:border-teal-300 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="wallet" class="sr-only">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 {{ $paymentMethod === 'wallet' ? 'text-white' : 'text-teal-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold">Wallet AfriTask</div>
                            <div class="mt-0.5 text-xs {{ $paymentMethod === 'wallet' ? 'text-teal-100' : 'text-zinc-500' }}">
                                Solde : <strong>{{ $wallet->formattedBalance() }}</strong>
                                @if ($wallet->balance < $order->price)
                                    <span class="ml-1 text-rose-400 font-semibold">— Insuffisant</span>
                                @endif
                            </div>
                        </div>
                        <div class="size-4 shrink-0 rounded-full border-2 {{ $paymentMethod === 'wallet' ? 'border-white bg-white' : 'border-zinc-300' }}"></div>
                    </label>

                    {{-- Mobile Money --}}
                    <label class="flex cursor-pointer items-center gap-4 rounded-2xl border p-4 transition
                        {{ $paymentMethod === 'mobile_money' ? 'border-amber-500 bg-amber-500 text-white' : 'border-zinc-200 bg-white hover:border-amber-300 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="mobile_money" class="sr-only">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 {{ $paymentMethod === 'mobile_money' ? 'text-white' : 'text-amber-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold">Mobile Money</div>
                            <div class="mt-0.5 text-xs {{ $paymentMethod === 'mobile_money' ? 'text-amber-100' : 'text-zinc-500' }}">
                                MTN, Orange, Moov, Flooz, T-Money
                            </div>
                        </div>
                        <div class="size-4 shrink-0 rounded-full border-2 {{ $paymentMethod === 'mobile_money' ? 'border-white bg-white' : 'border-zinc-300' }}"></div>
                    </label>

                    {{-- Carte bancaire --}}
                    <label class="flex cursor-pointer items-center gap-4 rounded-2xl border p-4 transition
                        {{ $paymentMethod === 'stripe' ? 'border-blue-600 bg-blue-600 text-white' : 'border-zinc-200 bg-white hover:border-blue-300 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="stripe" class="sr-only">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 {{ $paymentMethod === 'stripe' ? 'text-white' : 'text-blue-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold">Carte bancaire</div>
                            <div class="mt-0.5 text-xs {{ $paymentMethod === 'stripe' ? 'text-blue-100' : 'text-zinc-500' }}">
                                Visa, Mastercard, American Express
                            </div>
                        </div>
                        <div class="size-4 shrink-0 rounded-full border-2 {{ $paymentMethod === 'stripe' ? 'border-white bg-white' : 'border-zinc-300' }}"></div>
                    </label>
                </div>

                @error('paymentMethod')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Formulaire Mobile Money ── --}}
            @if ($paymentMethod === 'mobile_money')
                <div class="rounded-[1.75rem] border border-amber-200/80 bg-amber-50/50 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-amber-800/40">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Paiement Mobile Money</h2>
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Entrez votre numéro et validez avec votre code PIN.</p>

                    <div class="mt-5 grid gap-4">

                        {{-- Réseau --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-2">Réseau</label>
                            <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                                @foreach ([
                                    'mtn_momo'     => ['MTN', '#FFC107'],
                                    'orange_money' => ['Orange', '#FF6600'],
                                    'flooz'        => ['Flooz', '#00A859'],
                                    'tmoney'       => ['T-Money', '#E30613'],
                                    'moov_money'   => ['Moov', '#0033A0'],
                                ] as $network => [$name, $color])
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="momoNetwork" value="{{ $network }}" class="sr-only peer">
                                        <div class="flex flex-col items-center justify-center rounded-xl border-2 p-2 text-center transition
                                            peer-checked:border-current peer-checked:shadow-md
                                            border-zinc-200 hover:border-zinc-300 dark:border-zinc-700">
                                            <span class="text-xs font-bold" style="color: {{ $color }}">{{ $name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">
                                Numéro de téléphone
                            </label>
                            <div class="flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3 py-2.5 dark:bg-zinc-900 dark:border-zinc-700
                                @error('momoPhone') border-rose-400 @enderror">
                                <span class="text-sm font-semibold text-zinc-400">+</span>
                                <input type="tel" wire:model="momoPhone"
                                       placeholder="225 07 00 00 00"
                                       class="flex-1 bg-transparent text-sm text-zinc-900 outline-none placeholder:text-zinc-400 dark:text-white">
                            </div>
                            @error('momoPhone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- PIN --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">
                                Code PIN Mobile Money (4 chiffres)
                            </label>
                            <input type="password" wire:model="momoPin"
                                   maxlength="4"
                                   placeholder="• • • •"
                                   class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm text-center tracking-[0.5em] text-zinc-900 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white
                                       @error('momoPin') border-rose-400 @enderror">
                            @error('momoPin') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-xs text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
                            Vous recevrez une notification de confirmation sur votre téléphone.
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Formulaire Carte bancaire ── --}}
            @if ($paymentMethod === 'stripe')
                <div class="rounded-[1.75rem] border border-blue-200/80 bg-blue-50/30 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-blue-800/40">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Paiement par carte</h2>
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Paiement 100% sécurisé — SSL 256 bits.</p>

                    {{-- Visuel carte --}}
                    <div class="mt-5 h-44 w-full max-w-xs rounded-2xl bg-gradient-to-br from-zinc-800 to-zinc-950 p-5 shadow-2xl dark:from-zinc-700 dark:to-zinc-900">
                        <div class="flex items-center justify-between">
                            <svg class="size-8 text-white/40" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                            </svg>
                            <span class="text-xs font-bold text-white/40 uppercase tracking-widest">Sécurisé</span>
                        </div>
                        <div class="mt-4 font-mono text-base tracking-[0.2em] text-white/80">
                            {{ filled($cardNumber) ? chunk_split(preg_replace('/\D/', '', substr($cardNumber, 0, 16)), 4, ' ') : '•••• •••• •••• ••••' }}
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <div class="text-[10px] uppercase tracking-widest text-white/40">Titulaire</div>
                                <div class="mt-0.5 text-xs font-semibold uppercase tracking-widest text-white/80">
                                    {{ filled($cardHolder) ? Str::upper($cardHolder) : 'VOTRE NOM' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase tracking-widest text-white/40">Expire</div>
                                <div class="mt-0.5 text-xs font-semibold tracking-widest text-white/80">
                                    {{ filled($cardExpiry) ? $cardExpiry : 'MM/AA' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4">
                        {{-- Numéro de carte --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Numéro de carte</label>
                            <input type="text" wire:model.live="cardNumber"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19"
                                   class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 font-mono text-sm tracking-widest text-zinc-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white
                                       @error('cardNumber') border-rose-400 @enderror">
                            @error('cardNumber') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Expiration --}}
                            <div>
                                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Date d'expiration</label>
                                <input type="text" wire:model.live="cardExpiry"
                                       placeholder="MM/AA"
                                       maxlength="5"
                                       class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 font-mono text-sm text-zinc-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white
                                           @error('cardExpiry') border-rose-400 @enderror">
                                @error('cardExpiry') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            {{-- CVV --}}
                            <div>
                                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">CVV</label>
                                <input type="password" wire:model="cardCvv"
                                       placeholder="•••"
                                       maxlength="4"
                                       class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 font-mono text-sm text-zinc-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white
                                           @error('cardCvv') border-rose-400 @enderror">
                                @error('cardCvv') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Titulaire --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Nom du titulaire</label>
                            <input type="text" wire:model.live="cardHolder"
                                   placeholder="Prénom NOM"
                                   class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2.5 text-sm uppercase tracking-widest text-zinc-900 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white
                                       @error('cardHolder') border-rose-400 @enderror">
                            @error('cardHolder') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-xs text-blue-800 dark:border-blue-800/40 dark:bg-blue-950/30 dark:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            Paiement chiffré SSL 256-bit. Vos données sont protégées.
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Info escrow ── --}}
            <div class="flex items-start gap-3 rounded-2xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800 dark:border-teal-800/40 dark:bg-teal-950/30 dark:text-teal-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
                <span><strong>Paiement sécurisé par escrow :</strong> votre paiement est bloqué et ne sera libéré au freelance qu'après votre validation de la livraison.</span>
            </div>

            {{-- ── Bouton paiement ── --}}
            <flux:button type="submit" variant="primary"
                         class="w-full py-4 text-base"
                         wire:loading.attr="disabled"
                         wire:target="pay">
                <span wire:loading.remove wire:target="pay">
                    @if ($paymentMethod === 'wallet')
                        Payer {{ $order->formattedPrice() }} depuis mon wallet
                    @elseif ($paymentMethod === 'mobile_money')
                        Valider le paiement Mobile Money
                    @else
                        Payer {{ $order->formattedPrice() }} par carte
                    @endif
                </span>
                <span wire:loading wire:target="pay" class="flex items-center justify-center gap-2">
                    <svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Traitement du paiement en cours...
                </span>
            </flux:button>

        </form>

        {{-- ── Sidebar ── --}}
        <aside>
            <div class="sticky top-24 grid gap-4">
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Livraison prévue</p>
                    <div class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">{{ $order->delivery_days }} jours</div>
                    <p class="mt-1 text-xs text-zinc-400">
                        le {{ now()->addDays($order->delivery_days)->translatedFormat('d M Y') }}
                    </p>
                </div>

                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Révisions incluses</p>
                    <div class="mt-2 text-2xl font-black text-zinc-950 dark:text-white">{{ $order->revisions_allowed }}</div>
                </div>

                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-zinc-400">Garantie remboursement</p>
                    <div class="mt-3 flex items-center gap-2 text-sm font-semibold text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        Remboursé si non livré
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
