<div
    class="flex flex-col"
    style="height: calc(100vh - 65px);"
>

    {{-- ── Barre de contact (style WhatsApp) ──────────────────────── --}}
    <div class="flex items-center gap-3 border-b border-zinc-200/80 bg-white/95 px-4 py-3 shadow-sm backdrop-blur dark:bg-zinc-900/95 dark:border-zinc-700/60">
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
                <span class="absolute bottom-0 right-0 block size-2.5 rounded-full bg-teal-500 ring-2 ring-white dark:ring-zinc-900"></span>
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
        x-data="{ channelName: 'conversations.{{ $conversation->id }}' }"
        x-init="
            const el = $el;
            const scrollBottom = () => el.scrollTop = el.scrollHeight;
            scrollBottom();

            const playNotif = () => {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const o1 = ctx.createOscillator();
                    const o2 = ctx.createOscillator();
                    const gain = ctx.createGain();
                    o1.connect(gain); o2.connect(gain); gain.connect(ctx.destination);
                    o1.type = 'sine'; o1.frequency.setValueAtTime(880, ctx.currentTime);
                    o1.frequency.exponentialRampToValueAtTime(1100, ctx.currentTime + 0.08);
                    o2.type = 'sine'; o2.frequency.setValueAtTime(1320, ctx.currentTime + 0.08);
                    gain.gain.setValueAtTime(0.18, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
                    o1.start(ctx.currentTime); o1.stop(ctx.currentTime + 0.08);
                    o2.start(ctx.currentTime + 0.08); o2.stop(ctx.currentTime + 0.35);
                } catch (e) {}
            };

            $wire.on('message-sent', scrollBottom);
            $wire.on('new-message-arrived', () => { scrollBottom(); playNotif(); });

            if (window.Echo) {
                window.Echo.private(channelName)
                    .listen('.message.sent', () => { $wire.refreshMessages(); });
            } else {
                const poll = setInterval(() => $wire.refreshMessages(), 5000);
                $watch('channelName', () => clearInterval(poll));
            }
        "
        x-on:livewire:navigating.window="window.Echo?.leave(channelName)"
    >
        @if ($messages->isEmpty())
            <div class="flex h-full items-center justify-center">
                <div class="text-center">
                    <div class="mx-auto mb-3 flex size-14 items-center justify-center rounded-full bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
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
                <div wire:key="message-day-{{ $date }}" class="my-4 flex items-center gap-3">
                    <div class="h-px flex-1 bg-zinc-200/70 dark:bg-zinc-700/70"></div>
                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-[10px] font-semibold text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                        {{ \Carbon\Carbon::parse($date)->isToday() ? "Aujourd'hui" : (\Carbon\Carbon::parse($date)->isYesterday() ? 'Hier' : \Carbon\Carbon::parse($date)->translatedFormat('d M Y')) }}
                    </span>
                    <div class="h-px flex-1 bg-zinc-200/70 dark:bg-zinc-700/70"></div>
                </div>

                @foreach ($dayMessages as $msg)
                    @php $isMe = $msg->sender_id === auth()->id(); @endphp

                    <div wire:key="message-{{ $msg->id }}" class="mb-1 flex {{ $isMe ? 'justify-end' : 'justify-start' }} items-end gap-2">
                        {{-- Avatar de l'autre côté --}}
                        @if (!$isMe)
                            <img src="{{ $msg->sender?->avatarUrl() }}" alt=""
                                 class="mb-1 size-7 shrink-0 rounded-full object-cover">
                        @endif

                        {{-- Bulle de message --}}
                        <div class="max-w-[72%] min-w-[80px] sm:max-w-sm">
                            <div class="relative rounded-2xl px-4 py-2.5 text-sm shadow-sm {{ $isMe ? 'rounded-br-sm bg-teal-600 text-white' : 'rounded-bl-sm border border-zinc-200/80 bg-white text-zinc-800 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-100' }}">

                                {{-- Texte du message --}}
                                @if ($msg->body)
                                    <p class="whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                                @endif

                                {{-- Pièces jointes --}}
                                @if ($msg->attachments)
                                    <div class="mt-1.5 flex flex-col gap-2 {{ $msg->body ? 'mt-2' : '' }}">
                                        @foreach ($msg->attachments as $att)
                                            @php
                                                $isImage = str_starts_with($att['mime'], 'image/');
                                                $isVideo = str_starts_with($att['mime'], 'video/');
                                                $url = asset('storage/' . $att['path']);
                                            @endphp

                                            @if ($isImage)
                                                <a href="{{ $url }}" target="_blank" class="block overflow-hidden rounded-xl">
                                                    <img src="{{ $url }}" alt="{{ $att['name'] }}"
                                                         class="max-h-60 w-full rounded-xl object-cover transition hover:opacity-90">
                                                </a>
                                            @elseif ($isVideo)
                                                <video controls class="max-h-60 w-full rounded-xl bg-black">
                                                    <source src="{{ $url }}" type="{{ $att['mime'] }}">
                                                </video>
                                            @else
                                                <a href="{{ $url }}" target="_blank" download="{{ $att['name'] }}"
                                                   class="flex items-center gap-2.5 rounded-xl px-3 py-2 transition {{ $isMe ? 'bg-teal-700/50 hover:bg-teal-700' : 'bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-600 dark:hover:bg-zinc-500' }}">
                                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg {{ $isMe ? 'bg-teal-500' : 'bg-zinc-300 dark:bg-zinc-500' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 {{ $isMe ? 'text-white' : 'text-zinc-600 dark:text-zinc-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </span>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="truncate text-xs font-medium">{{ $att['name'] }}</p>
                                                        <p class="text-[10px] opacity-70">{{ number_format($att['size'] / 1024, 0) }} Ko</p>
                                                    </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Queue de bulle --}}
                                @if ($isMe)
                                    <span class="absolute -right-1.5 bottom-0 h-3 w-3 overflow-hidden">
                                        <svg viewBox="0 0 12 12" class="fill-teal-600">
                                            <path d="M0 12 L12 0 L12 12 Z"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="absolute -left-1.5 bottom-0 h-3 w-3 overflow-hidden">
                                        <svg viewBox="0 0 12 12" class="fill-white dark:fill-zinc-700">
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
    <div class="border-t border-zinc-200/80 bg-white/95 px-4 py-3 backdrop-blur dark:bg-zinc-900/95 dark:border-zinc-700/60">

        {{-- Aperçu des pièces jointes sélectionnées --}}
        @if (!empty($attachments))
            <div class="mb-2 flex flex-wrap gap-2">
                @foreach ($attachments as $i => $file)
                    @php
                        $mime = $file->getMimeType();
                        $isImg = str_starts_with($mime, 'image/');
                    @endphp
                    <div class="relative flex items-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800">
                        @if ($isImg)
                            <img src="{{ $file->temporaryUrl() }}" alt=""
                                 class="size-10 rounded-lg object-cover">
                        @else
                            <span class="flex size-10 items-center justify-center rounded-lg bg-teal-50 dark:bg-teal-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                        @endif
                        <div class="min-w-0">
                            <p class="max-w-[120px] truncate text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $file->getClientOriginalName() }}</p>
                            <p class="text-[10px] text-zinc-400">{{ number_format($file->getSize() / 1024, 0) }} Ko</p>
                        </div>
                        <button wire:click="removeAttachment({{ $i }})" type="button"
                                class="ml-1 flex size-5 shrink-0 items-center justify-center rounded-full bg-zinc-200 text-zinc-500 hover:bg-rose-100 hover:text-rose-600 dark:bg-zinc-700 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <form
            wire:submit.prevent="sendMessage"
            x-ref="messageForm"
            class="flex items-end gap-2"
            x-data="{
                emojiOpen: false,
                insertEmoji(emoji) {
                    const ta = $refs.messageInput;
                    const start = ta.selectionStart ?? ta.value.length;
                    const end   = ta.selectionEnd   ?? ta.value.length;
                    const before = ta.value.slice(0, start);
                    const after  = ta.value.slice(end);
                    const newVal = before + emoji + after;
                    ta.value = newVal;
                    ta.dispatchEvent(new Event('input', { bubbles: true }));
                    $wire.set('newMessage', newVal);
                    ta.focus();
                    const pos = start + emoji.length;
                    ta.setSelectionRange(pos, pos);
                    this.emojiOpen = false;
                }
            }"
            x-on:click.outside="emojiOpen = false"
        >
            {{-- Bouton pièce jointe --}}
            <label class="flex size-11 shrink-0 cursor-pointer items-center justify-center rounded-full text-zinc-500 transition hover:bg-zinc-100 hover:text-teal-700 dark:text-zinc-400 dark:hover:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <input
                    type="file"
                    wire:model="attachments"
                    multiple
                    accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                    class="sr-only"
                >
            </label>

            <div class="relative flex flex-1 items-end gap-2 rounded-3xl border border-zinc-200 bg-stone-50 px-4 py-2 transition focus-within:border-teal-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-teal-100 dark:bg-zinc-800 dark:border-zinc-700 dark:focus-within:bg-zinc-700 dark:focus-within:border-teal-500 dark:focus-within:ring-teal-800/40">
                <textarea
                    x-ref="messageInput"
                    wire:model="newMessage"
                    rows="1"
                    placeholder="Votre message…"
                    class="max-h-32 min-h-[24px] w-full resize-none bg-transparent text-sm text-zinc-800 outline-none placeholder:text-zinc-400 dark:text-zinc-200 dark:placeholder:text-zinc-500"
                    x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 128) + 'px'"
                    x-on:keydown.enter.prevent.stop="if (!$event.shiftKey) { $refs.messageForm.requestSubmit() }"
                ></textarea>

                {{-- Bouton émoji --}}
                <button type="button"
                        x-on:click.stop="emojiOpen = !emojiOpen"
                        class="mb-0.5 shrink-0 text-zinc-400 transition hover:text-teal-600 dark:hover:text-teal-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>

                {{-- Sélecteur d'émoji --}}
                <div
                    x-show="emojiOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute bottom-full right-0 mb-2 origin-bottom-right"
                    x-on:click.stop
                    x-cloak
                >
                    <emoji-picker
                        x-on:emoji-click="insertEmoji($event.detail.unicode)"
                        style="
                            --border-radius: 0.75rem;
                            --border-size: 1px;
                            --border-color: rgba(161,161,170,0.3);
                            --background: #ffffff;
                            --input-border-radius: 0.5rem;
                            --emoji-size: 1.4rem;
                            --num-columns: 8;
                            width: 320px;
                            height: 380px;
                        "
                        class="shadow-xl"
                    ></emoji-picker>
                </div>
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
        @error('attachments.*')
            <p class="mt-1 px-4 text-xs text-rose-600">{{ $message }}</p>
        @enderror

        <p class="mt-1.5 px-1 text-center text-[10px] text-zinc-400">
            Entrée pour envoyer · Maj+Entrée pour nouvelle ligne
        </p>
    </div>
</div>
