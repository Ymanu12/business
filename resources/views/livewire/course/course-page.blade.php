<div class="flex h-[calc(100vh-4rem)] overflow-hidden">

    {{-- Sidebar: lesson list --}}
    <aside class="w-80 shrink-0 overflow-y-auto border-r border-zinc-200 bg-white dark:bg-zinc-900 dark:border-zinc-700/60">

        {{-- Header --}}
        <div class="border-b border-zinc-200 p-5 dark:border-zinc-700/60">
            <a href="{{ route('orders.show', $order->uuid) }}" wire:navigate
               class="flex items-center gap-1.5 text-xs text-zinc-400 transition hover:text-teal-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Retour à la commande
            </a>
            <h1 class="mt-3 text-sm font-black leading-snug text-zinc-950 dark:text-white line-clamp-2">
                {{ $order->gig->title }}
            </h1>
            <p class="mt-1 text-xs text-zinc-400">par {{ $order->gig->freelancer->name }}</p>

            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                    <span>Progression</span>
                    <span class="font-bold text-teal-600">{{ $progressPct }}%</span>
                </div>
                <div class="mt-1.5 h-2 rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <div class="h-2 rounded-full bg-teal-500 transition-all duration-500" style="width: {{ $progressPct }}%"></div>
                </div>
                <p class="mt-1 text-[11px] text-zinc-400">{{ count($completed) }} / {{ $lessons->count() }} leçons terminées</p>
            </div>
        </div>

        {{-- Lessons --}}
        <div class="p-3">
            @forelse ($lessons as $i => $lesson)
                @php $isDone = in_array($lesson->id, $completed); @endphp
                <button wire:click="selectLesson({{ $lesson->id }})"
                        class="w-full rounded-2xl px-4 py-3 text-left transition {{ $activeLessonId === $lesson->id ? 'bg-teal-50 dark:bg-teal-900/30' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800' }}">
                    <div class="flex items-start gap-3">
                        {{-- Completion circle --}}
                        <div class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full border-2 {{ $isDone ? 'border-teal-500 bg-teal-500' : 'border-zinc-300 dark:border-zinc-600' }}">
                            @if ($isDone)
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <span class="text-[10px] font-bold text-zinc-400">{{ $i + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold {{ $activeLessonId === $lesson->id ? 'text-teal-700 dark:text-teal-400' : 'text-zinc-800 dark:text-zinc-200' }} line-clamp-2">
                                {{ $lesson->title }}
                            </p>
                            <div class="mt-0.5 flex items-center gap-2 text-[11px] text-zinc-400">
                                @if ($lesson->video_path)
                                    <span>Vidéo</span>
                                @endif
                                @if ($lesson->file_path)
                                    <span>Fichier</span>
                                @endif
                                @if ($lesson->is_preview)
                                    <span class="rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold uppercase text-amber-600">Aperçu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </button>
            @empty
                <div class="py-8 text-center text-sm text-zinc-400">
                    Aucune leçon disponible pour l'instant.
                </div>
            @endforelse
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto bg-zinc-50 dark:bg-zinc-950">
        @if ($activeLesson)
            <div class="mx-auto max-w-4xl px-6 py-8">

                {{-- Video player --}}
                @if ($activeLesson->video_path)
                    <div class="aspect-video w-full overflow-hidden rounded-[1.75rem] bg-black shadow-xl">
                        <video controls class="h-full w-full"
                               src="{{ asset('storage/' . $activeLesson->video_path) }}"
                               preload="metadata">
                        </video>
                    </div>
                @else
                    <div class="flex aspect-video w-full items-center justify-center rounded-[1.75rem] border-2 border-dashed border-zinc-200 bg-zinc-100 dark:bg-zinc-900 dark:border-zinc-700">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto size-14 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="mt-3 text-sm font-medium text-zinc-400">Pas de vidéo pour cette leçon</p>
                        </div>
                    </div>
                @endif

                {{-- Lesson info --}}
                <div class="mt-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-zinc-950 dark:text-white">{{ $activeLesson->title }}</h2>
                        @if ($activeLesson->description)
                            <p class="mt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-400">{{ $activeLesson->description }}</p>
                        @endif
                    </div>

                    {{-- Mark complete button --}}
                    @php $isDone = in_array($activeLesson->id, $completed); @endphp
                    <button wire:click="toggleComplete({{ $activeLesson->id }})"
                            class="shrink-0 flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold transition
                                   {{ $isDone
                                        ? 'bg-teal-500 text-white hover:bg-teal-600'
                                        : 'border border-zinc-300 text-zinc-600 hover:border-teal-400 hover:text-teal-600 dark:border-zinc-600 dark:text-zinc-400' }}">
                        @if ($isDone)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Terminée
                        @else
                            Marquer comme terminée
                        @endif
                    </button>
                </div>

                {{-- File attachment --}}
                @if ($activeLesson->file_path)
                    <div class="mt-6 flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-4 dark:bg-zinc-800 dark:border-zinc-700">
                        <div class="flex size-10 items-center justify-center rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Ressource téléchargeable</p>
                            <p class="text-xs text-zinc-400">{{ basename($activeLesson->file_path) }}</p>
                        </div>
                        <a href="{{ asset('storage/' . $activeLesson->file_path) }}" download
                           class="rounded-full border border-zinc-200 px-4 py-2 text-xs font-semibold text-zinc-600 transition hover:border-teal-300 hover:text-teal-700 dark:border-zinc-600 dark:text-zinc-300">
                            Télécharger
                        </a>
                    </div>
                @endif

                {{-- Navigation buttons --}}
                @php
                    $lessonsList = $lessons->values();
                    $currentIdx  = $lessonsList->search(fn($l) => $l->id === $activeLesson->id);
                    $prevLesson  = $currentIdx > 0 ? $lessonsList[$currentIdx - 1] : null;
                    $nextLesson  = $currentIdx < $lessonsList->count() - 1 ? $lessonsList[$currentIdx + 1] : null;
                @endphp
                <div class="mt-8 flex items-center justify-between border-t border-zinc-200 pt-6 dark:border-zinc-700">
                    @if ($prevLesson)
                        <button wire:click="selectLesson({{ $prevLesson->id }})"
                                class="flex items-center gap-2 rounded-full border border-zinc-200 px-5 py-2.5 text-sm font-semibold text-zinc-600 transition hover:border-teal-300 hover:text-teal-700 dark:border-zinc-600 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Leçon précédente
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if ($nextLesson)
                        <button wire:click="selectLesson({{ $nextLesson->id }})"
                                class="flex items-center gap-2 rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-teal-600 dark:hover:text-white">
                            Leçon suivante
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @elseif ($progressPct === 100)
                        <div class="flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Formation terminée !
                        </div>
                    @else
                        <div></div>
                    @endif
                </div>
            </div>
        @else
            <div class="flex h-full items-center justify-center">
                <div class="text-center text-zinc-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto size-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-3 font-medium">Sélectionnez une leçon pour commencer</p>
                </div>
            </div>
        @endif
    </main>
</div>
