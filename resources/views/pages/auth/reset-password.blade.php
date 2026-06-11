{{-- DS reset-password: stone-700 labels, emerald focus rings --}}
<x-layouts.guest>
    <x-slot:heading>Set new password</x-slot:heading>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') }}" />

        <x-form-field name="email" label="Email address" type="email"
            placeholder="you@example.com" :required="true" autocomplete="email" />

        <div class="space-y-1">
            <label for="password" class="block text-sm font-medium text-stone-700">
                New password <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                class="form-input @error('password') ring-red-400 @enderror" />
            @error('password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1">
            <label for="password_confirmation" class="block text-sm font-medium text-stone-700">
                Confirm new password <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                class="form-input" />
        </div>

        <x-ui-button type="submit" class="w-full justify-center">Update password</x-ui-button>
    </form>
</x-layouts.guest>
