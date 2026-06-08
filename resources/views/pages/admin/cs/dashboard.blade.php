<x-layouts.dashboard>
    <x-slot:title>Customer Service</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d F Y') }} &mdash; Customer Service overview</p>
        </div>
        <a href="{{ route('admin.disputes.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600 transition-colors shadow-sm">
            <x-nav-icon name="flag" class="h-4 w-4" />
            Manage disputes
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Open disputes"       :value="(string) $stats['open_disputes']"   icon="flag"    icon-color="red" />
        <x-card-stat-card label="Resolved today"      :value="(string) $stats['resolved_today']"  icon="package" icon-color="emerald" />
        <x-card-stat-card label="Pending refunds"     :value="(string) $stats['pending_refunds']" icon="box"     icon-color="amber" />
        <x-card-stat-card label="Avg resolution (d)"  :value="$stats['avg_resolution']"           icon="users"   icon-color="blue" />
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Open disputes</h3>
            <a href="{{ route('admin.disputes.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">View all</a>
        </div>

        @if($openDisputes->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="flag" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">No open disputes right now — great work.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($openDisputes as $dispute)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">Order #{{ $dispute->order_id }}</p>
                            <p class="text-xs text-slate-400">{{ $dispute->reason ?? 'No reason provided' }}</p>
                        </div>
                        <x-ui-badge color="red" size="xs">Open</x-ui-badge>
                        <a href="{{ route('admin.disputes.show', $dispute) }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
