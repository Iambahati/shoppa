<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── GREETING ─────────────────────────────────────────────────────── --}}
    <div class="mb-8">
        <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
        </h2>
        <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }}</p>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Active orders"
            :value="(string) $stats['active_orders']"
            icon="box"
            icon-color="blue"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Total purchases"
            :value="(string) $stats['total_orders']"
            icon="layers"
            icon-color="emerald"
            trend="+2 this month"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Wishlist"
            :value="(string) $stats['wishlist_count']"
            icon="search"
            icon-color="purple"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Verified devices"
            :value="(string) $stats['devices_verified']"
            icon="shield"
            icon-color="emerald"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── BROWSE CTA gradient card ─────────────────────────────────────── --}}
    <div class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-sky-600/30 via-sky-500/10 to-transparent
                ring-1 ring-sky-500/20 p-6 shadow-lg shadow-sky-500/10">
        <div class="flex items-center justify-between gap-6">
            <div>
                <h3 class="text-lg font-bold text-white">Find your next device</h3>
                <p class="mt-1 text-sm text-sky-200/70">486 verified devices available right now — all inspected and certified</p>
            </div>
            <a href="{{ route('buyer.browse') }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-5 py-2.5 text-sm font-semibold
                      text-white shadow-lg shadow-sky-500/30 transition-all hover:bg-sky-400 hover:shadow-sky-400/40">
                Browse now <x-nav-icon name="chevron-r" class="h-4 w-4" />
            </a>
        </div>
    </div>

    {{-- ── TRUST CALLOUT ────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start gap-4 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-6
                shadow-lg shadow-emerald-500/10">
        <x-trust-verified-pill size="lg" class="mt-0.5 shrink-0" />
        <div>
            <p class="text-sm font-semibold text-emerald-300">Every device on Shoppa is physically inspected</p>
            <p class="mt-1 text-sm leading-relaxed text-emerald-400">
                Our verification team checks IMEI legitimacy, hardware authenticity, and condition grading before any listing goes live.
                <a href="{{ route('buyer.browse') }}" class="font-medium underline underline-offset-2 hover:no-underline">Browse verified devices →</a>
            </p>
        </div>
    </div>

    {{-- ── RECENT ORDERS: timeline-style left border ────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        <ul role="list" class="divide-y divide-white/5">
            @foreach($recentOrders as $order)
                @php
                    $borderColor = match($order['status_color']) {
                        'emerald' => 'border-l-emerald-500',
                        'blue'    => 'border-l-sky-500',
                        'amber'   => 'border-l-amber-500',
                        default   => 'border-l-red-500',
                    };
                @endphp
                <li class="flex items-center gap-4 border-l-2 {{ $borderColor }} py-3.5 pl-5 pr-6 transition-colors hover:bg-white/5">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ $order['item'] }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $order['id'] }} &bull; {{ $order['age'] }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-3">
                        <span class="text-sm font-semibold tabular-nums text-white">KSh {{ number_format($order['amount']) }}</span>
                        <x-ui-badge :color="$order['status_color']" size="xs">{{ $order['status'] }}</x-ui-badge>
                        <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">View →</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

</x-layouts.app>
