<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- Welcome header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d F Y') }} &mdash; Admin overview</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white hover:bg-sky-600 transition-colors shadow-sm">
            <x-nav-icon name="user" class="h-4 w-4" />
            New user
        </a>
    </div>

    {{-- KPI tiles --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Total users"       :value="number_format($stats['total_users'])"         icon="users"   icon-color="blue" />
        <x-card-stat-card label="Pending vendors"   :value="(string) $stats['pending_vendor_apps']"       icon="store"   icon-color="amber" />
        <x-card-stat-card label="Orders today"      :value="(string) $stats['orders_today']"              icon="box"     icon-color="emerald" />
        <x-card-stat-card label="Open disputes"     :value="(string) $stats['disputes_open']"             icon="flag"    icon-color="red" />
    </div>

    {{-- Quick-access cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('admin.vendors.index') }}"
            class="group rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-6 hover:ring-sky-300 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <x-nav-icon name="store" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-slate-900">Vendor applications</span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">Review and approve pending seller applications.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-xs text-sky-600 font-medium group-hover:gap-2 transition-all">
                Review now <x-nav-icon name="chevron-r" class="h-3 w-3" />
            </span>
        </a>

        <a href="{{ route('verifier.queue') }}"
            class="group rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-6 hover:ring-sky-300 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <x-nav-icon name="shield" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-slate-900">Verification queue</span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">Inspect devices awaiting Trust Certification.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-xs text-sky-600 font-medium group-hover:gap-2 transition-all">
                Open queue <x-nav-icon name="chevron-r" class="h-3 w-3" />
            </span>
        </a>

        <a href="{{ route('admin.disputes.index') }}"
            class="group rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-6 hover:ring-sky-300 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <x-nav-icon name="flag" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-slate-900">Open disputes</span>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">Manage escalated buyer–seller disputes.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-xs text-sky-600 font-medium group-hover:gap-2 transition-all">
                Manage <x-nav-icon name="chevron-r" class="h-3 w-3" />
            </span>
        </a>
    </div>

    {{-- Recent registrations --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent registrations</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">
                View all
            </a>
        </div>

        @if($recentUsers->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="users" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">No users yet.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($recentUsers as $u)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-sky-600 text-xs font-semibold text-white uppercase">
                            {{ substr($u->name, 0, 2) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $u->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $u->email }}</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-3">
                            <x-ui-badge color="stone" size="xs">{{ $u->roleName()?->label() ?? 'Unknown' }}</x-ui-badge>
                            <span class="text-xs text-slate-400">{{ $u->created_at->diffForHumans() }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
