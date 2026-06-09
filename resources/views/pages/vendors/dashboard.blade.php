<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <p class="section-label mb-1.5">{{ now()->format('l, d F Y') }}</p>
            <h1 class="text-xl font-semibold text-white tracking-tight">
                {{ $vendor?->name ?? auth()->user()->name }}
            </h1>
            <p class="mt-0.5 text-[13px] text-slate-500">Seller overview</p>
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
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Total earned (KSh)"
            :value="'KSh '.number_format($stats['total_earned_ksh'])"
            icon="store"
            icon-color="purple"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── VERIFICATION NOTICE ──────────────────────────────────────────── --}}
    <div class="mb-8">
        <div class="notice-strip border-amber-400/40">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-400"></span>
            <p class="text-slate-300">
                Trust Certificates boost sales — KSh {{ number_format(config('shoppa.verification.fee_min_ksh')) }}–{{ number_format(config('shoppa.verification.fee_max_ksh')) }} per device
            </p>
            <a href="{{ route('vendor.listings.create') }}" class="ml-auto shrink-0 text-xs font-medium text-amber-400 transition-colors hover:text-amber-300">
                Submit for inspection →
            </a>
        </div>
    </div>

    {{-- ── REVENUE TREND: 7-day bar chart ─────────────────────────────── --}}
    <div class="mb-8 overflow-hidden rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06]">
        <div class="flex items-center justify-between border-b border-white/[0.05] px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-white">Revenue trend</h3>
                <p class="mt-0.5 text-xs text-slate-400">Last 7 days (KSh)</p>
            </div>
            <p class="text-lg font-bold tabular-nums text-white">
                KSh {{ number_format(array_sum($chartData)) }}
            </p>
        </div>
        @if(array_sum($chartData) > 0)
            <div class="px-6 py-5">
                @php
                    $days7  = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('D'))->toArray();
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
        @else
            <div class="flex flex-col items-center justify-center px-6 py-10 text-center">
                <svg class="h-10 w-full opacity-20" viewBox="0 0 300 40" preserveAspectRatio="none" aria-hidden="true">
                    <line x1="0" y1="20" x2="300" y2="20" stroke="#38bdf8" stroke-width="1" stroke-dasharray="4,4"/>
                </svg>
                <p class="mt-3 text-xs text-slate-500">Revenue data will appear as you make sales</p>
            </div>
        @endif
    </div>

    {{-- ── TWO-COLUMN: Inventory breakdown + Recent listings ───────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Inventory breakdown --}}
        <div class="rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06] px-6 py-5">
            <h3 class="mb-5 text-sm font-semibold text-white">Inventory breakdown</h3>
            @php $breakdownTotal = array_sum($listingBreakdown); @endphp
            @if($breakdownTotal > 0)
                <div class="space-y-4">
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-emerald-400">Live &amp; verified</span>
                            <span class="tabular-nums text-slate-300">{{ $listingBreakdown['active'] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-white/[0.06]">
                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-emerald-500 transition-all duration-700"
                                 style="width: {{ round(($listingBreakdown['active'] / $breakdownTotal) * 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-amber-400">Pending verification</span>
                            <span class="tabular-nums text-slate-300">{{ $listingBreakdown['pending'] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-white/[0.06]">
                            <div class="h-full rounded-full bg-amber-400 transition-all duration-700"
                                 style="width: {{ round(($listingBreakdown['pending'] / $breakdownTotal) * 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-red-400">Rejected</span>
                            <span class="tabular-nums text-slate-300">{{ $listingBreakdown['rejected'] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-white/[0.06]">
                            <div class="h-full rounded-full bg-red-400/70 transition-all duration-700"
                                 style="width: {{ round(($listingBreakdown['rejected'] / $breakdownTotal) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-5 border-t border-white/5 pt-4 text-xs text-slate-500">
                    {{ $breakdownTotal }} total devices listed
                </p>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg class="h-10 w-10 text-slate-700" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <rect x="4" y="4" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="22" y="4" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="4" y="22" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="22" y="22" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 2"/>
                    </svg>
                    <p class="mt-3 text-xs text-slate-500">No listings yet</p>
                </div>
            @endif
        </div>

        {{-- Recent listings --}}
        <div class="overflow-hidden rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06] lg:col-span-2">
            <div class="flex items-center justify-between border-b border-white/[0.05] px-6 py-4">
                <h3 class="text-sm font-semibold text-white">Recent listings</h3>
                <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">Manage all</a>
            </div>
            @if($recentListings->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-14 text-center">
                    <svg class="h-16 w-16 text-slate-700" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="8" y="16" width="48" height="36" rx="5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M20 16V12a4 4 0 0 1 4-4h16a4 4 0 0 1 4 4v4" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="24" y1="32" x2="40" y2="32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="24" y1="40" x2="34" y2="40" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="49" cy="47" r="8" fill="#0f172a" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="49" y1="43" x2="49" y2="51" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="45" y1="47" x2="53" y2="47" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <p class="mt-4 text-sm font-semibold text-slate-300">No listings yet</p>
                    <p class="mt-1 text-xs text-slate-500">Add your first device listing to get started</p>
                    <a href="{{ route('vendor.listings.create') }}"
                       class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-500/15 px-4 py-2 text-sm font-medium text-sky-400
                              ring-1 ring-sky-500/25 transition-all hover:bg-sky-500/25 hover:text-sky-300">
                        <x-nav-icon name="layers" class="h-4 w-4" />
                        Add first listing
                    </a>
                </div>
            @else
                <ul role="list" class="divide-y divide-white/[0.04]">
                    @foreach($recentListings as $listing)
                        <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/[0.03]">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-white">{{ $listing->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">KSh {{ number_format($listing->price) }} &bull; {{ $listing->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-3">
                                <x-trust-cert-badge :status="$listing->verification_status" />
                                <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">View →</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</x-layouts.app>
