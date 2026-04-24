<x-layouts::auth :title="__('Register')">
    @php($selectedRole = old('role', request('role', 'client')))

    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <flux:field>
                <flux:label>{{ __('I want to join as') }}</flux:label>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2" x-data="{ role: '{{ $selectedRole }}' }">
                    <label
                        :class="role === 'client'
                            ? 'border-zinc-950 bg-zinc-950 text-white dark:border-teal-600 dark:bg-teal-700'
                            : 'border-zinc-200 bg-white text-zinc-700 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-zinc-200'"
                        class="cursor-pointer rounded-2xl border px-4 py-4 transition"
                    >
                        <input type="radio" name="role" value="client" class="sr-only"
                               x-model="role" @change="role = 'client'">
                        <div class="text-sm font-semibold">{{ __('Client') }}</div>
                        <div class="mt-1 text-xs" :class="role === 'client' ? 'text-zinc-300' : 'text-zinc-500 dark:text-zinc-400'">
                            {{ __('I want to buy services and manage projects.') }}
                        </div>
                    </label>

                    <label
                        :class="role === 'freelance'
                            ? 'border-teal-600 bg-teal-600 text-white'
                            : 'border-zinc-200 bg-white text-zinc-700 dark:bg-zinc-700/50 dark:border-zinc-600 dark:text-zinc-200'"
                        class="cursor-pointer rounded-2xl border px-4 py-4 transition"
                    >
                        <input type="radio" name="role" value="freelance" class="sr-only"
                               x-model="role" @change="role = 'freelance'">
                        <div class="text-sm font-semibold">{{ __('Freelancer') }}</div>
                        <div class="mt-1 text-xs" :class="role === 'freelance' ? 'text-teal-50' : 'text-zinc-500 dark:text-zinc-400'">
                            {{ __('I want to sell my services on AfriTask.') }}
                        </div>
                    </label>
                </div>

                <flux:error name="role" />
            </flux:field>

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
