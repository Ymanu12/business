<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) - AfriTask</title>
    <meta
        name="description"
        content="@yield('description', 'Marketplace freelance africaine pour trouver rapidement des talents verifies et payer simplement.')"
    >
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'AfriTask')">
    <meta property="og:site_name" content="AfriTask">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#0f766e">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-stone-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    @php($isHomepage = request()->routeIs('home'))
    <div class="relative min-h-full overflow-x-hidden bg-[radial-gradient(circle_at_top,_rgba(13,148,136,0.14),_transparent_35%),linear-gradient(180deg,_#fcfcf9_0%,_#f6f3ee_100%)] dark:bg-none dark:bg-zinc-950">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-[linear-gradient(120deg,rgba(245,158,11,0.08),transparent_55%,rgba(13,148,136,0.1))] dark:opacity-30"></div>

        <header @class([
            'sticky top-0 z-50 backdrop-blur-xl',
            'border-b border-zinc-200/80 bg-white/95 shadow-sm' => $isHomepage,
            'border-b border-white/60 bg-white/85 dark:border-white/10 dark:bg-zinc-900/90' => ! $isHomepage,
        ])>
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="shrink-0">
                    <div class="flex items-center gap-3">
                        @if ($isHomepage)
                            <div class="[font-family:Manrope,_var(--font-sans)] text-[2rem] font-extrabold leading-none tracking-[-0.06em] text-zinc-950">
                                AfriTask<span class="text-[#1dbf73]">.</span>
                            </div>
                        @else
                            <div class="flex size-11 items-center justify-center rounded-2xl bg-zinc-950 text-sm font-black uppercase tracking-[0.35em] text-amber-300 shadow-lg shadow-zinc-950/15 dark:bg-zinc-800">
                                AT
                            </div>
                            <div>
                                <div class="text-lg font-black tracking-tight text-zinc-950 dark:text-white">AfriTask</div>
                                <div class="text-[11px] uppercase tracking-[0.28em] text-teal-700 dark:text-teal-400">Freelance marketplace</div>
                            </div>
                        @endif
                    </div>
                </a>

                <div class="hidden min-w-0 flex-1 lg:block">
                    <form action="{{ route('search') }}" method="GET" class="mx-auto max-w-2xl">
                        <label @class([
                            'flex items-center gap-3 px-4 py-3 transition',
                            'overflow-hidden rounded-xl border border-zinc-300 bg-white shadow-sm focus-within:border-[#1dbf73] focus-within:ring-2 focus-within:ring-[#1dbf73]/10' => $isHomepage,
                            'rounded-full border border-zinc-200 bg-white shadow-sm shadow-zinc-200/50 focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-100 dark:border-zinc-700 dark:bg-zinc-800 dark:shadow-none' => ! $isHomepage,
                        ])>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                            </svg>
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Rechercher un service, un metier, une specialite..."
                                class="w-full bg-transparent text-sm text-zinc-700 outline-none placeholder:text-zinc-400 dark:text-zinc-200 dark:placeholder:text-zinc-500"
                            >
                            @if ($isHomepage)
                                <button type="submit" class="inline-flex shrink-0 items-center rounded-lg bg-zinc-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-700">
                                    Chercher
                                </button>
                            @else
                                <button type="submit" class="inline-flex shrink-0 items-center rounded-full bg-zinc-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600">
                                    Chercher
                                </button>
                            @endif
                        </label>
                    </form>
                </div>

                <nav class="ml-auto flex items-center gap-2 sm:gap-3">
                    @auth
                        @if (auth()->user()->isClient())
                            <a
                                href="{{ route('register', ['role' => 'freelance']) }}"
                                class="hidden rounded-full px-3 py-2 text-sm font-medium text-zinc-600 transition hover:bg-white hover:text-[#1dbf73] dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-teal-400 sm:inline-flex"
                            >
                                Devenir freelance
                            </a>
                        @endif

                        <livewire:notification.notification-bell />

                        <a
                            href="{{ route('inbox') }}"
                            class="relative inline-flex size-11 items-center justify-center rounded-2xl border border-zinc-200 bg-white text-zinc-600 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:text-teal-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:border-teal-700 dark:hover:text-teal-400"
                            aria-label="Messages"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.812L3 20l1.063-3.188A7.477 7.477 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </a>

                        <div x-data="{ open: false }" class="relative">
                            <button
                                @click="open = !open"
                                @click.outside="open = false"
                                class="flex items-center gap-3 rounded-full border border-zinc-200 bg-white py-1 pl-1 pr-3 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-teal-700"
                            >
                                <img
                                    src="{{ auth()->user()->avatarUrl() }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="size-10 rounded-full object-cover ring-2 ring-teal-100 dark:ring-teal-900"
                                >
                                <div class="hidden text-left sm:block">
                                    <div class="max-w-32 truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</div>
                                    <div class="text-xs uppercase tracking-[0.22em] text-zinc-400 dark:text-zinc-500">{{ auth()->user()->role?->label() ?? 'Utilisateur' }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="hidden size-4 text-zinc-400 dark:text-zinc-500 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute right-0 mt-3 w-72 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-2xl shadow-zinc-900/10 dark:border-zinc-700 dark:bg-zinc-800 dark:shadow-zinc-900/40"
                            >
                                <div class="bg-[linear-gradient(135deg,_#0f172a,_#115e59)] px-5 py-4 text-white">
                                    <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.24em] text-teal-100">{{ auth()->user()->role?->label() ?? 'Utilisateur' }}</p>
                                </div>

                                <div class="grid gap-1 p-3">
                                    <a href="{{ route('dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-teal-400">Dashboard</a>

                                    @if (auth()->user()->isFreelancer())
                                        <a href="{{ route('seller.gigs.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-teal-400">Mes services</a>
                                    @endif

                                    <a href="{{ route('orders.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-teal-400">Mes commandes</a>
                                    <a href="{{ route('wallet') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-teal-400">Mon wallet</a>
                                    <a href="{{ route('profile.edit') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-zinc-700 transition hover:bg-teal-50 hover:text-teal-700 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:hover:text-teal-400">Parametres</a>

                                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                                        @csrf
                                        <button type="submit" class="w-full rounded-2xl bg-rose-50 px-4 py-3 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400 dark:hover:bg-rose-950/60">
                                            Deconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        @if ($isHomepage)
                            <a href="{{ route('search') }}" class="hidden rounded-full px-3 py-2 text-sm font-medium text-zinc-700 transition hover:text-[#1dbf73] lg:inline-flex">
                                Explorer
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="rounded-full px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-white hover:text-[#1dbf73] dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-teal-400">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" @class([
                            'inline-flex items-center px-4 py-2.5 text-sm font-semibold transition',
                            'rounded-lg border border-[#1dbf73] text-[#1dbf73] hover:bg-[#1dbf73] hover:text-white' => $isHomepage,
                            'rounded-full bg-zinc-950 text-white hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600' => ! $isHomepage,
                        ])>
                            S'inscrire
                        </a>
                    @endauth
                </nav>
            </div>

            <div class="border-t border-zinc-100 px-4 py-3 dark:border-zinc-800 lg:hidden sm:px-6">
                <form action="{{ route('search') }}" method="GET">
                    <label class="flex items-center gap-3 rounded-full border border-zinc-200 bg-white px-4 py-3 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Rechercher un service..."
                            class="w-full bg-transparent text-sm text-zinc-700 outline-none placeholder:text-zinc-400 dark:text-zinc-200 dark:placeholder:text-zinc-500"
                        >
                    </label>
                </form>
            </div>
        </header>

        @if (session('success') || session('error'))
            <div class="mx-auto max-w-7xl px-4 pt-5 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 shadow-sm dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-300">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        <main class="relative">
            {{ $slot }}
        </main>

        <footer class="mt-20 border-t border-white/70 bg-zinc-950 text-zinc-300">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
                    <div class="space-y-5">
                        <div>
                            <p class="text-sm uppercase tracking-[0.28em] text-amber-300">AfriTask</p>
                            <h2 class="mt-2 text-2xl font-black text-white">Des talents africains, des projets concrets.</h2>
                        </div>

                        <p class="max-w-md text-sm leading-7 text-zinc-400">
                            Une marketplace freelance pensee pour aller vite, inspirer confiance et faciliter les paiements locaux comme internationaux.
                        </p>

                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded-full bg-white/10 px-3 py-1.5 text-zinc-200">Mobile Money</span>
                            <span class="rounded-full bg-white/10 px-3 py-1.5 text-zinc-200">Escrow</span>
                            <span class="rounded-full bg-white/10 px-3 py-1.5 text-zinc-200">Freelances verifies</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Categories</h3>
                        <ul class="mt-4 grid gap-3 text-sm text-zinc-400">
                            @foreach (\App\Models\Category::parents()->limit(5)->get() as $category)
                                <li>
                                    <a href="{{ route('categories.show', $category->slug) }}" class="transition hover:text-amber-300">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Explorer</h3>
                        <ul class="mt-4 grid gap-3 text-sm text-zinc-400">
                            <li><a href="{{ route('search') }}" class="transition hover:text-amber-300">Trouver un service</a></li>
                            <li><a href="{{ route('register', ['role' => 'freelance']) }}" class="transition hover:text-amber-300">Devenir freelance</a></li>
                            <li><a href="{{ route('login') }}" class="transition hover:text-amber-300">Se connecter</a></li>
                            <li><a href="{{ route('dashboard') }}" class="transition hover:text-amber-300">Dashboard</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-white">Paiements</h3>
                        <div class="mt-4 flex flex-wrap gap-2 text-xs font-bold">
                            <span class="rounded-full bg-amber-400 px-3 py-1.5 text-zinc-950">MTN</span>
                            <span class="rounded-full bg-orange-500 px-3 py-1.5 text-white">Orange</span>
                            <span class="rounded-full bg-sky-500 px-3 py-1.5 text-white">Flooz</span>
                            <span class="rounded-full bg-emerald-500 px-3 py-1.5 text-white">Moov</span>
                            <span class="rounded-full bg-indigo-500 px-3 py-1.5 text-white">Stripe</span>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col gap-3 border-t border-white/10 pt-6 text-xs text-zinc-500 sm:flex-row sm:items-center sm:justify-between">
                    <p>&copy; {{ date('Y') }} AfriTask. Concu pour l'ecosysteme freelance africain.</p>
                    <p>Rapide, clair, mobile first.</p>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
    @fluxScripts
</body>
</html>
