<x-layouts.guest>
    <x-slot:heading>Reset your password</x-slot:heading>
    <x-slot:subheading>We'll send a reset link to your email.</x-slot:subheading>

    @if(session('status'))
    <x-ui-alert type="success" class="mb-6">{{ session('status') }}</x-ui-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <x-form-field name="email" label="Email address" type="email"
            placeholder="you@example.com" :required="true" autocomplete="email" />
        <x-ui-button type="submit" class="w-full justify-center">Send reset link</x-ui-button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        <a href="{{ route('login') }}" class="font-medium text-sky-500 transition-colors hover:text-sky-600">← Back to sign in</a>
    </p>
</x-layouts.guest>
