<x-layouts.dashboard>
    <x-slot:title>Vendor Manager</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Vendor Manager overview</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-sky-400">
            <x-nav-icon name="store" class="h-4 w-4" />
            Review applications
        </a>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card label="Pending applications" :value="(string) $stats['pending_applications']" icon="store"   icon-color="amber" />
        <x-card-stat-card label="Active vendors"        :value="(string) $stats['active_vendors']"       icon="users"   icon-color="blue" />
        <x-card-stat-card label="Suspended vendors"     :value="(string) $stats['suspended_vendors']"    icon="flag"    icon-color="red" />
        <x-card-stat-card label="Approvals this week"   :value="(string) $stats['approvals_this_week']"  icon="package" icon-color="emerald" />
    </div>

    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Pending vendor applications</h3>
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        @if($pendingVendors->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="store" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">No pending applications — you're all caught up.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($pendingVendors as $vendor)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $vendor->name }}</p>
                            <p class="text-xs text-slate-500">Submitted {{ $vendor->created_at->diffForHumans() }}</p>
                        </div>
                        <x-ui-badge color="amber" size="xs">Pending</x-ui-badge>
                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">Review →</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
