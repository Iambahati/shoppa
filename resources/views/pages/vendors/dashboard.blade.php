<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">
                {{ $vendor?->name ?? $user->name }}
            </h2>
            <p class="mt-1 text-sm text-stone-500">Seller dashboard</p>
        </div>
        <a href="{{ route('vendor.listings.create') }}">
            <x-ui.button size="sm">
                <x-nav.icon name="layers" class="h-4 w-4" />
                Add listing
            </x-ui.button>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
        <x-card.stat-card
            label="Active listings"
            :value="(string) $stats['active_listings']"
            icon="layers"
            icon-color="emerald"
        />
        <x-card.stat-card
            label="Awaiting verification"
            :value="(string) $stats['pending_listings']"
            icon="shield"
            icon-color="amber"
        />
        <x-card.stat-card
            label="Total sales"
            :value="(string) $stats['total_sales']"
            icon="box"
            icon-color="blue"
        />
        <x-card.stat-card
            label="Balance (KSh)"
            :value="number_format((float) $stats['balance_ksh'], 2)"
            icon="store"
            icon-color="purple"
        />
    </div>

    {{-- Verification callout — shown until vendor has verified listings --}}
    @if($stats['active_listings'] === 0)
        <div class="mb-8 rounded-xl border border-amber-200 bg-amber-50 p-5 flex items-start gap-4">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                <x-nav.icon name="shield" class="h-5 w-5" />
            </span>
            <div>
                <p class="text-sm font-medium text-amber-900">Get your first device verified</p>
                <p class="mt-0.5 text-sm text-amber-700">
                    List a device and send it to the Shoppa verification centre. Once certified,
                    it becomes visible to buyers across the platform.
                </p>
                <a href="{{ route('vendor.listings.create') }}" class="mt-2 inline-block">
                    <x-ui.button size="sm" variant="secondary">Create your first listing →</x-ui.button>
                </a>
            </div>
        </div>
    @endif

    {{-- Recent listings --}}
    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-stone-900">Recent listings</h3>
            <a href="{{ route('vendor.listings.index') }}"
               class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                View all
            </a>
        </div>

        @if($recentListings->isEmpty())
            <div class="px-5 py-12 text-center">
                <x-nav.icon name="layers" class="mx-auto h-8 w-8 text-stone-300" />
                <p class="mt-3 text-sm text-stone-500">No listings yet.</p>
                <p class="text-xs text-stone-400 mt-1">
                    Add a device and request verification to start selling.
                </p>
            </div>
        @else
            <ul role="list" class="divide-y divide-stone-100">
                @foreach($recentListings as $listing)
                    <li class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-stone-50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-stone-900">{{ $listing->name }}</p>
                            <p class="text-xs text-stone-400 mt-0.5">
                                KSh {{ number_format($listing->price, 2) }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-trust.cert-badge :status="$listing->verification_status ?? 'unverified'" />
                            <a href="{{ route('vendor.listings.show', $listing) }}"
                               class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                                Edit →
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>