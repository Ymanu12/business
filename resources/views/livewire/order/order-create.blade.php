<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Commande</p>
        <h1 class="mt-1 text-2xl font-black text-zinc-950 line-clamp-1 dark:text-white">{{ $gig->title }}</h1>
    </div>

    <div class="mt-8 flex flex-col gap-8 lg:flex-row lg:items-start">
        <form wire:submit="placeOrder" class="min-w-0 flex-1 grid gap-6">

            {{-- Sélection du package --}}
            @if ($gig->packages->isNotEmpty())
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Choisissez votre package</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        @foreach ($gig->packages as $pkg)
                            <label class="cursor-pointer rounded-2xl border p-4 transition {{ (int)$selectedPackageId === $pkg->id ? 'border-zinc-950 bg-zinc-950 text-white' : 'border-zinc-200 bg-white hover:border-teal-300' }} dark:bg-zinc-800">
                                <input type="radio" wire:model.live="selectedPackageId" value="{{ $pkg->id }}" class="sr-only">
                                <div class="text-xs font-semibold uppercase tracking-[0.2em] {{ (int)$selectedPackageId === $pkg->id ? 'text-teal-200' : 'text-zinc-400' }}">
                                    {{ $pkg->typeLabel() }}
                                </div>
                                <div class="mt-2 text-lg font-black">{{ $pkg->formattedPrice() }}</div>
                                <div class="mt-1 text-xs {{ (int)$selectedPackageId === $pkg->id ? 'text-zinc-300' : 'text-zinc-500' }}">
                                    {{ $pkg->delivery_days }}j · {{ $pkg->revision_count }} révision{{ $pkg->revision_count !== 1 ? 's' : '' }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Extras --}}
            @if ($gig->extras->isNotEmpty())
                <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <h2 class="text-base font-black text-zinc-950 dark:text-white">Options supplémentaires</h2>
                    <div class="mt-4 grid gap-3">
                        @foreach ($gig->extras as $extra)
                            <label class="flex cursor-pointer items-center justify-between gap-4 rounded-2xl border border-zinc-200 p-4 transition hover:border-teal-300 dark:border-zinc-700">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" wire:model="selectedExtras" value="{{ $extra->id }}"
                                           class="size-4 rounded border-zinc-300 text-teal-600">
                                    <div>
                                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $extra->title }}</div>
                                        @if ($extra->description)
                                            <div class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $extra->description }}</div>
                                        @endif
                                    </div>
                                </div>
                                <span class="shrink-0 text-sm font-bold text-teal-700">
                                    +{{ number_format($extra->price, 0, ',', ' ') }} {{ $gig->currency }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Brief client --}}
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <h2 class="text-base font-black text-zinc-950 dark:text-white">Votre brief</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Expliquez précisément ce que vous attendez.</p>
                <div class="mt-4">
                    <flux:field>
                        <flux:textarea wire:model="requirements" rows="6"
                                       placeholder="Décrivez votre projet : couleurs, ton, exemples de références, contraintes techniques…"></flux:textarea>
                        <flux:error name="requirements" />
                    </flux:field>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('gigs.show', $gig->slug) }}" wire:navigate
                   class="inline-flex items-center rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                    Retour
                </a>
                <flux:button type="submit" variant="primary" class="min-w-44">
                    Continuer vers le paiement
                </flux:button>
            </div>
        </form>

        {{-- Récap --}}
        <aside class="w-full lg:w-72 lg:shrink-0">
            <div class="sticky top-24 rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-xl shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <div class="flex items-center gap-3">
                    <img src="{{ $gig->freelancer->avatarUrl() }}" alt="{{ $gig->freelancer->name }}"
                         class="size-10 rounded-full object-cover">
                    <div>
                        <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $gig->freelancer->name }}</div>
                        <div class="text-xs text-zinc-400">Freelance</div>
                    </div>
                </div>

                @if ($selectedPackage)
                    <div class="mt-5 grid gap-2 rounded-2xl bg-stone-50 p-4 dark:bg-zinc-900">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Package {{ $selectedPackage->typeLabel() }}</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $selectedPackage->formattedPrice() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                            <span>Délai</span>
                            <span>{{ $selectedPackage->delivery_days }} jour{{ $selectedPackage->delivery_days > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-4 dark:border-zinc-800">
                    <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Total estimé</span>
                    <span class="text-lg font-black text-teal-700">
                        @php
                            $basePrice = $selectedPackage ? $selectedPackage->price : $gig->starting_price;
                            $extrasTotal = collect($selectedExtras)->sum(fn($id) => $gig->extras->firstWhere('id', $id)?->price ?? 0);
                        @endphp
                        {{ number_format($basePrice + $extrasTotal, 0, ',', ' ') }} {{ $gig->currency }}
                    </span>
                </div>
            </div>
        </aside>
    </div>
</div>
