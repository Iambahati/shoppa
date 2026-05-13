<x-layouts.guest>

    <x-slot:heading>Reset your password</x-slot:heading>
    <x-slot:subheading>We'll send a reset link to your email address.</x-slot:subheading>

    @if(session('status'))
        <x-ui.alert type="success" class="mb-6">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <x-form.field
            name="email"
            label="Email address"
            type="email"
            placeholder="you@example.com"
            :required="true"
            autocomplete="email"
        />

        <x-ui.button type="submit" class="w-full justify-center">
            Send reset link
        </x-ui.button>

    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        Remembered it?
        <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
            Back to sign in
        </a>
    </p>

</x-layouts.guest>