<x-layouts.dashboard>
    <x-slot:title>Verifier</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent text-2xl font-bold">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Inspection lab overview</p>
        </div>
        <a href="{{ route('verifier.queue') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-sky-400 hover:shadow-lg hover:shadow-sky-500/20">
            <x-nav-icon name="shield" class="h-4 w-4" />
            Open queue
        </a>
    </div>

    {{-- ── KPI GRID ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Queue depth"
            :value="(string) $stats['queue_depth']"
            icon="shield"
            icon-color="amber"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Certified today"
            :value="(string) $stats['certified_today']"
            icon="package"
            icon-color="emerald"
            trend="+3 vs yesterday"
            trend-dir="up"
            :sparkline="implode(',', $chartData)"
            :glow-first="true"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Rejected today"
            :value="(string) $stats['rejected_today']"
            icon="flag"
            icon-color="red"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Avg inspect time"
            :value="$stats['avg_time']"
            icon="users"
            icon-color="blue"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── TRUST ENGINE CALLOUT ─────────────────────────────────────────── --}}
    <div class="mb-8 flex items-start gap-4 rounded-2xl border border-sky-500/20 bg-sky-500/10 p-6">
        <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-500/15 text-sky-400">
            <x-nav-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-sm font-semibold text-sky-300">You are the Trust Engine</p>
            <p class="mt-1 text-sm leading-relaxed text-sky-400">
                Every certification you issue carries a QR-backed UUID. Buyers rely on your judgement to confirm IMEI legitimacy,
                hardware authenticity, and condition grading. Only the Verifier role can issue Trust Certificates.
            </p>
        </div>
    </div>

    {{-- ── TODAY'S PROGRESS: Donut ring + legend ───────────────────────── --}}
    @php
        $done           = $certificationRing['certified'] + $certificationRing['rejected'];
        $totalRing      = $done + $certificationRing['remaining'];
        $circumference  = 2 * M_PI * 36;
        $certDash       = $totalRing > 0 ? round(($certificationRing['certified'] / $totalRing) * $circumference, 2) : 0;
        $rejDash        = $totalRing > 0 ? round(($certificationRing['rejected']  / $totalRing) * $circumference, 2) : 0;
        $certOffset     = round(-$circumference / 4, 2);
        $rejOffset      = round($certOffset - $certDash, 2);
        $completionPct  = $totalRing > 0 ? round(($done / $totalRing) * 100) : 0;
    @endphp

    <div class="mb-8 overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Today's certification progress</h3>
            <p class="mt-0.5 text-xs text-slate-400">{{ $done }} of {{ $totalRing }} devices processed</p>
        </div>
        <div class="flex items-center gap-8 px-6 py-6">

            {{-- Donut chart --}}
            <div class="shrink-0">
                <svg viewBox="0 0 80 80" class="h-24 w-24" aria-hidden="true">
                    {{-- Background track --}}
                    <circle cx="40" cy="40" r="36" fill="none" stroke="currentColor"
                            stroke-width="8" class="text-white/5" />
                    {{-- Certified arc --}}
                    @if($certDash > 0)
                        <circle cx="40" cy="40" r="36" fill="none" stroke="currentColor"
                                stroke-width="8" class="text-emerald-400"
                                stroke-dasharray="{{ $certDash }} {{ $circumference - $certDash }}"
                                stroke-dashoffset="{{ $certOffset }}"
                                stroke-linecap="round" />
                    @endif
                    {{-- Rejected arc --}}
                    @if($rejDash > 0)
                        <circle cx="40" cy="40" r="36" fill="none" stroke="currentColor"
                                stroke-width="8" class="text-red-400"
                                stroke-dasharray="{{ $rejDash }} {{ $circumference - $rejDash }}"
                                stroke-dashoffset="{{ $rejOffset }}"
                                stroke-linecap="round" />
                    @endif
                    {{-- Center label --}}
                    <text x="40" y="37" text-anchor="middle" font-size="15" font-weight="700"
                          fill="white" font-family="Nunito, sans-serif">{{ $certificationRing['certified'] }}</text>
                    <text x="40" y="52" text-anchor="middle" font-size="7" fill="#94a3b8"
                          font-family="Nunito, sans-serif">certified</text>
                </svg>
            </div>

            {{-- Legend + progress bar --}}
            <div class="flex-1 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-emerald-400"></div>
                        <span class="text-sm text-slate-300">Certified</span>
                    </div>
                    <span class="text-sm font-semibold tabular-nums text-white">{{ $certificationRing['certified'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-red-400"></div>
                        <span class="text-sm text-slate-300">Rejected</span>
                    </div>
                    <span class="text-sm font-semibold tabular-nums text-white">{{ $certificationRing['rejected'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-slate-500"></div>
                        <span class="text-sm text-slate-300">Remaining</span>
                    </div>
                    <span class="text-sm font-semibold tabular-nums text-white">{{ $certificationRing['remaining'] }}</span>
                </div>
                <div class="border-t border-white/5 pt-3">
                    <div class="mb-1.5 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Completion rate</span>
                        <span class="font-semibold text-white">{{ $completionPct }}%</span>
                    </div>
                    <div class="h-1.5 rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-emerald-500 transition-all duration-700"
                             style="width: {{ $completionPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── QUEUE TABLE with urgency badges ─────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Oldest pending devices</h3>
            <a href="{{ route('verifier.queue') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">Full queue ({{ $stats['queue_depth'] }})</a>
        </div>

        @if($topQueue->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="shield" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">Queue is empty — all devices are processed.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($topQueue as $device)
                    @php
                        $urgencyClass = match($device['urgency']) {
                            'high'   => 'bg-red-500/20 text-red-400 ring-1 ring-red-500/30',
                            'medium' => 'bg-amber-500/20 text-amber-400 ring-1 ring-amber-500/30',
                            default  => 'bg-slate-500/20 text-slate-400 ring-1 ring-white/10',
                        };
                    @endphp
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $device['name'] }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $device['imei'] ? 'IMEI: '.$device['imei'] : 'No IMEI' }} &bull; {{ $device['vendor'] }}
                            </p>
                        </div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium {{ $urgencyClass }}">
                            {{ $device['wait'] }}
                        </span>
                        <a href="{{ route('verifier.queue') }}" class="shrink-0 text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">
                            Inspect →
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
