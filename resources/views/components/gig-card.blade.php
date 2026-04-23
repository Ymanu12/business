@props(['gig'])

<div class="group overflow-hidden rounded-[1.75rem] border border-zinc-200/80 bg-white shadow-sm shadow-zinc-900/5 transition hover:-translate-y-1 hover:border-teal-200 hover:shadow-xl dark:border-zinc-700/60 dark:bg-zinc-800">
    <a href="{{ route('gigs.show', $gig->slug) }}" class="relative block aspect-video overflow-hidden">
        <img
            src="{{ $gig->thumbnailUrl() }}"
            alt="{{ $gig->title }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
        >

        <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-zinc-700 backdrop-blur-sm dark:bg-zinc-800/90 dark:text-zinc-300">
            {{ $gig->category->name ?? '' }}
        </span>
    </a>

    <div class="p-5">
        <div class="mb-3 flex items-center gap-2">
            <img
                src="{{ $gig->freelancer->avatarUrl() }}"
                alt="{{ $gig->freelancer->name }}"
                class="size-7 rounded-full object-cover ring-2 ring-stone-100 dark:ring-zinc-700"
            >

            <a
                href="{{ route('profile.show', $gig->freelancer->username ?? $gig->freelancer->id) }}"
                class="truncate text-xs font-semibold text-zinc-700 transition hover:text-teal-700 dark:text-zinc-300"
            >
                {{ $gig->freelancer->name }}
            </a>

            @if ($gig->freelancer->is_verified)
                <span class="shrink-0 text-teal-600" title="Verifie">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>

        <a
            href="{{ route('gigs.show', $gig->slug) }}"
            class="block text-base font-bold leading-snug text-zinc-950 transition group-hover:text-teal-700 line-clamp-2 dark:text-white"
        >
            {{ $gig->title }}
        </a>

        @if ($gig->avg_rating > 0)
            <div class="mt-4 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">{{ number_format($gig->avg_rating, 1) }}</span>
                <span class="text-xs text-zinc-400">({{ $gig->reviews_count }})</span>
            </div>
        @endif

        <div class="mt-5 flex items-center justify-between border-t border-zinc-100 pt-4 dark:border-zinc-800">
            <div>
                <span class="text-[10px] uppercase tracking-[0.24em] text-zinc-400">A partir de</span>
                <div class="mt-1 text-lg font-black text-teal-700">{{ $gig->formattedPrice() }}</div>
            </div>

            <a
                href="{{ route('gigs.show', $gig->slug) }}"
                class="rounded-full bg-zinc-950 px-4 py-2 text-xs font-semibold text-white transition hover:bg-teal-700"
            >
                Voir
            </a>
        </div>
    </div>
</div>
