<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            @php $urgentCount = $stats['pending_vendor_apps'] + $stats['disputes_open']; @endphp
            <p class="mt-1 text-sm text-slate-400">
                {{ now()->format('l, d F Y') }} &mdash;
                @if($urgentCount > 0)
                    <span class="font-medium text-amber-400">{{ $urgentCount }} {{ $urgentCount === 1 ? 'item needs' : 'items need' }} your attention</span>
                @else
                    <span class="font-medium text-emerald-400">All clear — platform running smoothly</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-sky-400 hover:shadow-lg hover:shadow-sky-500/20">
            <x-nav-icon name="user" class="h-4 w-4" />
            Add staff
        </a>
    </div>

    {{-- ── ALERT STRIPS ────────────────────────────────────────────────── --}}
    @if($stats['pending_vendor_apps'] > 0)
        <div class="mb-3 flex items-center gap-3 rounded-xl border border-amber-500/20 bg-amber-500/10 px-4 py-3">
            <x-nav-icon name="store" class="h-4 w-4 shrink-0 text-amber-400" />
            <p class="text-sm text-amber-300">
                <span class="font-semibold">{{ $stats['pending_vendor_apps'] }} vendor {{ $stats['pending_vendor_apps'] === 1 ? 'application' : 'applications' }}</span>
                awaiting review.
            </p>
            <a href="{{ route('admin.vendors.index') }}" class="ml-auto text-xs font-medium text-amber-400 transition-colors hover:text-amber-300">Review now →</a>
        </div>
    @endif
    @if($stats['disputes_open'] > 0)
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3">
            <x-nav-icon name="flag" class="h-4 w-4 shrink-0 text-red-400" />
            <p class="text-sm text-red-300">
                <span class="font-semibold">{{ $stats['disputes_open'] }} open {{ $stats['disputes_open'] === 1 ? 'dispute' : 'disputes' }}</span>
                need urgent resolution.
            </p>
            <a href="{{ route('admin.disputes.index') }}" class="ml-auto text-xs font-medium text-red-400 transition-colors hover:text-red-300">Manage →</a>
        </div>
    @else
        <div class="mb-6"></div>
    @endif

    {{-- ── KPI GRID ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Total users"
            :value="number_format($stats['total_users'])"
            icon="users"
            icon-color="blue"
            trend="+8.2% this month"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Pending vendors"
            :value="(string) $stats['pending_vendor_apps']"
            icon="store"
            icon-color="amber"
            trend="+3 this week"
            trend-dir="neutral"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Orders today"
            :value="(string) $stats['orders_today']"
            icon="box"
            icon-color="emerald"
            trend="+18% vs yesterday"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Open disputes"
            :value="(string) $stats['disputes_open']"
            icon="flag"
            icon-color="red"
            trend="–1 since yesterday"
            trend-dir="down"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── PLATFORM SNAPSHOT: 30-day area chart ────────────────────────── --}}
    @php
        $pts30   = $orderVolume30d;
        $cnt30   = count($pts30);
        $maxV30  = max($pts30) ?: 1;
        $linePts = [];
        $fillPts = ["0,64"];
        foreach ($pts30 as $i => $v) {
            $x = round(($i / ($cnt30 - 1)) * 300, 2);
            $y = round(64 - (($v / $maxV30) * 56) - 4, 2);
            $linePts[] = "$x,$y";
            $fillPts[] = "$x,$y";
        }
        $fillPts[]   = "300,64";
        $lineStr     = implode(' ', $linePts);
        $fillStr     = implode(' ', $fillPts);
        $totalOrders = array_sum($pts30);
    @endphp

    <div class="mb-8 overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-white">Platform activity</h3>
                <p class="mt-0.5 text-xs text-slate-400">Order volume — last 30 days</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold tabular-nums text-white">{{ number_format($totalOrders) }}</p>
                <p class="text-xs text-slate-400">orders this month</p>
            </div>
        </div>
        <div class="px-6 pt-4 pb-1">
            <svg viewBox="0 0 300 64" class="h-16 w-full" preserveAspectRatio="none" aria-hidden="true">
                <defs>
                    <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%"   stop-color="#38bdf8" stop-opacity="0.3"/>
                        <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.02"/>
                    </linearGradient>
                </defs>
                <polygon points="{{ $fillStr }}" fill="url(#areaGrad)" />
                <polyline points="{{ $lineStr }}" fill="none" stroke="#38bdf8" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="flex items-center justify-between border-t border-white/5 px-6 py-3 text-xs text-slate-500">
            <span>30 days ago</span>
            <span>Today</span>
        </div>
    </div>

    {{-- ── TWO-COLUMN: Registrations + Vendor pipeline ─────────────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Recent registrations --}}
        <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
            <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
                <h3 class="text-sm font-semibold text-white">Recent registrations</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
            </div>
            <ul role="list" class="divide-y divide-white/5">
                @foreach($recentUsers as $u)
                    <li class="flex items-center gap-3 px-6 py-3 transition-colors hover:bg-white/5">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-sky-600 text-xs font-semibold uppercase text-white">
                            {{ substr($u['name'], 0, 2) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $u['name'] }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $u['email'] }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-xs font-medium text-slate-300">{{ $u['role'] }}</p>
                            <p class="text-xs text-slate-500">{{ $u['joined'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Vendor pipeline --}}
        <div class="rounded-2xl bg-slate-800 ring-1 ring-white/5 px-6 py-5">
            <h3 class="mb-5 text-sm font-semibold text-white">Vendor pipeline</h3>
            @php $pipeTotal = array_sum($vendorPipeline); @endphp
            <div class="space-y-4">
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-amber-400">Pending review</span>
                        <span class="tabular-nums text-slate-300">{{ $vendorPipeline['pending'] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-amber-400 transition-all duration-700"
                             style="width: {{ $pipeTotal > 0 ? round(($vendorPipeline['pending'] / $pipeTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-emerald-400">Active vendors</span>
                        <span class="tabular-nums text-slate-300">{{ $vendorPipeline['approved'] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-emerald-500 transition-all duration-700"
                             style="width: {{ $pipeTotal > 0 ? round(($vendorPipeline['approved'] / $pipeTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-red-400">Rejected</span>
                        <span class="tabular-nums text-slate-300">{{ $vendorPipeline['rejected'] }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-red-400/70 transition-all duration-700"
                             style="width: {{ $pipeTotal > 0 ? round(($vendorPipeline['rejected'] / $pipeTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            <p class="mt-5 border-t border-white/5 pt-4 text-xs text-slate-500">
                {{ $pipeTotal }} total applications on the platform
            </p>
            <a href="{{ route('admin.vendors.index') }}"
               class="mt-3 inline-flex items-center gap-1 text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">
                Manage applications →
            </a>
        </div>

    </div>

</x-layouts.dashboard>
