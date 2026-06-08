<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    {{-- Welcome header --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-stone-900">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ explode(' ', $user->name)[0] }}
        </h2>
        <p class="mt-1 text-sm text-stone-500">
            Here's what's happening with your account. {{ now()->format('l, d F Y') }}
        </p>
    </div>

     <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
        <x-card-stat-card label="Total users"       :value="number_format($stats['total_users'])"         icon="users"   icon-color="blue" />
        <x-card-stat-card label="Pending vendors"   :value="(string) $stats['pending_vendor_apps']"       icon="store"   icon-color="amber" />
        <x-card-stat-card label="Orders today"      :value="(string) $stats['orders_today']"              icon="box"     icon-color="emerald" />
        <x-card-stat-card label="Open disputes"     :value="(string) $stats['disputes_open']"             icon="flag"    icon-color="red" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.vendors.index') }}" class="group rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-5 hover:ring-emerald-400 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <x-nav-icon name="store" class="h-4 w-4" />
                </span>
                <span class="text-sm font-semibold text-stone-900">Vendor applications</span>
            </div>
            <p class="text-xs text-stone-500">Review and approve pending seller applications.</p>
            <span class="mt-3 inline-flex items-center gap-1 text-xs text-emerald-600 font-medium group-hover:gap-2 transition-all">Review now <x-nav-icon name="chevron-r" class="h-3 w-3" /></span>
        </a>

        <a href="{{ route('verifier.queue') }}" class="group rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-5 hover:ring-emerald-400 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <x-nav-icon name="shield" class="h-4 w-4" />
                </span>
                <span class="text-sm font-semibold text-stone-900">Verification queue</span>
            </div>
            <p class="text-xs text-stone-500">Inspect devices awaiting Trust Certification.</p>
            <span class="mt-3 inline-flex items-center gap-1 text-xs text-emerald-600 font-medium group-hover:gap-2 transition-all">Open queue <x-nav-icon name="chevron-r" class="h-3 w-3" /></span>
        </a>

        <a href="{{ route('admin.disputes.index') }}" class="group rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-5 hover:ring-emerald-400 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <x-nav-icon name="flag" class="h-4 w-4" />
                </span>
                <span class="text-sm font-semibold text-stone-900">Open disputes</span>
            </div>
            <p class="text-xs text-stone-500">Manage escalated buyer–seller disputes.</p>
            <span class="mt-3 inline-flex items-center gap-1 text-xs text-emerald-600 font-medium group-hover:gap-2 transition-all">Manage <x-nav-icon name="chevron-r" class="h-3 w-3" /></span>
        </a>
    </div>

</x-layouts.dashboard>