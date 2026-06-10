{{-- DS admin dashboard: greeting, 4-stat grid with trends, recent registrations table --}}
<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────── --}}
    <div class="mb-7 flex items-end justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-stone-900 tracking-tight">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h1>
            @php $urgentCount = $stats['pending_vendor_apps'] + $stats['disputes_open']; @endphp
            <p class="mt-1 text-sm text-stone-500">
                @if($urgentCount > 0)
                    <span class="text-amber-600">{{ $urgentCount }} {{ $urgentCount === 1 ? 'item needs' : 'items need' }} attention</span>
                @else
                    <span class="text-emerald-600">Platform running smoothly</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-white px-3.5 py-2 text-sm font-medium text-stone-700
                  ring-1 ring-stone-300 transition-all hover:bg-stone-50">
            <x-nav-icon name="user" class="h-3.5 w-3.5 text-stone-400" />
            Add staff
        </a>
    </div>

    {{-- ── INLINE NOTICE STRIPS: DS warning/error banners --}}
    @if($stats['pending_vendor_apps'] > 0)
        <div class="mb-2 flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm text-amber-800">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>
            <p>
                <span class="font-semibold">{{ $stats['pending_vendor_apps'] }}</span>
                vendor {{ $stats['pending_vendor_apps'] === 1 ? 'application' : 'applications' }} awaiting review
            </p>
            <a href="{{ route('admin.vendors.index') }}" class="ml-auto shrink-0 text-xs font-semibold text-amber-700 transition-colors hover:text-amber-900">Review →</a>
        </div>
    @endif
    @if($stats['disputes_open'] > 0)
        <div class="mb-6 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-800">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>
            <p>
                <span class="font-semibold">{{ $stats['disputes_open'] }}</span>
                open {{ $stats['disputes_open'] === 1 ? 'dispute' : 'disputes' }} need resolution
            </p>
            <a href="{{ route('admin.disputes.index') }}" class="ml-auto shrink-0 text-xs font-semibold text-red-700 transition-colors hover:text-red-900">Manage →</a>
        </div>
    @else
        <div class="mb-6"></div>
    @endif

    {{-- ── KPI GRID --}}
    <div class="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-card-stat-card
            label="Total users"
            :value="number_format($stats['total_users'])"
            icon="users"
            icon-color="blue"
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

    {{-- ── PLATFORM ACTIVITY + VENDOR PIPELINE — white DS cards --}}
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

        <div class="lg:col-span-2 card overflow-hidden">
            <div class="flex items-start justify-between px-5 pt-5 pb-4">
                <div>
                    <h3 class="text-sm font-semibold text-stone-900">Platform activity</h3>
                    <p class="mt-1 text-xs text-stone-400">Order volume — last 30 days</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-semibold tabular-nums text-stone-900 leading-none">{{ number_format($totalOrders) }}</p>
                    <p class="mt-1 text-xs text-stone-400">orders this month</p>
                </div>
            </div>
            @if($totalOrders > 0)
                <div class="px-5 pb-2">
                    <svg viewBox="0 0 300 60" class="h-14 w-full" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="adminAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%"   stop-color="#059669" stop-opacity="0.15"/>
                                <stop offset="100%" stop-color="#059669" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $fillStr }}" fill="url(#adminAreaGrad)" />
                        <polyline points="{{ $lineStr }}" fill="none" stroke="#059669" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" opacity="0.8" />
                    </svg>
                </div>
            @else
                <div class="px-5 pb-5">
                    <div class="flex h-14 items-center justify-center">
                        <p class="text-xs text-stone-400">No order data yet</p>
                    </div>
                </div>
            @endif
            <div class="flex items-center justify-between border-t border-stone-100 px-5 py-2.5 text-xs text-stone-400">
                <span>30 days ago</span>
                <span>Today</span>
            </div>
        </div>

        {{-- Vendor pipeline (1/3 width) --}}
        <div class="card px-5 pt-5 pb-4">
            <h3 class="mb-4 text-sm font-semibold text-stone-900">Vendor pipeline</h3>
            @php $pipeTotal = array_sum($vendorPipeline); @endphp
            <div class="space-y-3.5">
                @foreach([
                    ['label' => 'Pending', 'key' => 'pending',  'bar' => 'bg-amber-400',  'text' => 'text-amber-700'],
                    ['label' => 'Active',  'key' => 'approved', 'bar' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                    ['label' => 'Rejected','key' => 'rejected', 'bar' => 'bg-red-400',     'text' => 'text-red-700'],
                ] as $row)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <span class="text-xs font-medium {{ $row['text'] }}">{{ $row['label'] }}</span>
                            <span class="text-xs tabular-nums text-stone-500">{{ $vendorPipeline[$row['key']] }}</span>
                        </div>
                        <div class="h-[3px] rounded-full bg-stone-100">
                            <div class="h-full rounded-full {{ $row['bar'] }} transition-all duration-700"
                                 style="width: {{ $pipeTotal > 0 ? round(($vendorPipeline[$row['key']] / $pipeTotal) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-5 border-t border-stone-100 pt-3 text-xs text-stone-400">
                {{ $pipeTotal }} total applications
            </p>
            <a href="{{ route('admin.vendors.index') }}"
               class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">
                Manage →
            </a>
        </div>

    </div>

    {{-- ── RECENT REGISTRATIONS — DS table: stone-50 thead, avatar+name, role badge --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-stone-100 px-5 py-4">
            <h3 class="text-sm font-semibold text-stone-900">Recent registrations</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">View all →</a>
        </div>
        @if($recentUsers->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
                <svg class="h-12 w-12 text-stone-200" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 3"/>
                    <circle cx="24" cy="18" r="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M10 38c0-7.732 6.268-14 14-14s14 6.268 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <p class="mt-3 text-sm text-stone-400">No users yet</p>
            </div>
        @else
            <table class="w-full border-collapse">
                {{-- DS thead: stone-50 bg, xs/semibold/stone-500/uppercase/tracking-wide --}}
                <thead>
                    <tr class="bg-stone-50">
                        <th class="table-th">Name</th>
                        <th class="table-th">Email</th>
                        <th class="table-th">Role</th>
                        <th class="table-th">Joined</th>
                        <th class="table-th"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($recentUsers as $u)
                        @php
                            $isStaff = $u->role && in_array($u->role->name, ['Admin','Super Admin','Vendor Manager','Verifier','Customer Service','Content Manager']);
                        @endphp
                        <tr class="transition-colors hover:bg-stone-50">
                            <td class="table-td">
                                {{-- DS avatar: emerald-600 circle + name --}}
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-[11px] font-semibold uppercase text-white">
                                        {{ substr($u->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-medium text-stone-900">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="table-td text-stone-500">{{ $u->email }}</td>
                            {{-- DS badge: staff=purple, buyer=stone --}}
                            <td class="table-td">
                                <x-ui-badge :color="$isStaff ? 'purple' : 'stone'">{{ $u->role?->name ?? 'Unknown' }}</x-ui-badge>
                            </td>
                            <td class="table-td text-stone-400 text-xs">{{ $u->created_at->format('d M Y') }}</td>
                            <td class="table-td text-right">
                                <a href="{{ route('admin.users.show', $u) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</x-layouts.dashboard>
