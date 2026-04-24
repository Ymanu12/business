<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        @click.outside="open = false"
        class="relative inline-flex size-11 items-center justify-center rounded-2xl border border-zinc-200 bg-white text-zinc-600 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:text-teal-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:border-teal-700 dark:hover:text-teal-400"
        aria-label="Notifications"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 1-2.357.165c-2.148 0-4.228-.294-6.143-.84m8.5 0a7.5 7.5 0 1 0-8.5 0m8.5 0a24.255 24.255 0 0 0 3.208-.815m-11.708.815A24.255 24.255 0 0 1 3.15 16.5m13.207.582a3 3 0 1 1-5.714 0" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute -right-1 -top-1 flex min-h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute right-0 mt-3 w-[22rem] overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-2xl shadow-zinc-900/10 dark:border-zinc-700 dark:bg-zinc-800 dark:shadow-zinc-900/40"
    >
        <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-700">
            <div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Notifications</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ $unreadCount }} non lue{{ $unreadCount > 1 ? 's' : '' }}
                </p>
            </div>
            @if ($unreadCount > 0)
                <button
                    wire:click="markAllRead"
                    type="button"
                    class="text-xs font-semibold text-teal-700 transition hover:text-zinc-900 dark:text-teal-400"
                >
                    Tout lire
                </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto p-3">
            @forelse ($notifications as $notification)
                <a
                    href="{{ route('notifications.open', $notification) }}"
                    class="block rounded-2xl px-4 py-3 transition hover:bg-teal-50 dark:hover:bg-zinc-700/70"
                >
                    <div class="flex items-start gap-3">
                        <span class="mt-1 inline-flex size-2.5 shrink-0 rounded-full {{ $notification->read_at ? 'bg-zinc-300 dark:bg-zinc-600' : 'bg-teal-500' }}"></span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="mt-1 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                                {{ $notification->data['body'] ?? '' }}
                            </p>
                            <p class="mt-2 text-xs font-medium text-zinc-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-10 text-center">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-300">Aucune notification</p>
                    <p class="mt-1 text-xs text-zinc-400">Les alertes apparaîtront ici.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
