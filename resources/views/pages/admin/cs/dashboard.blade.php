<x-layouts.dashboard>
    <x-slot:title>Customer Service</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Customer Service overview</p>
        </div>
        <a href="{{ route('admin.disputes.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-sky-400">
            <x-nav-icon name="flag" class="h-4 w-4" />
            Manage disputes
        </a>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card label="Open disputes"       :value="(string) $stats['open_disputes']"   icon="flag"    icon-color="red" />
        <x-card-stat-card label="Resolved today"      :value="(string) $stats['resolved_today']"  icon="package" icon-color="emerald" />
        <x-card-stat-card label="Pending refunds"     :value="(string) $stats['pending_refunds']" icon="box"     icon-color="amber" />
        <x-card-stat-card label="Avg resolution (d)"  :value="$stats['avg_resolution']"           icon="users"   icon-color="blue" />
    </div>

    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Open disputes</h3>
            <a href="{{ route('admin.disputes.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        @if($openDisputes->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="flag" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">No open disputes right now — great work.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($openDisputes as $dispute)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">Order #{{ $dispute->order_id }}</p>
                            <p class="text-xs text-slate-500">{{ $dispute->reason ?? 'No reason provided' }}</p>
                        </div>
                        <x-ui-badge color="red" size="xs">Open</x-ui-badge>
                        <a href="{{ route('admin.disputes.show', $dispute) }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
