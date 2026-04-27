<div class="mx-auto max-w-3xl px-4 py-10 print:px-0 print:py-0">

    {{-- Print / back actions --}}
    <div class="mb-6 flex items-center justify-between print:hidden">
        <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
           class="flex items-center gap-1.5 text-xs text-zinc-400 transition hover:text-teal-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Retour à la commande
        </a>
        <button onclick="window.print()"
                class="flex items-center gap-2 rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 dark:bg-zinc-100 dark:text-zinc-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Imprimer / Sauvegarder en PDF
        </button>
    </div>

    {{-- Certificate document --}}
    <div class="relative overflow-hidden rounded-[2rem] border-4 border-double border-teal-700 bg-white shadow-2xl print:rounded-none print:border-4 print:shadow-none">

        {{-- Decorative corner ornaments --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute left-3 top-3 size-16 rounded-tl-2xl border-l-4 border-t-4 border-teal-200 opacity-60"></div>
            <div class="absolute right-3 top-3 size-16 rounded-tr-2xl border-r-4 border-t-4 border-teal-200 opacity-60"></div>
            <div class="absolute bottom-3 left-3 size-16 rounded-bl-2xl border-b-4 border-l-4 border-teal-200 opacity-60"></div>
            <div class="absolute bottom-3 right-3 size-16 rounded-br-2xl border-b-4 border-r-4 border-teal-200 opacity-60"></div>
        </div>

        <div class="px-10 py-12 text-center">

            {{-- Logo / brand --}}
            <div class="flex items-center justify-center gap-3">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-2xl font-black tracking-tight text-zinc-950">AfriTask</span>
            </div>

            {{-- Title --}}
            <div class="mt-8">
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-teal-600">Attestation de Formation</p>
                <div class="mx-auto mt-2 h-px w-24 bg-gradient-to-r from-transparent via-teal-400 to-transparent"></div>
            </div>

            {{-- Declaration --}}
            <p class="mt-8 text-sm font-medium text-zinc-500">Ceci certifie que</p>

            {{-- Client name --}}
            <h1 class="mt-3 text-4xl font-black text-zinc-950" style="font-family: Georgia, serif;">
                {{ $order->client->name }}
            </h1>

            {{-- Body text --}}
            <p class="mx-auto mt-6 max-w-lg text-sm leading-8 text-zinc-600">
                a suivi et complété avec succès la formation
            </p>
            <h2 class="mt-3 text-xl font-black text-teal-700">
                « {{ $order->gig?->title ?? $order->title }} »
            </h2>
            <p class="mx-auto mt-3 max-w-lg text-sm leading-8 text-zinc-600">
                dispensée par <strong class="text-zinc-900">{{ $order->freelancer->name }}</strong>
                sur la plateforme AfriTask.
            </p>

            {{-- Star rating --}}
            <div class="mt-6 flex items-center justify-center gap-1">
                @for ($s = 1; $s <= 5; $s++)
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="size-6 {{ $s <= $evaluation->rating ? 'text-amber-400' : 'text-zinc-200' }}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>

            @if ($evaluation->comment)
                <blockquote class="mx-auto mt-5 max-w-md text-sm italic leading-7 text-zinc-500">
                    "{{ $evaluation->comment }}"
                </blockquote>
            @endif

            {{-- Divider --}}
            <div class="mx-auto mt-10 h-px w-40 bg-gradient-to-r from-transparent via-zinc-300 to-transparent"></div>

            {{-- Footer info --}}
            <div class="mt-8 grid grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-zinc-400">Date de délivrance</p>
                    <p class="mt-1 text-sm font-bold text-zinc-700">{{ $evaluation->created_at->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-zinc-400">Référence</p>
                    <p class="mt-1 font-mono text-xs font-bold text-teal-700">{{ $evaluation->certificate_ref }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-zinc-400">Formateur</p>
                    <p class="mt-1 text-sm font-bold text-zinc-700">{{ $order->freelancer->name }}</p>
                </div>
            </div>

            {{-- Seal --}}
            <div class="mt-8 flex justify-center">
                <div class="relative flex size-20 items-center justify-center rounded-full border-4 border-teal-400 bg-teal-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                    </svg>
                    <div class="absolute -right-1 -top-1 flex size-6 items-center justify-center rounded-full bg-teal-500 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
            </div>

            <p class="mt-4 text-[11px] text-zinc-400">
                Ce document est généré électroniquement par AfriTask et certifié authentique.
            </p>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    nav, header, footer { display: none !important; }
}
</style>
