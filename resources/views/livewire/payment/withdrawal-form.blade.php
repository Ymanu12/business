<div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-700">Freelance</p>
        <h1 class="mt-1 text-3xl font-black text-zinc-950 dark:text-white">Demande de retrait</h1>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-[1.75rem] bg-[linear-gradient(135deg,_#0f172a,_#0f766e)] p-5 text-white shadow-xl">
            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-teal-100">Solde disponible</p>
            <div class="mt-3 text-3xl font-black">{{ $wallet->formattedBalance() }}</div>
        </div>
        <div class="rounded-[1.75rem] border border-zinc-200/80 bg-white/90 p-5 shadow-sm backdrop-blur dark:bg-zinc-800/90 dark:border-zinc-700/60">
            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-zinc-400">Retrait minimum</p>
            <div class="mt-3 text-2xl font-black text-zinc-950 dark:text-white">
                {{ number_format(config('afritask.min_withdrawal', 5000), 0, ',', ' ') }} {{ $wallet->currency }}
            </div>
        </div>
    </div>

    <form wire:submit="submit"
          class="mt-6 rounded-[2rem] border border-zinc-200/80 bg-white/90 p-6 shadow-xl shadow-zinc-900/5 backdrop-blur lg:p-8 dark:bg-zinc-800/90 dark:border-zinc-700/60">

        <flux:field>
            <flux:label>Montant à retirer ({{ $wallet->currency }})</flux:label>
            <flux:input wire:model="amount" type="number"
                        min="{{ config('afritask.min_withdrawal', 5000) }}"
                        max="{{ (int) $wallet->balance }}"
                        placeholder="{{ config('afritask.min_withdrawal', 5000) }}" />
            <flux:error name="amount" />
        </flux:field>

        <div class="mt-5">
            <flux:field>
                <flux:label>Méthode de retrait</flux:label>
                <flux:select wire:model="method">
                    <option value="mobile_money">Mobile Money (MTN, Orange, Moov…)</option>
                    <option value="bank_transfer">Virement bancaire</option>
                    <option value="paypal">PayPal</option>
                </flux:select>
                <flux:error name="method" />
            </flux:field>
        </div>

        <div class="mt-5">
            <flux:field>
                <flux:label>Coordonnées de paiement</flux:label>
                <flux:textarea wire:model="accountDetails" rows="3"
                               placeholder="Ex : +225 07 00 00 00 00 (MTN), ou IBAN/BIC pour virement…"></flux:textarea>
                <flux:error name="accountDetails" />
            </flux:field>
        </div>

        <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Le retrait sera traité sous 24 à 48 heures ouvrables après validation de l'équipe AfriTask.
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('wallet') }}" wire:navigate
               class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-teal-300 hover:text-teal-700 dark:text-zinc-300">
                Annuler
            </a>
            <flux:button type="submit" variant="primary" class="sm:min-w-44">
                Soumettre la demande
            </flux:button>
        </div>
    </form>
</div>
