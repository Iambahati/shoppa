<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────── --}}
    <div class="mb-7 flex items-end justify-between gap-4">
        <div>
            <p class="section-label mb-1.5">{{ now()->format('l, d F Y') }}</p>
            <h1 class="text-xl font-semibold text-white tracking-tight">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h1>
            @php $urgentCount = $stats['pending_vendor_apps'] + $stats['disputes_open']; @endphp
            <p class="mt-1 text-[13px] text-slate-500">
                @if($urgentCount > 0)
                    <span class="text-amber-400">{{ $urgentCount }} {{ $urgentCount === 1 ? 'item' : 'items' }} need attention</span>
                @else
                    <span class="text-emerald-400/80">Platform running smoothly</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-slate-700 px-3.5 py-2 text-[13px] font-medium text-slate-200
                  ring-1 ring-white/[0.08] transition-all hover:bg-slate-600 hover:text-white">
            <x-nav-icon name="user" class="h-3.5 w-3.5 text-slate-400" />
            Add staff
        </a>
    </div>

    {{-- ── INLINE NOTICE STRIPS ────────────────────────────────────────── --}}
    @if($stats['pending_vendor_apps'] > 0)
        <div class="notice-strip mb-2 border-amber-400/40">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-400"></span>
            <p>
                <span class="font-semibold text-white">{{ $stats['pending_vendor_apps'] }}</span>
                vendor {{ $stats['pending_vendor_apps'] === 1 ? 'application' : 'applications' }} awaiting review
            </p>
            <a href="{{ route('admin.vendors.index') }}" class="ml-auto shrink-0 text-xs font-medium text-amber-400 transition-colors hover:text-amber-300">Review →</a>
        </div>
    @endif
    @if($stats['disputes_open'] > 0)
        <div class="notice-strip mb-6 border-red-400/40">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-red-400"></span>
            <p>
                <span class="font-semibold text-white">{{ $stats['disputes_open'] }}</span>
                open {{ $stats['disputes_open'] === 1 ? 'dispute' : 'disputes' }} need resolution
            </p>
            <a href="{{ route('admin.disputes.index') }}" class="ml-auto shrink-0 text-xs font-medium text-red-400 transition-colors hover:text-red-300">Manage →</a>
        </div>
    @elseif($stats['pending_vendor_apps'] > 0)
        <div class="mb-6"></div>
    @else
        <div class="mb-6"></div>
    @endif

    {{-- ── KPI GRID ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-card-stat-card
            label="Total users"
            :value="number_format($stats['total_users'])"
            icon="users"
            icon-color="blue"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Pending vendors"
            :value="(string) $stats['pending_vendor_apps']"
            icon="store"
            icon-color="amber"
            style="animation-delay: 60ms"
        />
        <x-card-stat-card
            label="Orders today"
            :value="(string) $stats['orders_today']"
            icon="box"
            icon-color="emerald"
            style="animation-delay: 120ms"
        />
        <x-card-stat-card
            label="Open disputes"
            :value="(string) $stats['disputes_open']"
            icon="flag"
            icon-color="red"
            style="animation-delay: 180ms"
        />
    </div>

    {{-- ── PLATFORM ACTIVITY + VENDOR PIPELINE ─────────────────────────── --}}
    <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">

        {{-- Activity chart (2/3 width) --}}
        @php
            $pts30   = $orderVolume30d;
            $cnt30   = count($pts30);
            $maxV30  = max($pts30) ?: 1;
            $linePts = [];
            $fillPts = ["0,60"];
            foreach ($pts30 as $i => $v) {
                $x = round(($i / ($cnt30 - 1)) * 300, 2);
                $y = round(60 - (($v / $maxV30) * 50) - 2, 2);
                $linePts[] = "$x,$y";
                $fillPts[] = "$x,$y";
            }
            $fillPts[]   = "300,60";
            $lineStr     = implode(' ', $linePts);
            $fillStr     = implode(' ', $fillPts);
            $totalOrders = array_sum($pts30);
        @endphp

        <div class="lg:col-span-2 overflow-hidden rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06]">
            <div class="flex items-start justify-between px-5 pt-5 pb-4">
                <div>
                    <p class="section-label">Platform activity</p>
                    <p class="mt-1 text-[13px] text-slate-400">Order volume — last 30 days</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold tabular-nums text-white leading-none">{{ number_format($totalOrders) }}</p>
                    <p class="mt-1 text-[11px] text-slate-500">orders this month</p>
                </div>
            </div>
            @if($totalOrders > 0)
                <div class="px-5 pb-2">
                    <svg viewBox="0 0 300 60" class="h-14 w-full" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="adminAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%"   stop-color="#38bdf8" stop-opacity="0.2"/>
                                <stop offset="100%" stop-color="#38bdf8" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $fillStr }}" fill="url(#adminAreaGrad)" />
                        <polyline points="{{ $lineStr }}" fill="none" stroke="#38bdf8" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" opacity="0.8" />
                    </svg>
                </div>
            @else
                <div class="px-5 pb-5">
                    <div class="flex h-14 items-center justify-center">
                        <p class="text-xs text-slate-600">No order data yet — awaiting Sprint 2 migrations</p>
                    </div>
                </div>
            @endif
            <div class="flex items-center justify-between border-t border-white/[0.04] px-5 py-2.5 text-[11px] text-slate-600">
                <span>30 days ago</span>
                <span>Today</span>
            </div>
        </div>

        {{-- Vendor pipeline (1/3 width) --}}
        <div class="rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06] px-5 pt-5 pb-4">
            <p class="section-label mb-4">Vendor pipeline</p>
            @php $pipeTotal = array_sum($vendorPipeline); @endphp
            <div class="space-y-3.5">
                @foreach([
                    ['label' => 'Pending', 'key' => 'pending',  'color' => 'bg-amber-400',     'text' => 'text-amber-400'],
                    ['label' => 'Active',  'key' => 'approved', 'color' => 'bg-emerald-500',    'text' => 'text-emerald-400'],
                    ['label' => 'Rejected','key' => 'rejected', 'color' => 'bg-red-400/70',     'text' => 'text-red-400'],
                ] as $row)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <span class="text-xs font-medium {{ $row['text'] }}">{{ $row['label'] }}</span>
                            <span class="text-xs tabular-nums text-slate-400">{{ $vendorPipeline[$row['key']] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-white/[0.06]">
                            <div class="h-full rounded-full {{ $row['color'] }} transition-all duration-700"
                                 style="width: {{ $pipeTotal > 0 ? round(($vendorPipeline[$row['key']] / $pipeTotal) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-5 border-t border-white/[0.04] pt-3 text-[11px] text-slate-600">
                {{ $pipeTotal }} total applications
            </p>
            <a href="{{ route('admin.vendors.index') }}"
               class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">
                Manage →
            </a>
        </div>

    </div>

    {{-- ── RECENT REGISTRATIONS ─────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06]">
        <div class="flex items-center justify-between border-b border-white/[0.05] px-5 py-4">
            <p class="section-label">Recent registrations</p>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>
        @if($recentUsers->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
                <svg class="h-12 w-12 text-slate-700" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 3"/>
                    <circle cx="24" cy="18" r="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M10 38c0-7.732 6.268-14 14-14s14 6.268 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <p class="mt-3 text-sm text-slate-500">No users yet</p>
            </div>
        @else
            <ul role="list">
                @foreach($recentUsers as $u)
                    <li class="flex items-center gap-3.5 border-b border-white/[0.04] px-5 py-3 last:border-0 transition-colors hover:bg-white/[0.02]">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                                    bg-gradient-to-br from-slate-600 to-slate-700 text-[11px] font-bold uppercase text-slate-200">
                            {{ substr($u->name, 0, 2) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[13px] font-medium text-white">{{ $u->name }}</p>
                            <p class="truncate text-[11px] text-slate-500">{{ $u->email }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-[11px] font-medium text-slate-400">{{ $u->role?->name ?? 'Unknown' }}</p>
                            <p class="text-[11px] text-slate-600">{{ $u->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
