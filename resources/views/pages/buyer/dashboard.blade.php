{{-- DS buyer dashboard: greeting, 4-stat grid, trust callout, recent orders --}}
<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── GREETING ─────────────────────────────────────────────────────── --}}
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-stone-900 tracking-tight">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
        </h1>
        <p class="mt-1 text-sm text-stone-500">{{ now()->format('l, d F Y') }}</p>
    </div>

    {{-- ── KPI TILES — 4-across, white DS cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Active orders"
            :value="(string) $stats['active_orders']"
            icon="box"
            icon-color="blue"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Total purchases"
            :value="(string) $stats['total_orders']"
            icon="layers"
            icon-color="emerald"
            style="animation-delay: 100ms"
        />
        <x-card-stat-card
            label="Wishlist"
            :value="(string) $stats['wishlist_count']"
            icon="search"
            icon-color="purple"
            style="animation-delay: 200ms"
        />
        <x-card-stat-card
            label="Completed orders"
            :value="(string) $stats['devices_verified']"
            icon="shield"
            icon-color="emerald"
            style="animation-delay: 300ms"
        />
    </div>

    {{-- ── DS TRUST CALLOUT: emerald-50 bg, emerald-200 border, verified-pill lg --}}
    <div class="mb-8 flex items-start gap-4 rounded-xl border border-emerald-200 bg-emerald-50 p-5">
        <x-trust-verified-pill size="lg" />
        <div>
            <p class="text-sm font-semibold text-emerald-900">Every device on Shoppa is physically inspected</p>
            <p class="mt-1 text-sm text-emerald-700">
                Before a listing goes live, our verification team checks IMEI legitimacy, hardware authenticity, and condition grading.
                <a href="{{ route('buyer.browse') }}" class="font-semibold text-emerald-800 underline underline-offset-2">Browse verified devices →</a>
            </p>
        </div>
    </div>

    {{-- ── RECENT ORDERS — white DS card --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
            <h3 class="text-sm font-semibold text-stone-900">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-emerald-600 transition-colors hover:text-emerald-700">View all →</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <svg class="h-20 w-20 text-stone-300" viewBox="0 0 80 80" fill="none" aria-hidden="true">
                    <rect x="10" y="30" width="60" height="38" rx="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M10 44h18l4 6h16l4-6h18" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M28 20 L40 10 L52 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="40" y1="10" x2="40" y2="30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="40" cy="53" r="3" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <p class="mt-4 text-sm font-semibold text-stone-700">No orders yet</p>
                <p class="mt-1 text-xs text-stone-400 max-w-xs">Your order history will appear here once you make your first purchase on Shoppa.</p>
                <a href="{{ route('buyer.browse') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700
                          ring-1 ring-emerald-200 transition-all hover:bg-emerald-100">
                    <x-nav-icon name="search" class="h-4 w-4" />
                    Browse devices
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-stone-100">
                @foreach($recentOrders as $order)
                    @php
                        $statusName  = $order->status?->name ?? 'pending';
                        $statusLabel = ucfirst(str_replace('_', ' ', $statusName));
                        $badgeColor = match(true) {
                            in_array($statusName, ['completed', 'delivered']) => 'emerald',
                            in_array($statusName, ['shipped', 'processing'])  => 'blue',
                            in_array($statusName, ['cancelled', 'disputed'])  => 'red',
                            default                                           => 'amber',
                        };
                        $itemName = $order->items->first()?->product?->name ?? 'Order #' . $order->id;
                    @endphp
                    <li class="flex items-center gap-4 py-3.5 pl-6 pr-6 transition-colors hover:bg-stone-50">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-stone-900">{{ $itemName }}</p>
                            {{-- DS meta: xs stone-400, mono order ref --}}
                            <p class="mt-0.5 text-xs text-stone-400 font-mono">ORD-{{ $order->id }} &middot; {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <span class="text-sm font-semibold tabular-nums text-stone-900">KSh {{ number_format($order->total_amount) }}</span>
                            <x-ui-badge :color="$badgeColor" size="xs">{{ $statusLabel }}</x-ui-badge>
                            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
