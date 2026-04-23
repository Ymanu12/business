<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Entête --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-zinc-950 dark:text-white">Paramètres</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Gérez votre compte et vos préférences.</p>
    </div>

    <div class="flex flex-col gap-6 md:flex-row md:gap-10">

        {{-- Navigation latérale --}}
        <aside class="shrink-0 md:w-52">
            <nav class="flex flex-row gap-1 overflow-x-auto md:flex-col">
                <a
                    href="{{ route('profile.edit') }}"
                    wire:navigate
                    @class([
                        'flex items-center gap-2.5 rounded-2xl px-4 py-2.5 text-sm font-semibold transition',
                        'bg-zinc-950 text-white shadow-sm' => request()->routeIs('profile.edit'),
                        'text-zinc-600 hover:bg-white hover:text-teal-700' => !request()->routeIs('profile.edit'),
                    ])
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Profil
                </a>

                <a
                    href="{{ route('security.edit') }}"
                    wire:navigate
                    @class([
                        'flex items-center gap-2.5 rounded-2xl px-4 py-2.5 text-sm font-semibold transition',
                        'bg-zinc-950 text-white shadow-sm' => request()->routeIs('security.edit'),
                        'text-zinc-600 hover:bg-white hover:text-teal-700' => !request()->routeIs('security.edit'),
                    ])
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Sécurité
                </a>

                <a
                    href="{{ route('appearance.edit') }}"
                    wire:navigate
                    @class([
                        'flex items-center gap-2.5 rounded-2xl px-4 py-2.5 text-sm font-semibold transition',
                        'bg-zinc-950 text-white shadow-sm' => request()->routeIs('appearance.edit'),
                        'text-zinc-600 hover:bg-white hover:text-teal-700' => !request()->routeIs('appearance.edit'),
                    ])
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                    </svg>
                    Apparence
                </a>
            </nav>
        </aside>

        {{-- Contenu --}}
        <div class="flex-1">
            <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-6 shadow-sm backdrop-blur sm:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
                @if ($heading ?? null)
                    <div class="mb-6 border-b border-zinc-100 pb-5 dark:border-zinc-800">
                        <h2 class="text-lg font-black text-zinc-950 dark:text-white">{{ $heading }}</h2>
                        @if ($subheading ?? null)
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $subheading }}</p>
                        @endif
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>

    </div>
</div>
