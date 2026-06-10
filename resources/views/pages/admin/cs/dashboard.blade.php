<x-layouts.dashboard>
    <x-slot:title>Customer Service</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <p class="section-label mb-1.5">{{ now()->format('l, d F Y') }}</p>
            <h1 class="text-xl font-semibold text-stone-900 tracking-tight">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h1>
            <p class="mt-0.5 text-[13px] text-stone-500">Customer Service overview</p>
        </div>
        <a href="{{ route('admin.disputes.index') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-emerald-500">
            <x-nav-icon name="flag" class="h-4 w-4" />
            Manage disputes
        </a>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Open disputes"
            :value="(string) $stats['open_disputes']"
            icon="flag"
            icon-color="red"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Resolved today"
            :value="(string) $stats['resolved_today']"
            icon="package"
            icon-color="emerald"
            :sparkline="implode(',', $chartData)"
            :glow-first="true"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Pending refunds"
            :value="(string) $stats['pending_refunds']"
            icon="box"
            icon-color="amber"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Avg resolution"
            :value="$stats['avg_resolution']"
            icon="users"
            icon-color="blue"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── URGENCY BREAKDOWN ────────────────────────────────────────────── --}}
    <div class="mb-8 card overflow-hidden">
        <div class="grid grid-cols-3 divide-x divide-stone-100">
            <div class="px-5 py-4 text-center">
                <p class="text-3xl font-bold tabular-nums text-red-600 leading-none">{{ $urgencyBreakdown['high'] }}</p>
                <p class="mt-1.5 section-label">High priority</p>
                <p class="mt-0.5 text-[11px] text-stone-400">Within 24h</p>
            </div>
            <div class="px-5 py-4 text-center">
                <p class="text-3xl font-bold tabular-nums text-amber-600 leading-none">{{ $urgencyBreakdown['medium'] }}</p>
                <p class="mt-1.5 section-label">Medium</p>
                <p class="mt-0.5 text-[11px] text-stone-400">Within 3 days</p>
            </div>
            <div class="px-5 py-4 text-center">
                <p class="text-3xl font-bold tabular-nums text-stone-400 leading-none">{{ $urgencyBreakdown['low'] }}</p>
                <p class="mt-1.5 section-label">Low priority</p>
                <p class="mt-0.5 text-[11px] text-stone-400">No urgency</p>
            </div>
        </div>
    </div>

    {{-- ── OPEN DISPUTES LIST ───────────────────────────────────────────── --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
            <h3 class="text-sm font-semibold text-stone-900">Open disputes</h3>
            <a href="{{ route('admin.disputes.index') }}" class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">View all</a>
        </div>

        @if($openDisputes->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <svg class="h-16 w-16 text-stone-200" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <circle cx="32" cy="32" r="26" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M20 32 C20 32 24 40 32 40 C40 40 44 32 44 32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="24" cy="24" r="2.5" fill="currentColor"/>
                    <circle cx="40" cy="24" r="2.5" fill="currentColor"/>
                    <path d="M26 18 L32 14 L38 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="mt-4 text-sm font-semibold text-stone-600">No open disputes</p>
                <p class="mt-1 text-xs text-stone-400">The platform is running smoothly — keep up the great work.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-stone-100">
                @foreach($disputesWithPriority as $item)
                    @php
                        $dispute = $item['model'];
                        $priority = $item['priority'];
                        $priorityColor = match($priority) {
                            'high'   => 'red',
                            'medium' => 'amber',
                            default  => 'stone',
                        };
                    @endphp
                    <li class="flex items-start gap-4 px-6 py-4 transition-colors hover:bg-stone-50">
                        <div class="min-w-0 flex-1">
                            <div class="mb-0.5 flex items-center gap-2">
                                <span class="font-mono text-xs font-medium text-stone-500">DSP-{{ $dispute->id }}</span>
                                <span class="text-xs text-stone-400">&bull; Order #{{ $dispute->id }}</span>
                            </div>
                            <p class="text-sm font-medium text-stone-900">{{ $dispute->user?->name ?? 'Unknown buyer' }}</p>
                            <p class="mt-0.5 text-xs text-stone-500">Order total: KSh {{ number_format($dispute->total_amount) }}</p>
                        </div>
                        <div class="flex shrink-0 flex-col items-end gap-1.5">
                            <x-ui-badge :color="$priorityColor" size="xs">{{ ucfirst($priority) }}</x-ui-badge>
                            <span class="text-xs text-stone-400">{{ $item['age'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
