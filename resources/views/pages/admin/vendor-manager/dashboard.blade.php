<x-layouts.dashboard>
    <x-slot:title>Vendor Manager</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Vendor Manager overview</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-sky-400 hover:shadow-lg hover:shadow-sky-500/20">
            <x-nav-icon name="store" class="h-4 w-4" />
            Review applications
        </a>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Pending applications"
            :value="(string) $stats['pending_applications']"
            icon="store"
            icon-color="amber"
            trend="+3 today"
            trend-dir="neutral"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Active vendors"
            :value="(string) $stats['active_vendors']"
            icon="users"
            icon-color="emerald"
            trend="+12 this week"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Suspended vendors"
            :value="(string) $stats['suspended_vendors']"
            icon="flag"
            icon-color="red"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Approvals this week"
            :value="(string) $stats['approvals_this_week']"
            icon="package"
            icon-color="blue"
            trend="+4 vs last week"
            trend-dir="up"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── VENDOR ECOSYSTEM HEALTH ──────────────────────────────────────── --}}
    <div class="mb-8 rounded-2xl bg-slate-800 ring-1 ring-white/5 px-6 py-5">
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">Vendor ecosystem health</h3>
            <span class="text-xs text-slate-400">{{ array_sum($vendorBreakdown) }} total applications</span>
        </div>
        @php
            $breakMax  = max(array_values($vendorBreakdown)) ?: 1;
            $healthRows = [
                ['label' => 'Active vendors', 'key' => 'active',    'color' => 'bg-gradient-to-r from-sky-500 to-emerald-500', 'text' => 'text-emerald-400'],
                ['label' => 'Pending review', 'key' => 'pending',   'color' => 'bg-amber-400',                                  'text' => 'text-amber-400'],
                ['label' => 'Suspended',      'key' => 'suspended', 'color' => 'bg-red-400/70',                                 'text' => 'text-red-400'],
                ['label' => 'Rejected',       'key' => 'rejected',  'color' => 'bg-slate-600',                                  'text' => 'text-slate-400'],
            ];
        @endphp
        <div class="space-y-4">
            @foreach($healthRows as $row)
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="{{ $row['text'] }} font-medium">{{ $row['label'] }}</span>
                        <span class="tabular-nums text-slate-300">{{ $vendorBreakdown[$row['key']] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full {{ $row['color'] }} transition-all duration-700"
                             style="width: {{ round(($vendorBreakdown[$row['key']] / $breakMax) * 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── PENDING APPLICATIONS TABLE ───────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Pending applications</h3>
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        @if($pendingVendors->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="store" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">No pending applications right now.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($pendingVendors as $vendor)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-sm font-bold text-amber-400">
                            {{ substr($vendor['name'], 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $vendor['name'] }}</p>
                            <p class="text-xs text-slate-500">{{ $vendor['owner'] }} &bull; {{ $vendor['category'] }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <span class="h-2 w-2 rounded-full {{ $vendor['docs'] ? 'bg-emerald-400' : 'bg-amber-400' }}"
                                  title="{{ $vendor['docs'] ? 'Documents complete' : 'Missing documents' }}"></span>
                            <span class="text-xs text-slate-500">{{ $vendor['applied'] }}</span>
                            <x-ui-badge color="amber" size="xs">Pending</x-ui-badge>
                        </div>
                        <a href="{{ route('admin.vendors.index') }}" class="shrink-0 text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
