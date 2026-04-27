<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('orders.course', $order->uuid) }}" wire:navigate
           class="flex items-center gap-1.5 text-xs text-zinc-400 transition hover:text-teal-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Retour au cours
        </a>
        <h1 class="mt-4 text-2xl font-black text-zinc-950 dark:text-white">{{ $quiz->title }}</h1>
        @if ($quiz->instructions)
            <p class="mt-2 text-sm leading-7 text-zinc-500 dark:text-zinc-400">{{ $quiz->instructions }}</p>
        @endif
        <div class="mt-3 flex items-center gap-3 text-xs text-zinc-400">
            <span>{{ $questions->count() }} question{{ $questions->count() !== 1 ? 's' : '' }}</span>
            <span>·</span>
            <span>Score minimum : <strong class="text-zinc-600 dark:text-zinc-300">{{ $quiz->passing_score }}%</strong></span>
        </div>
    </div>

    {{-- Questions --}}
    <div class="grid gap-6">
        @foreach ($questions as $i => $question)
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60">
                <div class="flex items-start gap-4">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-teal-100 text-sm font-black text-teal-700 dark:bg-teal-900/30">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $question->question }}</p>

                        @if ($question->type === 'multiple_choice')
                            <div class="mt-4 grid gap-2">
                                @foreach ($question->choices as $choice)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border px-4 py-3 transition
                                                  {{ isset($answers[$question->id]) && (int)$answers[$question->id] === $choice->id
                                                       ? 'border-teal-400 bg-teal-50 dark:bg-teal-900/20'
                                                       : 'border-zinc-200 hover:border-teal-200 dark:border-zinc-700 dark:hover:border-zinc-500' }}">
                                        <input type="radio"
                                               wire:model="answers.{{ $question->id }}"
                                               value="{{ $choice->id }}"
                                               class="size-4 accent-teal-600" />
                                        <span class="text-sm text-zinc-800 dark:text-zinc-200">{{ $choice->label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <textarea wire:model="answers.{{ $question->id }}"
                                      rows="3"
                                      placeholder="Votre réponse…"
                                      class="mt-3 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white"></textarea>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Submit --}}
    <div class="mt-8">
        <button wire:click="submit"
                wire:loading.attr="disabled"
                wire:target="submit"
                wire:confirm="Soumettre le quiz ? Vous ne pourrez pas modifier vos réponses."
                class="flex w-full items-center justify-center gap-2 rounded-full bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-teal-600 dark:hover:text-white">
            <span wire:loading.remove wire:target="submit">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline size-4 mr-2 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Soumettre le quiz
            </span>
            <span wire:loading wire:target="submit">Soumission en cours…</span>
        </button>
    </div>
</div>
