<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8"
     x-data="{ tab: 'info' }"
     x-on:scroll-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })">

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

    @if ($successMessage)
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
            ✓ {{ $successMessage }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="mt-6 flex gap-1 rounded-2xl border border-zinc-200 bg-stone-50 p-1 dark:bg-zinc-900 dark:border-zinc-700">
        @foreach (['info' => 'Informations', 'packages' => 'Packages', 'media' => 'Médias', 'lessons' => 'Leçons', 'quiz' => 'Quiz', 'publish' => 'Publication'] as $key => $label)
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

    {{-- Tab : Médias --}}
    <div x-show="tab === 'media'" class="mt-6">
        <form wire:submit="saveMedia" class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">

            {{-- Miniature --}}
            <h2 class="text-lg font-black text-zinc-950 dark:text-white">Miniature du service</h2>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Image principale affichée dans les résultats de recherche (JPG/PNG/WebP, max 5 Mo)</p>

            @if ($gig->thumbnail)
                <div class="mt-3">
                    <img src="{{ $gig->thumbnailUrl() }}" alt="Miniature actuelle"
                         class="h-32 w-56 rounded-2xl object-cover border border-zinc-200 dark:border-zinc-700">
                    <p class="mt-1 text-xs text-zinc-400">Miniature actuelle — elle sera remplacée si vous en choisissez une nouvelle.</p>
                </div>
            @endif

            <div class="mt-3">
                <flux:field>
                    <flux:label>Choisir une miniature</flux:label>
                    <input type="file" wire:model="thumbnailUpload" accept="image/*"
                           class="mt-1 block w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 file:mr-3 file:cursor-pointer file:rounded-full file:border-0 file:bg-teal-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-teal-700 dark:bg-zinc-800/80 dark:border-zinc-700 dark:text-zinc-300">
                    <flux:error name="thumbnailUpload" />
                </flux:field>
                @if ($thumbnailUpload)
                    <p class="mt-2 text-xs text-teal-700 dark:text-teal-400">Aperçu avant enregistrement :</p>
                    <img src="{{ $thumbnailUpload->temporaryUrl() }}" alt="Aperçu"
                         class="mt-1 h-32 w-56 rounded-2xl object-cover border border-teal-300 dark:border-teal-600">
                @endif
            </div>

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Vidéo --}}
            <h2 class="text-lg font-black text-zinc-950 dark:text-white">Vidéo de présentation</h2>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Lien YouTube ou Vimeo pour présenter votre service (optionnel)</p>

            <div class="mt-3">
                <flux:field>
                    <flux:label>URL de la vidéo</flux:label>
                    <flux:input wire:model="videoUrl" type="url" placeholder="https://www.youtube.com/watch?v=..." />
                    <flux:error name="videoUrl" />
                </flux:field>
            </div>

            @if ($gig->video_url)
                <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">Vidéo actuelle : <a href="{{ $gig->video_url }}" target="_blank" class="text-teal-600 underline">{{ Str::limit($gig->video_url, 60) }}</a></p>
            @endif

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Galerie d'images --}}
            <h2 class="text-lg font-black text-zinc-950 dark:text-white">Galerie d'images</h2>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Images supplémentaires montrant votre travail (JPG/PNG/WebP, max 5 Mo par image)</p>

            @if ($gig->gallery->where('type', 'image')->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-3">
                    @foreach ($gig->gallery->where('type', 'image') as $item)
                        <div class="group relative">
                            <img src="{{ $item->url() }}" alt=""
                                 class="h-28 w-44 rounded-2xl object-cover border border-zinc-200 dark:border-zinc-700">
                            <button wire:click="removeGalleryItem({{ $item->id }})" wire:confirm="Supprimer cette image de la galerie ?"
                                    type="button"
                                    class="absolute -right-2 -top-2 flex size-6 items-center justify-center rounded-full bg-rose-500 text-white opacity-0 transition group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4">
                <flux:field>
                    <flux:label>Ajouter des images à la galerie</flux:label>
                    <input type="file" wire:model="newGalleryImages" accept="image/*" multiple
                           class="mt-1 block w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 file:mr-3 file:cursor-pointer file:rounded-full file:border-0 file:bg-teal-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-teal-700 dark:bg-zinc-800/80 dark:border-zinc-700 dark:text-zinc-300">
                    <flux:error name="newGalleryImages" />
                    <flux:error name="newGalleryImages.*" />
                </flux:field>
                @if (!empty($newGalleryImages))
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($newGalleryImages as $img)
                            <img src="{{ $img->temporaryUrl() }}" alt=""
                                 class="h-20 w-32 rounded-xl object-cover border border-teal-300 dark:border-teal-600">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="my-6 h-px bg-zinc-100 dark:bg-zinc-700"></div>

            {{-- Galerie de vidéos --}}
            <h2 class="text-lg font-black text-zinc-950 dark:text-white">Vidéos de la galerie</h2>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Vidéos de démonstration de votre service (MP4/MOV/WebM, max 200 Mo par vidéo, 5 vidéos max)</p>

            @if ($gig->gallery->where('type', 'video')->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-3">
                    @foreach ($gig->gallery->where('type', 'video') as $item)
                        <div class="group relative">
                            <video src="{{ $item->url() }}"
                                   class="h-28 w-44 rounded-2xl object-cover border border-zinc-200 dark:border-zinc-700 bg-zinc-900"
                                   muted></video>
                            <div class="pointer-events-none absolute inset-0 flex items-center justify-center rounded-2xl bg-black/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-white/80" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                            <button wire:click="removeGalleryItem({{ $item->id }})" wire:confirm="Supprimer cette vidéo de la galerie ?"
                                    type="button"
                                    class="absolute -right-2 -top-2 flex size-6 items-center justify-center rounded-full bg-rose-500 text-white opacity-0 transition group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4">
                <flux:field>
                    <flux:label>Ajouter des vidéos à la galerie</flux:label>
                    <input type="file" wire:model="newGalleryVideos" accept="video/mp4,video/quicktime,video/webm,video/x-msvideo" multiple
                           class="mt-1 block w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 file:mr-3 file:cursor-pointer file:rounded-full file:border-0 file:bg-teal-600 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white hover:file:bg-teal-700 dark:bg-zinc-800/80 dark:border-zinc-700 dark:text-zinc-300">
                    <flux:error name="newGalleryVideos" />
                    <flux:error name="newGalleryVideos.*" />
                </flux:field>
                @if (!empty($newGalleryVideos))
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($newGalleryVideos as $vid)
                            <div class="relative h-20 w-32 overflow-hidden rounded-xl border border-teal-300 bg-zinc-900 dark:border-teal-600">
                                <video src="{{ $vid->temporaryUrl() }}"
                                       class="h-full w-full object-cover" muted></video>
                                <div class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/25">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white/80" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <flux:button type="submit" variant="primary">
                    Enregistrer les médias
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Tab : Leçons --}}
    <div x-show="tab === 'lessons'" class="mt-6">
        <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <h2 class="text-xl font-black text-zinc-950 dark:text-white">Contenu pédagogique</h2>
            <p class="mt-2 text-sm leading-7 text-zinc-500 dark:text-zinc-400">
                Ajoutez les leçons de votre formation. Les clients y auront accès après paiement.
                Vous pouvez marquer certaines leçons comme "Aperçu libre" pour donner un avant-goût.
            </p>
            <div class="mt-6">
                <livewire:gig.lesson-manager :gig="$gig" />
            </div>
        </div>
    </div>

    {{-- Tab : Quiz --}}
    <div x-show="tab === 'quiz'" class="mt-6">
        <div class="rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <h2 class="text-xl font-black text-zinc-950 dark:text-white">Quiz de fin de formation</h2>
            <p class="mt-2 text-sm leading-7 text-zinc-500 dark:text-zinc-400">
                Créez un quiz que les clients devront passer après avoir suivi vos leçons.
                Les questions QCM sont corrigées automatiquement. Les questions texte nécessitent une correction manuelle.
            </p>
            <div class="mt-6">
                <livewire:quiz.quiz-builder :gig="$gig" />
            </div>
        </div>
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
