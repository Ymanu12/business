<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    @include('partials.head')
    @livewireStyles
</head>
<body class="h-full antialiased bg-stone-50 text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">

    {{-- Fond dégradé AfriTask --}}
    <div class="relative min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_top,_rgba(13,148,136,0.12),_transparent_35%),linear-gradient(180deg,_#fcfcf9_0%,_#f6f3ee_100%)] dark:bg-[radial-gradient(circle_at_top,_rgba(13,148,136,0.15),_transparent_35%),linear-gradient(180deg,_#18181b_0%,_#09090b_100%)]">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[linear-gradient(120deg,rgba(245,158,11,0.07),transparent_55%,rgba(13,148,136,0.09))]"></div>

        {{-- Header minimal --}}
        <header class="border-b border-white/60 bg-white/85 backdrop-blur-xl dark:bg-zinc-800/85 dark:border-white/10">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" wire:navigate>
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-2xl bg-zinc-950 text-[11px] font-black uppercase tracking-[0.35em] text-amber-300 shadow-lg shadow-zinc-950/15">
                            AT
                        </div>
                        <div>
                            <div class="text-base font-black tracking-tight text-zinc-950 dark:text-white">AfriTask</div>
                            <div class="text-[10px] uppercase tracking-[0.28em] text-teal-700">Freelance marketplace</div>
                        </div>
                    </div>
                </a>

                <nav class="flex items-center gap-2 text-sm">
                    @if (Route::has('login') && !request()->routeIs('login'))
                        <a href="{{ route('login') }}" class="rounded-full px-3 py-2 font-medium text-zinc-600 transition hover:text-teal-700 dark:text-zinc-400">
                            Connexion
                        </a>
                    @endif
                    @if (Route::has('register') && !request()->routeIs('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-zinc-950 px-4 py-2 font-semibold text-white transition hover:bg-teal-700">
                            S'inscrire
                        </a>
                    @endif
                </nav>
            </div>
        </header>

        {{-- Contenu centré --}}
        <main class="relative flex min-h-[calc(100vh-65px)] items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <div class="rounded-[2rem] border border-zinc-200/80 bg-white/95 p-8 shadow-xl shadow-zinc-900/8 backdrop-blur dark:bg-zinc-800/95 dark:border-zinc-700/60">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @livewireScripts
    @fluxScripts
</body>
</html>
