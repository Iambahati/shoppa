{{-- DS login: heading "Welcome back", subheading, email+password, "Keep me signed in", full-width primary --}}
<x-layouts.guest>
    <x-slot:heading>Welcome back</x-slot:heading>
    <x-slot:subheading>Sign in to your Shoppa account</x-slot:subheading>

    @if(session('status'))
    <x-ui-alert type="success" class="mb-6">{{ session('status') }}</x-ui-alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        <x-form-field name="email" label="Email address" type="email"
            placeholder="you@example.com" :required="true" autocomplete="email" />

        <div class="space-y-1" x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-stone-700">
                    Password <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                @if(Route::has('password.request'))
                {{-- DS link: emerald-600, hover:emerald-700 --}}
                <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">
                    Forgot password?
                </a>
                @endif
            </div>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                    class="form-input pr-10 @error('password') ring-red-400 @enderror" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 transition-colors hover:text-stone-600" :aria-label="show ? 'Hide password' : 'Show password'">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            {{-- DS checkbox: emerald-600 accent --}}
            <input type="checkbox" id="remember" name="remember"
                class="h-4 w-4 rounded border-stone-300 text-emerald-600 focus:ring-emerald-600" />
            <label for="remember" class="text-sm text-stone-600">Keep me signed in</label>
        </div>

        <x-ui-button type="submit" class="w-full justify-center">Sign in</x-ui-button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-emerald-600 transition-colors hover:text-emerald-700">Create one free</a>
    </p>
</x-layouts.guest>
