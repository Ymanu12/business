<div class="grid gap-8">

    @if ($successMessage)
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-700/40 dark:text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $successMessage }}
        </div>
    @endif

    {{-- Quiz meta --}}
    <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60">
        <h3 class="text-base font-black text-zinc-950 dark:text-white">Paramètres du quiz</h3>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Titre <span class="text-rose-500">*</span></label>
                <input wire:model="quizTitle" type="text" placeholder="Ex : Quiz de fin de formation"
                       class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white" />
                @error('quizTitle') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Instructions</label>
                <textarea wire:model="instructions" rows="2" placeholder="Instructions pour le client avant de commencer..."
                          class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Score minimum pour réussir (%)</label>
                <input wire:model="passingScore" type="number" min="1" max="100"
                       class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white" />
                @error('passingScore') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>
        <button wire:click="saveMeta"
                wire:loading.attr="disabled" wire:target="saveMeta"
                class="mt-5 rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-teal-600 dark:hover:text-white">
            <span wire:loading.remove wire:target="saveMeta">Enregistrer</span>
            <span wire:loading wire:target="saveMeta">Enregistrement…</span>
        </button>
    </div>

    {{-- Questions list --}}
    @if ($questions->isNotEmpty())
        <div class="grid gap-3">
            @foreach ($questions as $i => $question)
                <div class="rounded-2xl border border-zinc-200/80 bg-white/90 p-4 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="flex size-6 items-center justify-center rounded-full bg-teal-100 text-[11px] font-black text-teal-700 dark:bg-teal-900/30">{{ $i + 1 }}</span>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $question->type === 'multiple_choice' ? 'bg-sky-100 text-sky-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $question->type === 'multiple_choice' ? 'QCM' : 'Texte libre' }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $question->question }}</p>
                            @if ($question->type === 'multiple_choice')
                                <div class="mt-2 grid gap-1">
                                    @foreach ($question->choices as $choice)
                                        <div class="flex items-center gap-2 text-xs {{ $choice->is_correct ? 'text-emerald-600 font-semibold' : 'text-zinc-400' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 shrink-0 {{ $choice->is_correct ? 'text-emerald-500' : 'text-zinc-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $choice->is_correct ? '3' : '2' }}">
                                                @if ($choice->is_correct)
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                @else
                                                    <circle cx="12" cy="12" r="9" stroke-width="1.5"/>
                                                @endif
                                            </svg>
                                            {{ $choice->label }}
                                        </div>
                                    @endforeach
                                </div>
                            @elseif ($question->correct_answer)
                                <p class="mt-1.5 text-xs text-zinc-400">Réponse attendue : <em>{{ $question->correct_answer }}</em></p>
                            @endif
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <button wire:click="editQuestion({{ $question->id }})"
                                    class="rounded-xl border border-zinc-200 px-3 py-1.5 text-xs font-semibold text-zinc-600 transition hover:border-teal-300 hover:text-teal-700 dark:border-zinc-600 dark:text-zinc-400">
                                Modifier
                            </button>
                            <button wire:click="deleteQuestion({{ $question->id }})"
                                    wire:confirm="Supprimer cette question ?"
                                    class="rounded-xl border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-500 transition hover:bg-rose-50 dark:border-rose-800">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center dark:bg-zinc-900/40 dark:border-zinc-700">
            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Aucune question pour l'instant</p>
        </div>
    @endif

    {{-- Question form --}}
    <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60">
        <h3 class="text-base font-black text-zinc-950 dark:text-white">{{ $editingId ? 'Modifier la question' : 'Nouvelle question' }}</h3>

        <div class="mt-5 grid gap-5">

            {{-- Question text --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Question <span class="text-rose-500">*</span></label>
                <textarea wire:model="questionText" rows="2" placeholder="Ex : Quel est le rôle de…"
                          class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white"></textarea>
                @error('questionText') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Type --}}
            <div class="flex items-center gap-3">
                <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Type :</label>
                <label class="flex cursor-pointer items-center gap-2">
                    <input wire:model.live="questionType" type="radio" value="multiple_choice" class="accent-teal-600" />
                    <span class="text-sm text-zinc-700 dark:text-zinc-300">QCM</span>
                </label>
                <label class="flex cursor-pointer items-center gap-2">
                    <input wire:model.live="questionType" type="radio" value="text" class="accent-teal-600" />
                    <span class="text-sm text-zinc-700 dark:text-zinc-300">Texte libre</span>
                </label>
            </div>

            {{-- Multiple choice options --}}
            @if ($questionType === 'multiple_choice')
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Choix de réponses</label>
                    @error('choices') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    <div class="mt-2 grid gap-2">
                        @foreach ($choices as $i => $choice)
                            <div class="flex items-center gap-3">
                                <label class="flex cursor-pointer items-center gap-1.5" title="Marquer comme correct">
                                    <input wire:model="choices.{{ $i }}.is_correct" type="checkbox"
                                           class="size-4 rounded accent-emerald-500" />
                                    <span class="text-xs text-zinc-400">Correct</span>
                                </label>
                                <input wire:model="choices.{{ $i }}.label" type="text"
                                       placeholder="Choix {{ $i + 1 }}"
                                       class="flex-1 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white" />
                                @if (count($choices) > 2)
                                    <button wire:click="removeChoice({{ $i }})"
                                            class="rounded-full p-1.5 text-zinc-400 hover:text-rose-500 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                @endif
                            </div>
                            @error("choices.{$i}.label") <p class="ml-20 text-xs text-rose-500">{{ $message }}</p> @enderror
                        @endforeach
                    </div>
                    @if (count($choices) < 6)
                        <button wire:click="addChoice"
                                class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-teal-600 transition hover:text-teal-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Ajouter un choix
                        </button>
                    @endif
                </div>
            @else
                {{-- Text type: correct answer hint --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Réponse attendue (indice pour la correction)</label>
                    <input wire:model="correctAnswer" type="text" placeholder="Facultatif — visible uniquement pour vous"
                           class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white" />
                </div>
            @endif
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button wire:click="saveQuestion"
                    wire:loading.attr="disabled" wire:target="saveQuestion"
                    class="flex items-center gap-2 rounded-full bg-zinc-950 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-teal-600 dark:hover:text-white">
                <span wire:loading.remove wire:target="saveQuestion">{{ $editingId ? 'Mettre à jour' : 'Ajouter la question' }}</span>
                <span wire:loading wire:target="saveQuestion">Enregistrement…</span>
            </button>
            @if ($editingId)
                <button wire:click="cancelEdit"
                        class="rounded-full border border-zinc-200 px-5 py-2.5 text-sm font-semibold text-zinc-600 transition hover:border-zinc-400 dark:border-zinc-600 dark:text-zinc-400">
                    Annuler
                </button>
            @endif
        </div>
    </div>
</div>
