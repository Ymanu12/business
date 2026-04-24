<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <section class="overflow-hidden rounded-[2.2rem] border border-white/70 bg-[linear-gradient(135deg,_#111827_0%,_#0f766e_42%,_#f59e0b_140%)] px-6 py-8 text-white shadow-2xl shadow-zinc-900/15 lg:px-8 lg:py-10 dark:border-white/15">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <div class="inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-50">
                    Onboarding freelance
                </div>
                <h1 class="mt-5 text-4xl font-black leading-none tracking-tight sm:text-5xl">
                    Construisez un profil qui donne envie de commander.
                </h1>
                <p class="mt-5 max-w-2xl text-sm leading-7 text-zinc-100/85 sm:text-base">
                    En quelques champs bien choisis, vous posez votre positionnement, vos competences et vos liens de confiance avant de publier votre premier service.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    @foreach ([
                        ['label' => 'Positionnement', 'text' => 'Une promesse courte et nette.'],
                        ['label' => 'Competences', 'text' => 'Les mots-clés que les clients cherchent.'],
                        ['label' => 'Credibilite', 'text' => 'Portfolio et profils publics.'],
                    ] as $item)
                        <article class="rounded-[1.5rem] border border-white/12 bg-white/10 p-4 backdrop-blur">
                            <div class="text-sm font-semibold">{{ $item['label'] }}</div>
                            <p class="mt-2 text-xs leading-6 text-zinc-100/75">{{ $item['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[1.9rem] border border-white/12 bg-zinc-950/35 p-6 backdrop-blur">
                <div class="text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-100">Conseils rapides</div>
                <div class="mt-4 grid gap-3">
                    @foreach ([
                        'Utilisez une tagline qui parle du resultat client, pas seulement du metier.',
                        'Listez 4 a 8 competences claires separees par des virgules.',
                        'Ajoutez au moins un lien externe pour inspirer confiance.',
                    ] as $tip)
                        <div class="rounded-2xl bg-white/10 px-4 py-4 text-sm leading-7 text-zinc-100/80">
                            {{ $tip }}
                        </div>
                    @endforeach
                </div>

                @if ($adminContact)
                    <div class="mt-5 rounded-[1.5rem] border border-white/12 bg-white/10 p-4">
                        <div class="text-sm font-semibold text-white">Un blocage pendant la mise en place ?</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-100/75">
                            Ouvrez directement une discussion avec {{ $adminContact->name }} pour poser vos questions avant de publier votre premier service.
                        </p>
                        <button
                            type="button"
                            wire:click="openAdminChat"
                            wire:loading.attr="disabled"
                            class="mt-4 inline-flex min-h-11 items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-zinc-950 transition hover:bg-teal-50 disabled:opacity-60"
                        >
                            Discuter avec l'admin
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="mt-8 rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
        <div class="max-w-3xl">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-700">Votre profil vendeur</p>
            <h2 class="mt-2 text-3xl font-black text-zinc-950 dark:text-white">Finalisez votre base avant de creer un service.</h2>
        </div>

        <form wire:submit="save" class="mt-8 grid gap-6">
            <flux:field>
                <flux:label>Tagline</flux:label>
                <flux:input wire:model="tagline" placeholder="Ex: Je cree des landing pages qui convertissent pour les startups fintech." />
                <flux:error name="tagline" />
            </flux:field>

            <div class="grid gap-6 lg:grid-cols-2">
                <flux:field>
                    <flux:label>Competences</flux:label>
                    <flux:textarea wire:model="skillsInput" rows="5" placeholder="UI design, Figma, Landing page, Conversion, Branding"></flux:textarea>
                    <flux:error name="skillsInput" />
                </flux:field>

                <flux:field>
                    <flux:label>Langues</flux:label>
                    <flux:textarea wire:model="languagesInput" rows="5" placeholder="Francais, Anglais"></flux:textarea>
                    <flux:error name="languagesInput" />
                </flux:field>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <flux:field>
                    <flux:label>Disponibilite</flux:label>
                    <flux:select wire:model="availability">
                        <option value="available">Disponible</option>
                        <option value="busy">Occupe</option>
                        <option value="unavailable">Indisponible</option>
                    </flux:select>
                    <flux:error name="availability" />
                </flux:field>

                <div class="rounded-[1.5rem] border border-zinc-200 bg-stone-50 px-5 py-4 dark:bg-zinc-900 dark:border-zinc-700">
                    <div class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Et ensuite ?</div>
                    <p class="mt-2 text-sm leading-7 text-zinc-500 dark:text-zinc-400">
                        Une fois ce profil enregistre, vous serez envoye vers la creation de votre premier service.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <flux:field>
                    <flux:label>Portfolio</flux:label>
                    <flux:input wire:model="portfolioUrl" placeholder="https://votreportfolio.com" />
                    <flux:error name="portfolioUrl" />
                </flux:field>

                <flux:field>
                    <flux:label>LinkedIn</flux:label>
                    <flux:input wire:model="linkedinUrl" placeholder="https://linkedin.com/in/vous" />
                    <flux:error name="linkedinUrl" />
                </flux:field>

                <flux:field>
                    <flux:label>GitHub</flux:label>
                    <flux:input wire:model="githubUrl" placeholder="https://github.com/vous" />
                    <flux:error name="githubUrl" />
                </flux:field>
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                    Plus tard
                </a>
                <flux:button type="submit" variant="primary" class="sm:min-w-52">
                    Enregistrer et creer mon service
                </flux:button>
            </div>
        </form>
    </section>
</div>
