<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6">

    {{-- Back link --}}
    <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
       class="flex items-center gap-1.5 text-xs text-zinc-400 transition hover:text-teal-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Retour à la commande
    </a>

    <div class="mt-6 rounded-[2rem] border border-zinc-200/80 bg-white/90 p-8 shadow-xl shadow-zinc-900/5 backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <div class="flex size-12 items-center justify-center rounded-2xl bg-teal-100 text-teal-600 dark:bg-teal-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-zinc-950 dark:text-white">Évaluer le client</h1>
                <p class="mt-0.5 text-sm text-zinc-400">{{ $order->client->name }}</p>
            </div>
        </div>

        {{-- Client card --}}
        <div class="mt-6 flex items-center gap-4 rounded-2xl border border-zinc-100 bg-zinc-50 p-4 dark:bg-zinc-900/60 dark:border-zinc-700">
            <img src="{{ $order->client->avatarUrl() }}" alt="{{ $order->client->name }}"
                 class="size-12 rounded-full object-cover">
            <div>
                <div class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $order->client->name }}</div>
                <div class="text-xs text-zinc-400">Commande : {{ Str::limit($order->title ?? $order->gig?->title, 45) }}</div>
            </div>
        </div>

        <div class="mt-8 grid gap-6">

            {{-- Star rating --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Note globale <span class="text-rose-500">*</span></label>
                <div x-data="{ rating: $wire.entangle('rating') }" class="mt-3 flex items-center gap-2">
                    @for ($s = 1; $s <= 5; $s++)
                        <button type="button"
                                @click="rating = {{ $s }}"
                                class="transition-transform hover:scale-110 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 :class="rating >= {{ $s }} ? 'text-amber-400' : 'text-zinc-200 dark:text-zinc-700'"
                                 class="size-9 transition"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endfor
                    <span x-text="['', 'Insuffisant', 'Passable', 'Bien', 'Très bien', 'Excellent'][rating]"
                          class="ml-2 text-sm font-semibold text-zinc-600 dark:text-zinc-400"></span>
                </div>
                @error('rating') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Comment --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Commentaire (facultatif)</label>
                <textarea wire:model="comment" rows="4"
                          placeholder="Décrivez l'engagement du client, sa communication, sa motivation..."
                          class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white"></textarea>
                @error('comment') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Certificate toggle --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:bg-amber-900/20 dark:border-amber-800/40">
                <label class="flex cursor-pointer items-start gap-4">
                    <div x-data="{ on: $wire.entangle('withCert') }" @click="on = !on"
                         :class="on ? 'bg-amber-400' : 'bg-zinc-200 dark:bg-zinc-700'"
                         class="relative mt-0.5 h-6 w-11 shrink-0 rounded-full transition">
                        <span :class="on ? 'translate-x-5' : 'translate-x-1'"
                              class="absolute top-1 size-4 rounded-full bg-white shadow transition-transform"></span>
                    </div>
                    <div>
                        <p class="font-semibold text-amber-800 dark:text-amber-400">Délivrer une attestation de formation</p>
                        <p class="mt-1 text-xs text-amber-700/70 dark:text-amber-500/70">
                            Une attestation officielle AfriTask sera générée avec un numéro unique et accessible au client.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-8 flex items-center gap-4">
            <button wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    class="flex-1 rounded-full bg-zinc-950 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-teal-600 dark:hover:text-white">
                <span wire:loading.remove wire:target="submit">Valider l'évaluation</span>
                <span wire:loading wire:target="submit">Enregistrement…</span>
            </button>
            <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
               class="rounded-full border border-zinc-200 px-5 py-3.5 text-sm font-semibold text-zinc-600 transition hover:border-zinc-400 dark:border-zinc-600 dark:text-zinc-400">
                Annuler
            </a>
        </div>
    </div>
</div>
