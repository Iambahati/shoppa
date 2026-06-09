<x-layouts.dashboard>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- Welcome header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
            </h2>
            <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }} &mdash; Admin overview</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-sky-400">
            <x-nav-icon name="user" class="h-4 w-4" />
            New user
        </a>
    </div>

    {{-- KPI tiles --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card label="Total users"       :value="number_format($stats['total_users'])"         icon="users"   icon-color="blue" />
        <x-card-stat-card label="Pending vendors"   :value="(string) $stats['pending_vendor_apps']"       icon="store"   icon-color="amber" />
        <x-card-stat-card label="Orders today"      :value="(string) $stats['orders_today']"              icon="box"     icon-color="emerald" />
        <x-card-stat-card label="Open disputes"     :value="(string) $stats['disputes_open']"             icon="flag"    icon-color="red" />
    </div>

    {{-- Quick-access cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('admin.vendors.index') }}"
            class="group rounded-2xl bg-slate-800 ring-1 ring-white/5 p-6 transition-all hover:ring-sky-500/40 hover:bg-slate-700">
            <div class="mb-3 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/15 text-amber-400">
                    <x-nav-icon name="store" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-white">Vendor applications</span>
            </div>
            <p class="text-xs leading-relaxed text-slate-400">Review and approve pending seller applications.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-sky-400 transition-all group-hover:gap-2">
                Review now <x-nav-icon name="chevron-r" class="h-3 w-3" />
            </span>
        </a>

        <a href="{{ route('verifier.queue') }}"
            class="group rounded-2xl bg-slate-800 ring-1 ring-white/5 p-6 transition-all hover:ring-sky-500/40 hover:bg-slate-700">
            <div class="mb-3 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-400">
                    <x-nav-icon name="shield" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-white">Verification queue</span>
            </div>
            <p class="text-xs leading-relaxed text-slate-400">Inspect devices awaiting Trust Certification.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-sky-400 transition-all group-hover:gap-2">
                Open queue <x-nav-icon name="chevron-r" class="h-3 w-3" />
            </span>
        </a>

        <a href="{{ route('admin.disputes.index') }}"
            class="group rounded-2xl bg-slate-800 ring-1 ring-white/5 p-6 transition-all hover:ring-sky-500/40 hover:bg-slate-700">
            <div class="mb-3 flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/15 text-red-400">
                    <x-nav-icon name="flag" class="h-5 w-5" />
                </span>
                <span class="text-sm font-semibold text-white">Open disputes</span>
            </div>
            <p class="text-xs leading-relaxed text-slate-400">Manage escalated buyer–seller disputes.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-sky-400 transition-all group-hover:gap-2">
                Manage <x-nav-icon name="chevron-r" class="h-3 w-3" />
            </span>
        </a>
    </div>

    {{-- Recent registrations --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Recent registrations</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">
                View all
            </a>
        </div>

        @if($recentUsers->isEmpty())
            <div class="px-6 py-12 text-center">
                <x-nav-icon name="users" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">No users yet.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($recentUsers as $u)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-sky-600 text-xs font-semibold uppercase text-white">
                            {{ substr($u->name, 0, 2) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $u->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $u->email }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <x-ui-badge color="blue" size="xs">{{ $u->roleName()?->label() ?? 'Unknown' }}</x-ui-badge>
                            <span class="text-xs text-slate-500">{{ $u->created_at->diffForHumans() }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.dashboard>
