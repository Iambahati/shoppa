<x-layouts.guest>

    <x-slot:heading>Welcome back</x-slot:heading>
    <x-slot:subheading>Sign in to your Shoppa account</x-slot:subheading>

    {{-- Session status (e.g. password reset success) --}}
    @if(session('status'))
        <x-ui.alert type="success" class="mb-6">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        <x-form.field
            name="email"
            label="Email address"
            type="email"
            placeholder="you@example.com"
            :required="true"
            autocomplete="email"
        />

        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-stone-700">
                    Password <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600 @error('password') ring-red-400 @enderror"
            />
            @error('password')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="h-4 w-4 rounded border-stone-300 text-emerald-600 focus:ring-emerald-600"
            />
            <label for="remember" class="text-sm text-stone-600">Keep me signed in</label>
        </div>

        <x-ui.button type="submit" class="w-full justify-center">
            Sign in
        </x-ui.button>

    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
            Create one free
        </a>
    </p>

</x-layouts.guest>