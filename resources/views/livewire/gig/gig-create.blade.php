<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <section class="overflow-hidden rounded-[2.2rem] border border-white/70 bg-[linear-gradient(135deg,_#111827_0%,_#0f766e_42%,_#f59e0b_145%)] px-6 py-8 text-white shadow-2xl shadow-zinc-900/15 lg:px-8 lg:py-10 dark:border-white/15">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <div class="inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-50">
                    Creation de service
                </div>
                <h1 class="mt-5 text-4xl font-black leading-none tracking-tight sm:text-5xl">
                    Votre premier gig est deja amorce avec votre onboarding.
                </h1>
                <p class="mt-5 max-w-2xl text-sm leading-7 text-zinc-100/85 sm:text-base">
                    Le titre, la description et les competences reprennent votre positionnement freelance pour vous faire gagner du temps et garder une ligne claire.
                </p>
            </div>

            <div class="rounded-[1.9rem] border border-white/12 bg-zinc-950/35 p-6 backdrop-blur">
                <div class="text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-100">Ce qui sera cree</div>
                <div class="mt-4 grid gap-3">
                    @foreach ([
                        'Un brouillon de service avec un titre exploitable.',
                        'Une description initiale basee sur votre onboarding.',
                        'Des tags derives de vos competences pour accelerer la suite.',
                    ] as $item)
                        <div class="rounded-2xl bg-white/10 px-4 py-4 text-sm leading-7 text-zinc-100/80">
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">
        <div class="max-w-3xl">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-teal-700">Brouillon initial</p>
            <h2 class="mt-2 text-3xl font-black text-zinc-950 dark:text-white">Affinez votre offre avant publication.</h2>
        </div>

        <form wire:submit="saveGig" class="mt-8 grid gap-6">
            <flux:field>
                <flux:label>Titre du service</flux:label>
                <flux:input wire:model="title" placeholder="Ex: Je vais concevoir une landing page qui convertit" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Description</flux:label>
                <flux:textarea wire:model="description" rows="9" placeholder="Expliquez clairement ce que le client recevra, pour qui et dans quel delai."></flux:textarea>
                <flux:error name="description" />
            </flux:field>

            <div class="grid gap-6 lg:grid-cols-2">
                <flux:field>
                    <flux:label>Categorie</flux:label>
                    <flux:select wire:model="categoryId">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="categoryId" />
                </flux:field>

                <flux:field>
                    <flux:label>Tags / competences</flux:label>
                    <flux:input wire:model="tagsInput" placeholder="UI design, Figma, UX writing" />
                    <flux:error name="tagsInput" />
                </flux:field>
            </div>

            <div class="grid gap-6 lg:grid-cols-4">
                <flux:field>
                    <flux:label>Prix de depart</flux:label>
                    <flux:input wire:model="startingPrice" type="number" min="5000" step="1000" />
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
                    <flux:label>Delai (jours)</flux:label>
                    <flux:input wire:model="deliveryDays" type="number" min="1" max="90" />
                    <flux:error name="deliveryDays" />
                </flux:field>

                <flux:field>
                    <flux:label>Revisions</flux:label>
                    <flux:input wire:model="revisionCount" type="number" min="0" max="20" />
                    <flux:error name="revisionCount" />
                </flux:field>
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:justify-end">
                <a href="{{ route('seller.gigs.index') }}" class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                    Retour
                </a>
                <flux:button type="submit" variant="primary" class="sm:min-w-52">
                    Creer le brouillon
                </flux:button>
            </div>
        </form>
    </section>
</div>
