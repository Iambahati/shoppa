{{-- DS register: heading, subheading, trust note panel, full-width primary --}}
<x-layouts.guest>
    <x-slot:heading>Create your account</x-slot:heading>
    <x-slot:subheading>Buy and sell verified electronics with confidence</x-slot:subheading>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf

        <x-form-field name="name" label="Full name" type="text" placeholder="Jane Wanjiru" :required="true" autocomplete="name" />
        <x-form-field name="email" label="Email address" type="email" placeholder="jane@example.com" :required="true" autocomplete="email" />
        {{-- DS phone placeholder: +254 7XX XXX XXX --}}
        <x-form-field name="phone" label="Phone number" type="tel" placeholder="+254 7XX XXX XXX"
            hint="Used for order updates and delivery coordination." autocomplete="tel" />

        <div class="space-y-1">
            <label for="password" class="block text-sm font-medium text-stone-700">
                Password <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                class="block w-full rounded-lg border-0 py-2 px-3 text-sm text-stone-900 ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600 @error('password') ring-red-400 @enderror" />
            @error('password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1">
            <label for="password_confirmation" class="block text-sm font-medium text-stone-700">
                Confirm password <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                class="block w-full rounded-lg border-0 py-2 px-3 text-sm text-stone-900 ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-emerald-600" />
        </div>

        {{-- DS trust note: stone-50 bg, stone-200 border, rounded-lg --}}
        <div class="flex items-start gap-3 rounded-lg border border-stone-200 bg-stone-50 px-4 py-3">
            <x-trust-verified-pill size="sm" class="mt-0.5 shrink-0" />
            <p class="text-xs leading-relaxed text-stone-500">
                Every device on Shoppa is physically inspected before listing. Your purchase is protected by escrow until you confirm delivery.
            </p>
        </div>

        <x-ui-button type="submit" class="w-full justify-center">Create account</x-ui-button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-emerald-600 transition-colors hover:text-emerald-700">Sign in</a>
    </p>
</x-layouts.guest>
