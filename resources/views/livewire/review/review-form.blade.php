<div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Avis client</p>
        <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Évaluez ce service</h1>
    </div>

    @if ($order->gig)
        <div class="mt-6 flex items-center gap-4 rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-4 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
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

    <form wire:submit="submit"
          class="mt-6 rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">

        {{-- Note en étoiles --}}
        <div>
            <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">Note globale</p>
            <div class="mt-3 flex gap-2" x-data="{ rating: @entangle('rating') }">
                @for ($s = 1; $s <= 5; $s++)
                    <button type="button"
                            wire:click="$set('rating', {{ $s }})"
                            class="text-3xl transition hover:scale-110 {{ $rating >= $s ? 'text-amber-400' : 'text-zinc-200' }}">
                        ★
                    </button>
                @endfor
                <span class="ml-2 self-center text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    @php
                        $labels = ['', 'Mauvais', 'Décevant', 'Correct', 'Bien', 'Excellent'];
                    @endphp
                    {{ $labels[$rating] ?? '' }}
                </span>
            </div>
            @error('rating') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        {{-- Commentaire --}}
        <div class="mt-6">
            <flux:field>
                <flux:label>Votre avis (20 caractères min.)</flux:label>
                <flux:textarea wire:model="comment" rows="5"
                               placeholder="Décrivez votre expérience : qualité du travail, communication, respect des délais…"></flux:textarea>
                <flux:error name="comment" />
            </flux:field>
        </div>

        {{-- Recommande ? --}}
        <div class="mt-5 flex items-center gap-3">
            <input type="checkbox" wire:model="recommend" id="recommend"
                   class="size-4 rounded border-zinc-300 text-teal-600 focus:ring-teal-500">
            <label for="recommend" class="text-sm font-medium text-zinc-700 cursor-pointer dark:text-zinc-300">
                Je recommande ce freelance
            </label>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
               class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                Annuler
            </a>
            <flux:button type="submit" variant="primary" class="sm:min-w-44">
                Publier l'avis
            </flux:button>
        </div>
    </form>
</div>
