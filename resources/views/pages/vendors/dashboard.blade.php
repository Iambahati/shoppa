{{-- DS vendor dashboard: header + emerald "Add listing" button, 4-stat grid, charts, listings --}}
<x-layouts.app>
    <x-slot:title>Seller dashboard</x-slot:title>

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-stone-900 tracking-tight">
                {{ $vendor?->name ?? auth()->user()->name }}
            </h1>
            <p class="mt-1 text-sm text-stone-500">{{ now()->format('l, d F Y') }}</p>
        </div>
        {{-- DS primary button: emerald-600, emerald-500 hover --}}
        <a href="{{ route('vendor.listings.create') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-emerald-500">
            <x-nav-icon name="layers" class="h-4 w-4" />
            Add listing
        </a>
    </div>

    {{-- ── KPI TILES --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Active listings"
            :value="(string) $stats['active_listings']"
            icon="layers"
            icon-color="emerald"
            style="animation-delay: 0ms"
        />
        {{-- DS: awaiting verification uses amber shield --}}
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
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── REVENUE TREND — white DS card --}}
    <div class="mb-8 card overflow-hidden">
        <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-stone-900">Revenue trend</h3>
                <p class="mt-0.5 text-xs text-stone-400">Last 7 days (KSh)</p>
            </div>
            <p class="text-lg font-semibold tabular-nums text-stone-900">
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
                                 style="height: {{ $barPx }}px; background-color: {{ $i === count($chartData) - 1 ? '#059669' : 'rgba(5,150,105,0.25)' }};"></div>
                            <span class="absolute -bottom-5 left-0 right-0 text-center text-xs text-stone-400">{{ $days7[$i] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-7"></div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center px-6 py-10 text-center">
                <p class="text-xs text-stone-400">Revenue data will appear as you make sales</p>
            </div>
        @endif
    </div>

    {{-- ── TWO-COLUMN: Inventory breakdown + Recent listings --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Inventory breakdown --}}
        <div class="card px-6 py-5">
            <h3 class="mb-5 text-sm font-semibold text-stone-900">Inventory breakdown</h3>
            @php $breakdownTotal = array_sum($listingBreakdown); @endphp
            @if($breakdownTotal > 0)
                <div class="space-y-4">
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-emerald-700">Live &amp; verified</span>
                            <span class="tabular-nums text-stone-600">{{ $listingBreakdown['active'] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-stone-100">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-700"
                                 style="width: {{ round(($listingBreakdown['active'] / $breakdownTotal) * 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-amber-700">Pending verification</span>
                            <span class="tabular-nums text-stone-600">{{ $listingBreakdown['pending'] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-stone-100">
                            <div class="h-full rounded-full bg-amber-400 transition-all duration-700"
                                 style="width: {{ round(($listingBreakdown['pending'] / $breakdownTotal) * 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-red-700">Rejected</span>
                            <span class="tabular-nums text-stone-600">{{ $listingBreakdown['rejected'] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-stone-100">
                            <div class="h-full rounded-full bg-red-400 transition-all duration-700"
                                 style="width: {{ round(($listingBreakdown['rejected'] / $breakdownTotal) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-5 border-t border-stone-100 pt-4 text-xs text-stone-400">
                    {{ $breakdownTotal }} total devices listed
                </p>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg class="h-10 w-10 text-stone-200" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <rect x="4" y="4" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="22" y="4" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="4" y="22" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5"/>
                        <rect x="22" y="22" width="14" height="14" rx="3" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 2"/>
                    </svg>
                    <p class="mt-3 text-xs text-stone-400">No listings yet</p>
                </div>
            @endif
        </div>

        {{-- Recent listings — white DS card with CertBadge per row --}}
        <div class="card overflow-hidden lg:col-span-2">
            <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-stone-900">Recent listings</h3>
                <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">Manage all →</a>
            </div>
            @if($recentListings->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-14 text-center">
                    <svg class="h-16 w-16 text-stone-200" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <rect x="8" y="16" width="48" height="36" rx="5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M20 16V12a4 4 0 0 1 4-4h16a4 4 0 0 1 4 4v4" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="24" y1="32" x2="40" y2="32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="24" y1="40" x2="34" y2="40" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="49" cy="47" r="8" fill="white" stroke="currentColor" stroke-width="1.5"/>
                        <line x1="49" y1="43" x2="49" y2="51" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="45" y1="47" x2="53" y2="47" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <p class="mt-4 text-sm font-semibold text-stone-600">No listings yet</p>
                    <p class="mt-1 text-xs text-stone-400">Add your first device listing to get started</p>
                    <a href="{{ route('vendor.listings.create') }}"
                       class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700
                              ring-1 ring-emerald-200 transition-all hover:bg-emerald-100">
                        <x-nav-icon name="layers" class="h-4 w-4" />
                        Add first listing
                    </a>
                </div>
            @else
                <ul role="list" class="divide-y divide-stone-100">
                    @foreach($recentListings as $listing)
                        <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-stone-50">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-stone-900">{{ $listing->name }}</p>
                                <p class="mt-0.5 text-xs text-stone-400">KSh {{ number_format($listing->price) }} &middot; {{ $listing->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-3">
                                <x-trust-cert-badge :status="$listing->verification_status" />
                                <a href="{{ route('vendor.listings.index') }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View →</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</x-layouts.app>
