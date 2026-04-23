<div
    class="flex flex-col"
    style="height: calc(100vh - 65px);"
    wire:poll.2500ms="refreshMessages"
>

    {{-- ── Barre de contact (style WhatsApp) ──────────────────────── --}}
    <div class="flex items-center gap-3 border-b border-zinc-200/80 bg-white/95 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-700/60">
        <a href="{{ route('inbox') }}" wire:navigate
           class="flex size-9 shrink-0 items-center justify-center rounded-full text-zinc-500 transition hover:bg-zinc-100 hover:text-teal-700 dark:text-zinc-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        @if ($other)
            <div class="relative">
                <img src="{{ $other->avatarUrl() }}" alt="{{ $other->name }}"
                     class="size-10 rounded-full object-cover ring-2 ring-teal-100">
                <span class="absolute bottom-0 right-0 block size-2.5 rounded-full bg-teal-500 ring-2 ring-white"></span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $other->name }}</p>
                <p class="text-xs text-teal-600">{{ $other->role?->label() ?? 'Utilisateur' }} · En ligne</p>
            </div>
        @endif
    </div>

    {{-- ── Zone de messages ─────────────────────────────────────────── --}}
    <div
        class="flex-1 overflow-y-auto px-4 py-4"
        style="background: repeating-linear-gradient(0deg, transparent, transparent 29px, rgba(13,148,136,0.03) 30px);"
        id="messages-container"
        x-data
        x-init="
            const el = $el;
            const scrollBottom = () => el.scrollTop = el.scrollHeight;
            scrollBottom();
            $wire.on('message-sent', scrollBottom);
            $wire.on('new-message-arrived', scrollBottom);
        "
    >
        @if ($messages->isEmpty())
            <div class="flex h-full items-center justify-center">
                <div class="text-center">
                    <div class="mx-auto mb-3 flex size-14 items-center justify-center rounded-full bg-teal-50 text-teal-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.812L3 20l1.063-3.188A7.477 7.477 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Démarrez la conversation</p>
                    <p class="mt-1 text-xs text-zinc-400">Envoyez votre premier message à {{ $other?->name ?? 'votre contact' }}</p>
                </div>
            </div>
        @else
            {{-- Regrouper les messages par date --}}
            @php
                $grouped = $messages->groupBy(fn($m) => $m->created_at->format('Y-m-d'));
            @endphp

            @foreach ($grouped as $date => $dayMessages)
                {{-- Séparateur date --}}
                <div class="my-4 flex items-center gap-3">
                    <div class="h-px flex-1 bg-zinc-200/70"></div>
                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-[10px] font-semibold text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                        {{ \Carbon\Carbon::parse($date)->isToday() ? "Aujourd'hui" : (\Carbon\Carbon::parse($date)->isYesterday() ? 'Hier' : \Carbon\Carbon::parse($date)->translatedFormat('d M Y')) }}
                    </span>
                    <div class="h-px flex-1 bg-zinc-200/70"></div>
                </div>

                @foreach ($dayMessages as $msg)
                    @php $isMe = $msg->sender_id === auth()->id(); @endphp

                    <div class="mb-1 flex {{ $isMe ? 'justify-end' : 'justify-start' }} items-end gap-2">
                        {{-- Avatar de l'autre côté --}}
                        @if (!$isMe)
                            <img src="{{ $msg->sender?->avatarUrl() }}" alt=""
                                 class="mb-1 size-7 shrink-0 rounded-full object-cover">
                        @endif

                        {{-- Bulle de message --}}
                        <div class="max-w-[72%] min-w-[80px] sm:max-w-sm">
                            <div class="relative rounded-2xl px-4 py-2.5 text-sm shadow-sm {{ $isMe ? 'rounded-br-sm bg-teal-600 text-white' : 'rounded-bl-sm border border-zinc-200/80 bg-white text-zinc-800' }} dark:border-zinc-700/60 dark:bg-zinc-800">
                                {{ $msg->body }}

                                {{-- Queue de bulle --}}
                                @if ($isMe)
                                    <span class="absolute -right-1.5 bottom-0 h-3 w-3 overflow-hidden">
                                        <svg viewBox="0 0 12 12" class="fill-teal-600">
                                            <path d="M0 12 L12 0 L12 12 Z"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="absolute -left-1.5 bottom-0 h-3 w-3 overflow-hidden">
                                        <svg viewBox="0 0 12 12" class="fill-white">
                                            <path d="M12 12 L0 0 L0 12 Z"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            {{-- Heure + statut lu --}}
                            <div class="mt-1 flex items-center gap-1 px-1 {{ $isMe ? 'justify-end' : '' }}">
                                <span class="text-[10px] text-zinc-400">{{ $msg->created_at->format('H:i') }}</span>
                                @if ($isMe)
                                    @if ($msg->read_at)
                                        {{-- Double coche bleue --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-teal-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12.75l6 6" opacity="0.5"/>
                                        </svg>
                                    @else
                                        {{-- Coche grise --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if ($isMe)
                            <img src="{{ auth()->user()->avatarUrl() }}" alt=""
                                 class="mb-1 size-7 shrink-0 rounded-full object-cover">
                        @endif
                    </div>
                @endforeach
            @endforeach
        @endif
    </div>

    {{-- ── Zone de saisie (style WhatsApp) ────────────────────────── --}}
    <div class="border-t border-zinc-200/80 bg-white/95 px-4 py-3 backdrop-blur dark:border-zinc-700/60">
        <form
            wire:submit.prevent="sendMessage"
            class="flex items-end gap-2"
        >
            <div class="flex flex-1 items-end gap-2 rounded-3xl border border-zinc-200 bg-stone-50 px-4 py-2 transition focus-within:border-teal-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-teal-100 dark:bg-zinc-900 dark:border-zinc-700">
                <textarea
                    wire:model="newMessage"
                    rows="1"
                    placeholder="Votre message…"
                    class="max-h-32 min-h-[24px] w-full resize-none bg-transparent text-sm text-zinc-800 outline-none placeholder:text-zinc-400 dark:text-zinc-200 dark:placeholder:text-zinc-500"
                    x-data
                    x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 128) + 'px'"
                    x-on:keydown.enter.prevent.stop="if (!$event.shiftKey) { $wire.sendMessage() }"
                ></textarea>
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex size-11 shrink-0 items-center justify-center rounded-full bg-teal-600 text-white shadow-sm transition hover:bg-teal-700 active:scale-95 disabled:opacity-60"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z"/>
                </svg>
            </button>
        </form>

        @error('newMessage')
            <p class="mt-1 px-4 text-xs text-rose-600">{{ $message }}</p>
        @enderror

        <p class="mt-1.5 px-1 text-center text-[10px] text-zinc-400">
            Entrée pour envoyer · Maj+Entrée pour nouvelle ligne
        </p>
    </div>
</div>
