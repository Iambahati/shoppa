<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">{{ now()->format('l, d F Y') }}</p>
    </div>

    {{-- KPI tiles --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-card-stat-card label="Active orders"        :value="(string) $stats['active_orders']"    icon="box"    icon-color="blue" />
        <x-card-stat-card label="Total purchases"      :value="(string) $stats['total_orders']"     icon="layers" icon-color="emerald" />
        <x-card-stat-card label="Wishlist"             :value="(string) $stats['wishlist_count']"   icon="search" icon-color="purple" />
        <x-card-stat-card label="Verified devices"     :value="(string) $stats['devices_verified']" icon="shield" icon-color="emerald" />
    </div>

    {{-- Trust callout --}}
    <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 flex items-start gap-4">
        <x-trust-verified-pill size="lg" class="shrink-0 mt-0.5" />
        <div>
            <p class="text-sm font-semibold text-emerald-900">Every device on Shoppa is physically inspected</p>
            <p class="mt-1 text-sm text-emerald-700 leading-relaxed">
                Our verification team checks IMEI legitimacy, hardware authenticity, and condition grading before any listing goes live.
                <a href="{{ route('buyer.browse') }}" class="font-medium underline underline-offset-2 hover:no-underline">Browse verified devices →</a>
            </p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('buyer.browse') }}"
            class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-300 hover:shadow-md transition-all">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <x-nav-icon name="search" class="h-5 w-5" />
            </span>
            <div>
                <p class="text-sm font-semibold text-slate-900">Browse devices</p>
                <p class="text-xs text-slate-500 mt-0.5">Discover verified phones &amp; electronics</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 ml-auto group-hover:text-sky-400 transition-colors" />
        </a>

        <a href="{{ route('buyer.orders.index') }}"
            class="group flex items-center gap-4 rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm p-5 hover:ring-sky-300 hover:shadow-md transition-all">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <x-nav-icon name="box" class="h-5 w-5" />
            </span>
            <div>
                <p class="text-sm font-semibold text-slate-900">Track orders</p>
                <p class="text-xs text-slate-500 mt-0.5">View status and delivery updates</p>
            </div>
            <x-nav-icon name="chevron-r" class="h-4 w-4 text-slate-300 ml-auto group-hover:text-sky-400 transition-colors" />
        </a>
    </div>

    {{-- Recent orders --}}
    <div class="rounded-2xl bg-white ring-1 ring-slate-900/5 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium transition-colors">View all</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="px-6 py-14 text-center">
                <x-nav-icon name="box" class="mx-auto h-8 w-8 text-slate-300" />
                <p class="mt-3 text-sm text-slate-500">No orders yet.</p>
                <a href="{{ route('buyer.browse') }}" class="mt-3 inline-flex items-center gap-1 text-sm text-sky-600 font-medium hover:text-sky-700">
                    Browse devices →
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($recentOrders as $order)
                    <li class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900">Order #{{ $order->id }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <x-ui-badge color="stone" size="xs">{{ $order->status?->name ?? '—' }}</x-ui-badge>
                            <a href="{{ route('buyer.orders.show', $order) }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
