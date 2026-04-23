<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8" x-data="{ tab: 'info' }">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Modifier le service</p>
            <h1 class="mt-1 text-2xl font-black text-zinc-950 line-clamp-1 dark:text-white">{{ $gig->title }}</h1>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] @switch($gig->status->value) @case('published') bg-emerald-100 text-emerald-700 @break @case('draft') bg-zinc-100 text-zinc-600 @break @case('pending') bg-amber-100 text-amber-700 @break @case('paused') bg-orange-100 text-orange-700 @break @case('rejected') bg-rose-100 text-rose-700 @break @default bg-zinc-100 text-zinc-600 @endswitch dark:bg-zinc-800 dark:text-zinc-400">
                {{ ucfirst($gig->status->value) }}
            </span>
            <a href="{{ route('gigs.show', $gig->slug) }}" wire:navigate
               class="rounded-full border border-zinc-200 px-3 py-1.5 text-xs font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300 dark:border-zinc-700">
                Aperçu
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="mt-6 flex gap-1 rounded-2xl border border-zinc-200 bg-stone-50 p-1 dark:bg-zinc-900 dark:border-zinc-700">
        @foreach (['info' => 'Informations', 'packages' => 'Packages', 'publish' => 'Publication'] as $key => $label)
            <button @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'bg-white shadow-sm text-zinc-950 font-semibold dark:bg-zinc-700 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                    class="flex-1 rounded-xl px-4 py-2.5 text-sm transition">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Tab : Informations de base --}}
    <div x-show="tab === 'info'" class="mt-6">
        <form wire:submit="saveBasicInfo" class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <div class="grid gap-6">
                <flux:field>
                    <flux:label>Titre du service</flux:label>
                    <flux:input wire:model="title" placeholder="Ex : Je vais créer votre logo professionnel" />
                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="10" placeholder="Décrivez précisément ce que le client va recevoir..."></flux:textarea>
                    <flux:error name="description" />
                </flux:field>

                <div class="grid gap-6 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>Catégorie</flux:label>
                        <flux:select wire:model="categoryId">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="categoryId" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Tags</flux:label>
                        <flux:input wire:model="tagsInput" placeholder="logo, branding, figma" />
                        <flux:error name="tagsInput" />
                    </flux:field>
                </div>

                <div class="grid gap-6 lg:grid-cols-4">
                    <flux:field>
                        <flux:label>Prix de départ</flux:label>
                        <flux:input wire:model="startingPrice" type="number" min="1000" step="500" />
                        <flux:error name="startingPrice" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Devise</flux:label>
                        <flux:select wire:model="currency">
                            <option value="XOF">XOF</option>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </flux:select>
                        <flux:error name="currency" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Délai (jours)</flux:label>
                        <flux:input wire:model="deliveryDays" type="number" min="1" max="90" />
                        <flux:error name="deliveryDays" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Révisions</flux:label>
                        <flux:input wire:model="revisionCount" type="number" min="0" max="20" />
                        <flux:error name="revisionCount" />
                    </flux:field>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('seller.gigs.index') }}" wire:navigate
                   class="inline-flex items-center rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                    Retour
                </a>
                <flux:button type="submit" variant="primary">
                    Enregistrer les modifications
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Tab : Packages --}}
    <div x-show="tab === 'packages'" class="mt-6">
        <form wire:submit="savePackages" class="grid gap-5">
            @foreach ($packages as $i => $pkg)
                <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
                    <h3 class="text-base font-black text-zinc-950 dark:text-white">
                        Package {{ ucfirst($pkg['type']) }}
                    </h3>

                    <div class="mt-5 grid gap-5">
                        <div class="grid gap-5 lg:grid-cols-2">
                            <flux:field>
                                <flux:label>Nom du package</flux:label>
                                <flux:input wire:model="packages.{{ $i }}.name" placeholder="Ex : Basique — 1 concept" />
                                <flux:error name="packages.{{ $i }}.name" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Prix ({{ $currency }})</flux:label>
                                <flux:input wire:model="packages.{{ $i }}.price" type="number" min="1000" step="500" />
                                <flux:error name="packages.{{ $i }}.price" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label>Description du package</flux:label>
                            <flux:textarea wire:model="packages.{{ $i }}.description" rows="3"></flux:textarea>
                        </flux:field>

                        <div class="grid gap-5 lg:grid-cols-2">
                            <flux:field>
                                <flux:label>Délai (jours)</flux:label>
                                <flux:input wire:model="packages.{{ $i }}.delivery_days" type="number" min="1" max="90" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Révisions incluses</flux:label>
                                <flux:input wire:model="packages.{{ $i }}.revision_count" type="number" min="0" max="20" />
                            </flux:field>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">
                    Sauvegarder les packages
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Tab : Publication --}}
    <div x-show="tab === 'publish'" class="mt-6">
        <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <h2 class="text-xl font-black text-zinc-950 dark:text-white">Soumettre pour publication</h2>
            <p class="mt-3 text-sm leading-7 text-zinc-500 dark:text-zinc-400">
                Une fois soumis, votre service sera examiné par l'équipe AfriTask avant d'être publié.
                Assurez-vous d'avoir rempli toutes les informations et ajouté au moins un package.
            </p>

            <div class="mt-6 grid gap-3">
                @php
                    $checks = [
                        ['ok' => strlen($gig->title ?? '') >= 12, 'label' => 'Titre (12 caractères min.)'],
                        ['ok' => strlen($gig->description ?? '') >= 80, 'label' => 'Description (80 caractères min.)'],
                        ['ok' => $gig->packages->isNotEmpty(), 'label' => 'Au moins un package créé'],
                        ['ok' => $gig->category_id !== null, 'label' => 'Catégorie sélectionnée'],
                    ];
                @endphp
                @foreach ($checks as $check)
                    <div class="flex items-center gap-3 rounded-2xl border {{ $check['ok'] ? 'border-emerald-200 bg-emerald-50' : 'border-zinc-200 bg-stone-50' }} px-4 py-3">
                        <div class="size-5 shrink-0 rounded-full flex items-center justify-center {{ $check['ok'] ? 'bg-emerald-500' : 'bg-zinc-300' }}">
                            @if ($check['ok'])
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 text-white" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-sm {{ $check['ok'] ? 'font-medium text-emerald-800' : 'text-zinc-500' }}">
                            {{ $check['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            @if ($gig->status->value === 'draft')
                @php $allPassed = collect($checks)->every(fn($c) => $c['ok']); @endphp
                @if ($allPassed)
                    <button wire:click="publish" wire:confirm="Soumettre ce service pour validation ?"
                            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-zinc-950 px-6 py-4 text-sm font-semibold text-white transition hover:bg-teal-700">
                        Soumettre pour publication
                    </button>
                @else
                    <p class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        Complétez les points ci-dessus avant de soumettre.
                    </p>
                @endif
            @elseif ($gig->status->value === 'pending')
                <p class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Votre service est en cours de révision par notre équipe.
                </p>
            @elseif ($gig->status->value === 'published')
                <p class="mt-6 flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Ce service est publié et visible par les clients.
                </p>
            @elseif ($gig->status->value === 'rejected')
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <strong>Service rejeté :</strong> {{ $gig->rejection_reason ?? 'Contactez le support pour plus d\'informations.' }}
                </div>
            @endif
        </div>
    </div>
</div>
