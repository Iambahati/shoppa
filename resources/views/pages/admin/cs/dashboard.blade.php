<x-layouts.dashboard>
    <x-slot:title>Customer Service</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Customer Service overview</p>
        </div>
        <a href="{{ route('admin.disputes.index') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-sky-400 hover:shadow-lg hover:shadow-sky-500/20">
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

    {{-- ── URGENCY BREAKDOWN TILES ─────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-red-500/10 ring-1 ring-red-500/20 px-5 py-5 text-center">
            <p class="text-4xl font-bold tabular-nums text-red-400">{{ $urgencyBreakdown['high'] }}</p>
            <p class="mt-1.5 text-xs font-semibold text-slate-300">High priority</p>
            <p class="mt-0.5 text-xs text-red-400/70">Action required within 24h</p>
        </div>
        <div class="rounded-2xl bg-amber-500/10 ring-1 ring-amber-500/20 px-5 py-5 text-center">
            <p class="text-4xl font-bold tabular-nums text-amber-400">{{ $urgencyBreakdown['medium'] }}</p>
            <p class="mt-1.5 text-xs font-semibold text-slate-300">Medium priority</p>
            <p class="mt-0.5 text-xs text-amber-400/70">Resolve within 3 days</p>
        </div>
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/5 px-5 py-5 text-center">
            <p class="text-4xl font-bold tabular-nums text-slate-400">{{ $urgencyBreakdown['low'] }}</p>
            <p class="mt-1.5 text-xs font-semibold text-slate-300">Low priority</p>
            <p class="mt-0.5 text-xs text-slate-500">No immediate urgency</p>
        </div>
    </div>

    {{-- ── OPEN DISPUTES LIST ───────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Open disputes</h3>
            <a href="{{ route('admin.disputes.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        @if($openDisputes->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <svg class="h-16 w-16 text-slate-700" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <circle cx="32" cy="32" r="26" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M20 32 C20 32 24 40 32 40 C40 40 44 32 44 32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="24" cy="24" r="2.5" fill="currentColor"/>
                    <circle cx="40" cy="24" r="2.5" fill="currentColor"/>
                    <path d="M26 18 L32 14 L38 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="mt-4 text-sm font-semibold text-slate-300">No open disputes</p>
                <p class="mt-1 text-xs text-slate-500">The platform is running smoothly — keep up the great work.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($disputesWithPriority as $item)
                    @php
                        $dispute = $item['model'];
                        $priority = $item['priority'];
                        $priorityClass = match($priority) {
                            'high'   => 'bg-red-500/20 text-red-400 ring-1 ring-red-500/30',
                            'medium' => 'bg-amber-500/20 text-amber-400 ring-1 ring-amber-500/30',
                            default  => 'bg-slate-500/20 text-slate-400 ring-1 ring-white/10',
                        };
                    @endphp
                    <li class="flex items-start gap-4 px-6 py-4 transition-colors hover:bg-white/[0.03]">
                        <div class="min-w-0 flex-1">
                            <div class="mb-0.5 flex items-center gap-2">
                                <span class="font-mono text-xs font-medium text-sky-400">DSP-{{ $dispute->id }}</span>
                                <span class="text-xs text-slate-500">&bull; Order #{{ $dispute->id }}</span>
                            </div>
                            <p class="text-sm font-medium text-white">{{ $dispute->user?->name ?? 'Unknown buyer' }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">Order total: KSh {{ number_format($dispute->total_amount) }}</p>
                        </div>
                        <div class="flex shrink-0 flex-col items-end gap-1.5">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $priorityClass }}">
                                {{ ucfirst($priority) }}
                            </span>
                            <span class="text-xs text-slate-500">{{ $item['age'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
