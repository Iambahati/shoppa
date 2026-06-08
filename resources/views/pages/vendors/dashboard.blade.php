<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                {{ $vendor?->name ?? auth()->user()->name }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">Seller overview &mdash; {{ now()->format('d F Y') }}</p>
        </div>
        <a href="{{ route('vendor.listings.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600 transition-colors shadow-sm">
            <x-nav-icon name="layers" class="h-4 w-4" />
            Add listing
        </a>
    </div>

    {{-- KPI tiles --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Active listings"       :value="(string) $stats['active_listings']"     icon="layers" icon-color="emerald" />
        <x-card-stat-card label="Awaiting verification" :value="(string) $stats['pending_listings']"    icon="shield" icon-color="amber" />
        <x-card-stat-card label="Orders to fulfil"      :value="(string) $stats['orders_to_fulfil']"   icon="box"    icon-color="blue" />
        <x-card-stat-card label="Total earned (KSh)"    :value="number_format($stats['total_earned_ksh'])" icon="store" icon-color="purple" />
    </div>

    {{-- Verification upsell --}}
    <div class="mb-8 rounded-2xl border border-amber-200 bg-amber-50 p-6 flex items-start gap-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 mt-0.5">
            <x-nav-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-sm font-semibold text-amber-900">Verification boosts your sales</p>
            <p class="mt-1 text-sm text-amber-700 leading-relaxed">
                Devices with a Shoppa Trust Certificate sell faster and command better prices.
                Send your stock to our verification centre — we charge
                KSh {{ number_format(config('shoppa.verification.fee_min_ksh')) }}–{{ number_format(config('shoppa.verification.fee_max_ksh')) }} per device.
            </p>
        </div>
    </div>

    {{-- Recent listings --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent listings</h3>
            <a href="{{ route('vendor.listings.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">Manage all</a>
        </div>

        @if($recentListings->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="layers" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">No listings yet.</p>
                <a href="{{ route('vendor.listings.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm text-sky-600 font-medium hover:text-sky-700">
                    Create your first listing →
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($recentListings as $listing)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $listing->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">KSh {{ number_format($listing->price) }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-trust-cert-badge :status="$listing->verification_status ?? 'unverified'" />
                            <a href="{{ route('vendor.listings.show', $listing) }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
