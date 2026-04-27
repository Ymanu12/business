<div class="grid gap-8">

    {{-- Success message --}}
    @if ($successMessage)
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-700/40 dark:text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $successMessage }}
        </div>
    @endif

    {{-- Lesson list --}}
    @if ($lessons->isNotEmpty())
        <div class="grid gap-3">
            @foreach ($lessons as $i => $lesson)
                <div class="flex items-center gap-3 rounded-2xl border border-zinc-200/80 bg-white/90 p-4 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    {{-- Order controls --}}
                    <div class="flex flex-col gap-1">
                        <button wire:click="moveUp({{ $lesson->id }})" class="rounded-lg p-1 text-zinc-400 hover:text-zinc-700 disabled:opacity-30" {{ $i === 0 ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        <button wire:click="moveDown({{ $lesson->id }})" class="rounded-lg p-1 text-zinc-400 hover:text-zinc-700 disabled:opacity-30" {{ $i === $lessons->count() - 1 ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    {{-- Number --}}
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-black text-zinc-500 dark:bg-zinc-700 dark:text-zinc-300">
                        {{ $i + 1 }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $lesson->title }}</span>
                            @if ($lesson->is_preview)
                                <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-700">Aperçu libre</span>
                            @endif
                        </div>
                        <div class="mt-0.5 flex items-center gap-3 text-xs text-zinc-400">
                            @if ($lesson->video_path)
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Vidéo
                                </span>
                            @endif
                            @if ($lesson->file_path)
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    Fichier joint
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex shrink-0 items-center gap-2">
                        <button wire:click="edit({{ $lesson->id }})"
                                class="rounded-xl border border-zinc-200 px-3 py-1.5 text-xs font-semibold text-zinc-600 transition hover:border-teal-300 hover:text-teal-700 dark:border-zinc-600 dark:text-zinc-400">
                            Modifier
                        </button>
                        <button wire:click="delete({{ $lesson->id }})"
                                wire:confirm="Supprimer cette leçon ?"
                                class="rounded-xl border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-500 transition hover:bg-rose-50 dark:border-rose-800 dark:text-rose-400">
                            Supprimer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center dark:bg-zinc-900/40 dark:border-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto size-10 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="mt-3 text-sm font-medium text-zinc-500 dark:text-zinc-400">Aucune leçon pour l'instant</p>
            <p class="mt-1 text-xs text-zinc-400">Créez votre première leçon ci-dessous.</p>
        </div>
    @endif

    {{-- Form --}}
    <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700/60">
        <h3 class="text-base font-black text-zinc-950 dark:text-white">
            {{ $editingId ? 'Modifier la leçon' : 'Nouvelle leçon' }}
        </h3>

        <div class="mt-5 grid gap-5">

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Titre <span class="text-rose-500">*</span></label>
                <input wire:model="title" type="text" placeholder="Ex : Introduction au module 1"
                       class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white" />
                @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Description</label>
                <textarea wire:model="description" rows="3" placeholder="Résumé du contenu de cette leçon..."
                          class="mt-1.5 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-teal-400 focus:ring-2 focus:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700 dark:text-white"></textarea>
                @error('description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Video --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Vidéo (MP4, WebM, MOV — max 700 Mo)</label>
                <div class="mt-1.5 flex items-center gap-3">
                    <label class="flex cursor-pointer items-center gap-2 rounded-2xl border border-dashed border-zinc-300 px-4 py-3 text-sm text-zinc-500 transition hover:border-teal-400 hover:text-teal-600 dark:border-zinc-600 dark:text-zinc-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>{{ $videoFile ? $videoFile->getClientOriginalName() : 'Choisir une vidéo' }}</span>
                        <input wire:model="videoFile" type="file" accept="video/*" class="sr-only" />
                    </label>
                    <div wire:loading wire:target="videoFile" class="text-xs text-zinc-400">Chargement…</div>
                </div>
                @error('videoFile') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Attachment --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Fichier joint (PDF, ZIP… — max 200 Mo)</label>
                <div class="mt-1.5 flex items-center gap-3">
                    <label class="flex cursor-pointer items-center gap-2 rounded-2xl border border-dashed border-zinc-300 px-4 py-3 text-sm text-zinc-500 transition hover:border-teal-400 hover:text-teal-600 dark:border-zinc-600 dark:text-zinc-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <span>{{ $attachment ? $attachment->getClientOriginalName() : 'Choisir un fichier' }}</span>
                        <input wire:model="attachment" type="file" class="sr-only" />
                    </label>
                    <div wire:loading wire:target="attachment" class="text-xs text-zinc-400">Chargement…</div>
                </div>
                @error('attachment') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Preview toggle --}}
            <label class="flex cursor-pointer items-center gap-3">
                <div x-data="{ on: $wire.entangle('isPreview') }" @click="on = !on"
                     :class="on ? 'bg-amber-400' : 'bg-zinc-200 dark:bg-zinc-700'"
                     class="relative h-6 w-11 rounded-full transition">
                    <span :class="on ? 'translate-x-5' : 'translate-x-1'"
                          class="absolute top-1 size-4 rounded-full bg-white shadow transition-transform"></span>
                </div>
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Aperçu libre (accessible sans paiement)</span>
            </label>
        </div>

        {{-- Buttons --}}
        <div class="mt-6 flex items-center gap-3">
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="flex items-center gap-2 rounded-full bg-zinc-950 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 disabled:opacity-60 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-teal-600 dark:hover:text-white">
                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Mettre à jour' : 'Ajouter la leçon' }}</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
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
