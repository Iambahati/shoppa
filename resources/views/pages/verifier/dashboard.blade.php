<x-layouts.dashboard>
    <x-slot:title>Verifier</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d F Y') }} &mdash; Inspection lab overview</p>
        </div>
        <a href="{{ route('verifier.queue') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600 transition-colors shadow-sm">
            <x-nav-icon name="shield" class="h-4 w-4" />
            Open queue
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Queue depth"        :value="(string) $stats['queue_depth']"     icon="shield"  icon-color="amber" />
        <x-card-stat-card label="Certified today"    :value="(string) $stats['certified_today']" icon="package" icon-color="emerald" />
        <x-card-stat-card label="Rejected today"     :value="(string) $stats['rejected_today']"  icon="flag"    icon-color="red" />
        <x-card-stat-card label="Avg inspect time"   :value="$stats['avg_time']"                 icon="users"   icon-color="blue" />
    </div>

    {{-- Trust Engine callout --}}
    <div class="mb-8 rounded-2xl border border-sky-200 bg-sky-50 p-6 flex items-start gap-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 mt-0.5">
            <x-nav-icon name="shield" class="h-5 w-5" />
        </span>
        <div>
            <p class="text-sm font-semibold text-sky-900">You are the Trust Engine</p>
            <p class="mt-1 text-sm text-sky-700 leading-relaxed">
                Every certification you issue carries a QR-backed UUID. Buyers rely on your judgement to confirm IMEI legitimacy,
                hardware authenticity, and condition grading. Only the Verifier role can issue Trust Certificates.
            </p>
        </div>
    </div>

    {{-- Queue table --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Oldest pending devices</h3>
            <a href="{{ route('verifier.queue') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">Full queue</a>
        </div>

        @if($topQueue->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="shield" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">Queue is empty — all devices are processed.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($topQueue as $device)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $device->name }}</p>
                            <p class="text-xs text-slate-400">IMEI: {{ $device->imei ?? '—' }} &bull; Submitted {{ $device->created_at->diffForHumans() }}</p>
                        </div>
                        <x-trust-cert-badge status="pending" />
                        <a href="{{ route('verifier.inspections.show', $device) }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Inspect →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
