<x-layouts.dashboard>
    <x-slot:title>Content Manager</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Content Manager overview</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-sky-400 hover:shadow-lg hover:shadow-sky-500/20">
            <x-nav-icon name="package" class="h-4 w-4" />
            Add product
        </a>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Total products"
            :value="number_format($stats['total_products'])"
            icon="package"
            icon-color="blue"
            trend="+14 today"
            trend-dir="up"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Pending review"
            :value="(string) $stats['pending_review']"
            icon="shield"
            icon-color="amber"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Published today"
            :value="(string) $stats['published_today']"
            icon="layers"
            icon-color="emerald"
            trend="+40% vs yesterday"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Categories"
            :value="(string) $stats['categories']"
            icon="box"
            icon-color="purple"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── PUBLICATION FUNNEL ───────────────────────────────────────────── --}}
    <div class="mb-8 rounded-2xl bg-slate-800 ring-1 ring-white/5 px-6 py-5">
        <h3 class="mb-5 text-sm font-semibold text-white">Publication funnel</h3>
        @php
            $funnelStages = [
                ['label' => 'Submitted for review', 'count' => $funnel['submitted'], 'color' => 'bg-sky-400',                                      'text' => 'text-sky-400'],
                ['label' => 'In review',             'count' => $funnel['in_review'], 'color' => 'bg-amber-400',                                    'text' => 'text-amber-400'],
                ['label' => 'Published &amp; live',  'count' => $funnel['approved'],  'color' => 'bg-gradient-to-r from-sky-500 to-emerald-500',    'text' => 'text-emerald-400'],
                ['label' => 'Rejected',              'count' => $funnel['rejected'],  'color' => 'bg-red-400/70',                                   'text' => 'text-red-400'],
            ];
            $funnelMax = max(array_column($funnelStages, 'count')) ?: 1;
        @endphp
        <div class="space-y-4">
            @foreach($funnelStages as $stage)
                <div>
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="{{ $stage['text'] }} font-medium">{!! $stage['label'] !!}</span>
                        <span class="tabular-nums text-slate-300">{{ number_format($stage['count']) }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10">
                        <div class="h-full rounded-full {{ $stage['color'] }} transition-all duration-700"
                             style="width: {{ round(($stage['count'] / $funnelMax) * 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── RECENTLY SUBMITTED PRODUCTS ─────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Recently submitted products</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>
        <ul role="list" class="divide-y divide-white/5">
            @foreach($recentProducts as $product)
                <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ $product['name'] }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $product['vendor'] }} &bull; {{ $product['category'] }} &bull; {{ $product['age'] }}
                        </p>
                    </div>
                    <x-trust-cert-badge :status="$product['status']" />
                    <a href="{{ route('admin.products.index') }}" class="shrink-0 text-xs font-medium text-sky-400 hover:text-sky-300">Review →</a>
                </li>
            @endforeach
        </ul>
    </div>

</x-layouts.dashboard>
