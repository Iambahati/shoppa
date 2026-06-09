<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">
                {{ $vendor?->name ?? auth()->user()->name }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">Seller overview &mdash; {{ now()->format('d F Y') }}</p>
        </div>
        <a href="{{ route('vendor.listings.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-sky-400">
            <x-nav-icon name="layers" class="h-4 w-4" />
            Add listing
        </a>
    </div>

    {{-- KPI tiles --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card label="Active listings"       :value="(string) $stats['active_listings']"         icon="layers" icon-color="emerald" />
        <x-card-stat-card label="Awaiting verification" :value="(string) $stats['pending_listings']"        icon="shield" icon-color="amber" />
        <x-card-stat-card label="Orders to fulfil"      :value="(string) $stats['orders_to_fulfil']"        icon="box"    icon-color="blue" />
        <x-card-stat-card label="Total earned (KSh)"    :value="number_format($stats['total_earned_ksh'])"  icon="store"  icon-color="purple" />
    </div>

    {{-- Verification upsell --}}
    <div class="mb-8 flex items-start gap-4 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-6">
        <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-400">
            <x-nav-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-sm font-semibold text-amber-300">Verification boosts your sales</p>
            <p class="mt-1 text-sm leading-relaxed text-amber-400">
                Devices with a Shoppa Trust Certificate sell faster and command better prices.
                Send your stock to our verification centre — we charge
                KSh {{ number_format(config('shoppa.verification.fee_min_ksh')) }}–{{ number_format(config('shoppa.verification.fee_max_ksh')) }} per device.
            </p>
        </div>
    </div>

    {{-- Recent listings --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Recent listings</h3>
            <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">Manage all</a>
        </div>

        @if($recentListings->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="layers" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">No listings yet.</p>
                <a href="{{ route('vendor.listings.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-sky-400 hover:text-sky-300">
                    Create your first listing →
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($recentListings as $listing)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $listing->name }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">KSh {{ number_format($listing->price) }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <x-trust-cert-badge :status="$listing->verification_status ?? 'unverified'" />
                            <a href="{{ route('vendor.listings.show', $listing) }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
