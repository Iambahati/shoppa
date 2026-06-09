<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
        </h2>
        <p class="mt-1 text-sm text-slate-400">{{ now()->format('l, d F Y') }}</p>
    </div>

    {{-- KPI tiles --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card label="Active orders"        :value="(string) $stats['active_orders']"    icon="box"    icon-color="blue" />
        <x-card-stat-card label="Total purchases"      :value="(string) $stats['total_orders']"     icon="layers" icon-color="emerald" />
        <x-card-stat-card label="Wishlist"             :value="(string) $stats['wishlist_count']"   icon="search" icon-color="purple" />
        <x-card-stat-card label="Verified devices"     :value="(string) $stats['devices_verified']" icon="shield" icon-color="emerald" />
    </div>

    {{-- Trust callout --}}
    <div class="mb-8 flex items-start gap-4 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-6">
        <x-trust-verified-pill size="lg" class="mt-0.5 shrink-0" />
        <div>
            <p class="text-sm font-semibold text-emerald-300">Every device on Shoppa is physically inspected</p>
            <p class="mt-1 text-sm leading-relaxed text-emerald-400">
                Our verification team checks IMEI legitimacy, hardware authenticity, and condition grading before any listing goes live.
                <a href="{{ route('buyer.browse') }}" class="font-medium underline underline-offset-2 hover:no-underline">Browse verified devices →</a>
            </p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('buyer.browse') }}"
            class="group flex items-center gap-4 rounded-2xl bg-slate-800 p-5 ring-1 ring-white/5 transition-all hover:bg-slate-700 hover:ring-sky-500/40">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-500/15 text-sky-400">
                <x-nav-icon name="search" class="h-5 w-5" />
            </span>
            <div>
                <p class="text-sm font-semibold text-white">Browse devices</p>
                <p class="mt-0.5 text-xs text-slate-400">Discover verified phones &amp; electronics</p>
            </div>
            <x-nav-icon name="chevron-r" class="ml-auto h-4 w-4 text-slate-600 transition-colors group-hover:text-sky-400" />
        </a>

        <a href="{{ route('buyer.orders.index') }}"
            class="group flex items-center gap-4 rounded-2xl bg-slate-800 p-5 ring-1 ring-white/5 transition-all hover:bg-slate-700 hover:ring-sky-500/40">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-400">
                <x-nav-icon name="box" class="h-5 w-5" />
            </span>
            <div>
                <p class="text-sm font-semibold text-white">Track orders</p>
                <p class="mt-0.5 text-xs text-slate-400">View status and delivery updates</p>
            </div>
            <x-nav-icon name="chevron-r" class="ml-auto h-4 w-4 text-slate-600 transition-colors group-hover:text-sky-400" />
        </a>
    </div>

    {{-- Recent orders --}}
    <div class="overflow-hidden rounded-2xl bg-slate-800 ring-1 ring-white/5">
        <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="box" class="mx-auto h-8 w-8 text-slate-600" />
                <p class="mt-3 text-sm text-slate-400">No orders yet.</p>
                <a href="{{ route('buyer.browse') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-sky-400 hover:text-sky-300">
                    Browse devices →
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/5">
                @foreach($recentOrders as $order)
                    <li class="flex items-center gap-4 px-6 py-3.5 transition-colors hover:bg-white/5">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-white">Order #{{ $order->id }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <x-ui-badge color="blue" size="xs">{{ $order->status?->name ?? '—' }}</x-ui-badge>
                            <a href="{{ route('buyer.orders.show', $order) }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
