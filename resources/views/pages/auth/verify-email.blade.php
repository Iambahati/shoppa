<x-layouts.guest>
    <x-slot:heading>Verify your email</x-slot:heading>

    @if(session('status') === 'verification-link-sent')
    <x-ui-alert type="success" class="mb-6">A new verification link has been sent.</x-ui-alert>
    @endif

    <div class="text-center space-y-6">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-sky-50 ring-1 ring-sky-100">
            <svg class="h-8 w-8 text-sky-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <div class="space-y-2">
            <p class="text-sm text-stone-600">
                We sent a verification link to <strong class="text-stone-900">{{ auth()->user()->email }}</strong>.
            </p>
            <p class="text-sm text-stone-500">Check your inbox and click the link to activate your account.</p>
        </div>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui-button type="submit" variant="secondary" class="w-full justify-center">
                Resend verification email
            </x-ui-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-stone-400 hover:text-stone-600 transition-colors underline underline-offset-2">
                Sign out and use a different account
            </button>
        </form>
    </div>
</x-layouts.guest>