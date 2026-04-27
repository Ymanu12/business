<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">

    <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
       class="flex items-center gap-1.5 text-xs text-zinc-400 transition hover:text-teal-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Retour à la commande
    </a>

    @if (!$attempt)
        <div class="mt-8 rounded-2xl border border-zinc-200 bg-zinc-50 p-8 text-center dark:bg-zinc-900/40 dark:border-zinc-700">
            @if (auth()->id() === $order->client_id)
                <p class="text-sm font-medium text-zinc-500">Vous n'avez pas encore soumis ce quiz.</p>
                <a href="{{ route('orders.quiz', $order->uuid) }}" wire:navigate
                   class="mt-4 inline-flex items-center gap-2 rounded-full bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                    Passer le quiz
                </a>
            @else
                <p class="text-sm font-medium text-zinc-500">{{ $order->client->name }} n'a pas encore soumis le quiz.</p>
            @endif
        </div>
    @else
        {{-- Score banner --}}
        <div class="mt-6 rounded-[1.75rem] p-8 text-center shadow-xl {{ $attempt->passed ? 'bg-gradient-to-br from-teal-500 to-emerald-600' : 'bg-gradient-to-br from-rose-500 to-pink-600' }}">
            <div class="text-6xl font-black text-white">{{ $attempt->score ?? '—' }}%</div>
            <p class="mt-2 text-lg font-bold text-white/90">
                {{ $attempt->passed ? 'Félicitations, quiz réussi !' : 'Quiz non réussi' }}
            </p>
            <p class="mt-1 text-sm text-white/70">
                Score minimum requis : {{ $quiz->passing_score }}%
                · Soumis le {{ $attempt->submitted_at->format('d M Y à H:i') }}
            </p>
            @if (!$attempt->passed && auth()->id() === $order->client_id)
                <a href="{{ route('orders.quiz', $order->uuid) }}" wire:navigate
                   class="mt-5 inline-flex items-center gap-2 rounded-full bg-white/20 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/30">
                    Réessayer
                </a>
            @endif
        </div>

        {{-- Per-question review --}}
        <div class="mt-8 grid gap-5">
            @foreach ($quiz->questions as $i => $question)
                @php
                    $answer = $attempt->answers->firstWhere('question_id', $question->id);
                    $isCorrect = $answer?->is_correct;
                @endphp
                <div class="rounded-[1.75rem] border {{ $isCorrect === true ? 'border-emerald-200 bg-emerald-50/60 dark:bg-emerald-900/10 dark:border-emerald-800/40' : ($isCorrect === false ? 'border-rose-200 bg-rose-50/60 dark:bg-rose-900/10 dark:border-rose-800/40' : 'border-zinc-200 bg-white/90 dark:bg-zinc-800/90 dark:border-zinc-700/60') }} p-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-full
                                    {{ $isCorrect === true ? 'bg-emerald-500' : ($isCorrect === false ? 'bg-rose-400' : 'bg-zinc-300 dark:bg-zinc-600') }}">
                            @if ($isCorrect === true)
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @elseif ($isCorrect === false)
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                                <span class="text-xs font-bold text-white">{{ $i + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $question->question }}</p>

                            @if ($question->type === 'multiple_choice')
                                <div class="mt-3 grid gap-1.5">
                                    @foreach ($question->choices as $choice)
                                        @php
                                            $wasSelected = $answer?->choice_id === $choice->id;
                                        @endphp
                                        <div class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm
                                                    {{ $choice->is_correct ? 'bg-emerald-100 text-emerald-800 font-semibold dark:bg-emerald-900/30 dark:text-emerald-300' : '' }}
                                                    {{ $wasSelected && !$choice->is_correct ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300' : '' }}
                                                    {{ !$choice->is_correct && !$wasSelected ? 'text-zinc-500' : '' }}">
                                            @if ($choice->is_correct)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            @elseif ($wasSelected)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            @else
                                                <div class="size-4 shrink-0"></div>
                                            @endif
                                            {{ $choice->label }}
                                            @if ($wasSelected && !$choice->is_correct)
                                                <span class="ml-auto text-[10px] font-bold text-rose-500">Votre réponse</span>
                                            @elseif ($wasSelected && $choice->is_correct)
                                                <span class="ml-auto text-[10px] font-bold text-emerald-600">Votre réponse ✓</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{-- Text answer --}}
                                <div class="mt-3 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-600 dark:bg-zinc-900/60 dark:border-zinc-700 dark:text-zinc-400">
                                    {{ $answer?->text_answer ?? 'Pas de réponse' }}
                                </div>
                                @if ($question->correct_answer && auth()->id() === $order->freelancer_id)
                                    <p class="mt-2 text-xs text-zinc-400">Réponse attendue : <em>{{ $question->correct_answer }}</em></p>
                                @endif
                                @if ($isCorrect === null)
                                    <p class="mt-2 text-xs text-amber-600 dark:text-amber-400">⏳ En attente de correction manuelle</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
