<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Messagerie</p>
            <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Messages</h1>
        </div>
    </div>

    @if ($conversations->isEmpty())
        <div class="mt-12 rounded-[2rem] border border-zinc-200 bg-white/90 px-6 py-16 text-center shadow-sm dark:bg-zinc-800/90 dark:border-zinc-700">
            <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-stone-100 text-zinc-400 dark:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                </svg>
            </div>
            <h3 class="mt-5 text-lg font-bold text-zinc-900 dark:text-zinc-100">Aucune conversation</h3>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Vos échanges avec les freelances et clients apparaîtront ici.</p>
            <a href="{{ route('search') }}" wire:navigate
               class="mt-6 inline-flex items-center rounded-full bg-zinc-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                Explorer les services
            </a>
        </div>
    @else
        <div class="mt-6 grid gap-3">
            @foreach ($conversations as $conversation)
                @php $other = $conversation->otherUser(); @endphp
                <a href="{{ route('inbox.show', $conversation->id) }}" wire:navigate
                   class="group flex items-center gap-4 rounded-[1.75rem] border border-zinc-200/80 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:shadow-md dark:border-zinc-700/60 dark:bg-zinc-800">

                    @if ($other)
                        <div class="relative shrink-0">
                            <img src="{{ $other->avatarUrl() }}" alt="{{ $other->name }}"
                                 class="size-12 rounded-full object-cover ring-2 ring-stone-100 dark:ring-zinc-700">
                            @php $unread = $conversation->unreadCount(auth()->id()); @endphp
                            @if ($unread > 0)
                                <span class="absolute -right-1 -top-1 flex size-5 items-center justify-center rounded-full bg-teal-600 text-[10px] font-bold text-white">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ $other?->name ?? 'Utilisateur inconnu' }}
                            </span>
                            @if ($conversation->lastMessage)
                                <span class="shrink-0 text-xs text-zinc-400">
                                    {{ $conversation->lastMessage->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        @if ($conversation->lastMessage)
                            <p class="mt-1 text-sm text-zinc-500 line-clamp-1 dark:text-zinc-400">
                                {{ $conversation->lastMessage->body }}
                            </p>
                        @else
                            <p class="mt-1 text-sm italic text-zinc-400">Aucun message pour l'instant</p>
                        @endif
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-zinc-300 transition group-hover:text-teal-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
</div>
