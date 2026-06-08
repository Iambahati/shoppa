<x-layouts.dashboard>
    <x-slot:title>Vendor Manager</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d F Y') }} &mdash; Vendor Manager overview</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600 transition-colors shadow-sm">
            <x-nav-icon name="store" class="h-4 w-4" />
            Review applications
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Pending applications" :value="(string) $stats['pending_applications']" icon="store"   icon-color="amber" />
        <x-card-stat-card label="Active vendors"        :value="(string) $stats['active_vendors']"       icon="users"   icon-color="blue" />
        <x-card-stat-card label="Suspended vendors"     :value="(string) $stats['suspended_vendors']"    icon="flag"    icon-color="red" />
        <x-card-stat-card label="Approvals this week"   :value="(string) $stats['approvals_this_week']"  icon="package" icon-color="emerald" />
    </div>

    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Pending vendor applications</h3>
            <a href="{{ route('admin.vendors.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">View all</a>
        </div>

        @if($pendingVendors->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="store" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">No pending applications — you're all caught up.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($pendingVendors as $vendor)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $vendor->name }}</p>
                            <p class="text-xs text-slate-400">Submitted {{ $vendor->created_at->diffForHumans() }}</p>
                        </div>
                        <x-ui-badge color="amber" size="xs">Pending</x-ui-badge>
                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
