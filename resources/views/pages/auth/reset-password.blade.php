<x-layouts.guest>

    <x-slot:heading>Set new password</x-slot:heading>
    <x-slot:subheading>Choose a strong password for your account.</x-slot:subheading>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ request()->route('token') }}" />

        <x-form.field
            name="email"
            label="Email address"
            type="email"
            placeholder="you@example.com"
            :required="true"
            autocomplete="email"
        />

        <div class="space-y-1">
            <label for="password" class="block text-sm font-medium text-stone-700">
                New password <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
                class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600 @error('password') ring-red-400 @enderror"
            />
            @error('password')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1">
            <label for="password_confirmation" class="block text-sm font-medium text-stone-700">
                Confirm new password <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600"
            />
        </div>

        <x-ui.button type="submit" class="w-full justify-center">
            Update password
        </x-ui.button>

    </form>

</x-layouts.guest>