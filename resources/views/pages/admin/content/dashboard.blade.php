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
        @if($recentProducts->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <svg class="h-16 w-16 text-slate-700" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <rect x="8" y="8" width="48" height="48" rx="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8 24 L56 24" stroke="currentColor" stroke-width="1" stroke-dasharray="3 2"/>
                    <path d="M8 40 L56 40" stroke="currentColor" stroke-width="1" stroke-dasharray="3 2"/>
                    <path d="M24 8 L24 56" stroke="currentColor" stroke-width="1" stroke-dasharray="3 2"/>
                    <path d="M40 8 L40 56" stroke="currentColor" stroke-width="1" stroke-dasharray="3 2"/>
                    <circle cx="32" cy="32" r="8" fill="#0f172a" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="32" y1="28" x2="32" y2="36" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="28" y1="32" x2="36" y2="32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <p class="mt-4 text-sm font-semibold text-slate-300">No products submitted yet</p>
                <p class="mt-1 text-xs text-slate-500">Products will appear here as vendors submit them for review.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($recentProducts as $product)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/[0.03]">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $product->vendor?->name ?? 'Unknown vendor' }}
                                &bull; {{ $product->category?->name ?? 'Uncategorized' }}
                                &bull; {{ $product->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <x-trust-cert-badge :status="$product->verification_status" />
                        <a href="{{ route('admin.products.index') }}" class="shrink-0 text-xs font-medium text-sky-400 hover:text-sky-300">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
