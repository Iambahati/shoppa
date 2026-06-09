<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
                {{ $vendor?->name ?? auth()->user()->name }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">Seller overview &mdash; {{ now()->format('d F Y') }}</p>
        </div>
        <a href="{{ route('vendor.listings.create') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-sky-400 hover:shadow-lg hover:shadow-sky-500/20">
            <x-nav-icon name="layers" class="h-4 w-4" />
            Add listing
        </a>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Active listings"
            :value="(string) $stats['active_listings']"
            icon="layers"
            icon-color="emerald"
            trend="+2 this week"
            trend-dir="up"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Awaiting verification"
            :value="(string) $stats['pending_listings']"
            icon="shield"
            icon-color="amber"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Orders to fulfil"
            :value="(string) $stats['orders_to_fulfil']"
            icon="box"
            icon-color="blue"
            trend="–3 since yesterday"
            trend-dir="down"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Total earned (KSh)"
            :value="'KSh '.number_format($stats['total_earned_ksh'])"
            icon="store"
            icon-color="purple"
            trend="+12.4% this month"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── VERIFICATION UPSELL ──────────────────────────────────────────── --}}
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

    {{-- ── REVENUE TREND: 7-day bar chart ─────────────────────────────── --}}
    <div class="mb-8 overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-white">Revenue trend</h3>
                <p class="mt-0.5 text-xs text-slate-400">Last 7 days (KSh)</p>
            </div>
            <p class="text-lg font-bold tabular-nums text-white">
                KSh {{ number_format(array_sum($chartData)) }}
            </p>
        </div>
        <div class="px-6 py-5">
            @php
                $days7  = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $maxRev = max($chartData) ?: 1;
            @endphp
            <div class="flex gap-2" style="height: 64px; align-items: stretch;">
                @foreach($chartData as $i => $rev)
                    @php $barPx = max(round(($rev / $maxRev) * 56), 4); @endphp
                    <div class="relative flex-1">
                        <div class="absolute bottom-0 w-full rounded-t-sm transition-all duration-700"
                             style="height: {{ $barPx }}px; background-color: {{ $i === count($chartData) - 1 ? '#38bdf8' : 'rgba(56,189,248,0.25)' }};"></div>
                        <span class="absolute -bottom-5 left-0 right-0 text-center text-xs text-slate-500">{{ $days7[$i] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-7"></div>
        </div>
    </div>

    {{-- ── TWO-COLUMN: Inventory breakdown + Recent listings ───────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Inventory breakdown --}}
        <div class="rounded-2xl bg-slate-800 ring-1 ring-white/5 px-6 py-5">
            <h3 class="mb-5 text-sm font-semibold text-white">Inventory breakdown</h3>
            @php $breakdownTotal = array_sum($listingBreakdown); @endphp
            <div class="space-y-4">
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-emerald-400">Live &amp; verified</span>
                        <span class="tabular-nums text-slate-300">{{ $listingBreakdown['active'] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-emerald-500 transition-all duration-700"
                             style="width: {{ $breakdownTotal > 0 ? round(($listingBreakdown['active'] / $breakdownTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-amber-400">Pending verification</span>
                        <span class="tabular-nums text-slate-300">{{ $listingBreakdown['pending'] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-amber-400 transition-all duration-700"
                             style="width: {{ $breakdownTotal > 0 ? round(($listingBreakdown['pending'] / $breakdownTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-red-400">Rejected</span>
                        <span class="tabular-nums text-slate-300">{{ $listingBreakdown['rejected'] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-red-400/70 transition-all duration-700"
                             style="width: {{ $breakdownTotal > 0 ? round(($listingBreakdown['rejected'] / $breakdownTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            <p class="mt-5 border-t border-white/5 pt-4 text-xs text-slate-500">
                {{ $breakdownTotal }} total devices listed
            </p>
        </div>

        {{-- Recent listings --}}
        <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5 lg:col-span-2">
            <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
                <h3 class="text-sm font-semibold text-white">Recent listings</h3>
                <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">Manage all</a>
            </div>
            <ul role="list" class="divide-y divide-white/5">
                @foreach($recentListings as $listing)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $listing['name'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">KSh {{ number_format($listing['price']) }} &bull; {{ $listing['age'] }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <x-trust-cert-badge :status="$listing['status']" />
                            <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</x-layouts.app>
