<x-layouts.dashboard>
    <x-slot:title>Device inspection</x-slot:title>

    <div class="mx-auto max-w-3xl">

        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('verifier.queue') }}" class="text-sm text-stone-400 hover:text-stone-600 transition-colors flex items-center gap-1">
                <x-nav.icon name="chevron-r" class="h-4 w-4 rotate-180" />
                Back to queue
            </a>
        </div>

        <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-stone-900">Inspection report</h2>
                <x-trust.cert-badge status="pending" />
            </div>

            {{-- Sprint 4 will replace this placeholder --}}
            <x-ui.alert type="info">
                Full inspection form is implemented in Sprint 4 (Trust Engine).
                This page will capture IMEI check, hardware authenticity, condition grade, battery health, and photo uploads.
            </x-ui.alert>

            <div class="mt-6 flex items-center gap-3">
                <x-ui.button variant="secondary" size="sm" class="opacity-50 cursor-not-allowed" disabled>
                    Reject device
                </x-ui.button>
                <x-ui.button size="sm" class="opacity-50 cursor-not-allowed" disabled>
                    Issue Trust Certificate
                </x-ui.button>
            </div>
        </div>

    </div>

</x-layouts.dashboard>
