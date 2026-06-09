<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    @php $firstName = explode(' ', auth()->user()->name)[0]; @endphp

    {{-- ── GREETING ─────────────────────────────────────────────────────── --}}
    <div class="mb-8">
        <p class="section-label mb-1.5">{{ now()->format('l, d F Y') }}</p>
        <h1 class="text-xl font-semibold text-white tracking-tight">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ $firstName }}
        </h1>
    </div>

    {{-- ── KPI TILES ────────────────────────────────────────────────────── --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-card-stat-card
            label="Active orders"
            :value="(string) $stats['active_orders']"
            icon="box"
            icon-color="blue"
            :glow-first="true"
            style="animation-delay: 0ms"
        />
        <x-card-stat-card
            label="Total purchases"
            :value="(string) $stats['total_orders']"
            icon="layers"
            icon-color="emerald"
            :sparkline="implode(',', $chartData)"
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

    {{-- ── NOTICE STRIPS ────────────────────────────────────────────────── --}}
    <div class="mb-6 space-y-2">
        <div class="notice-strip border-sky-400/40">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-sky-400"></span>
            <p class="text-slate-300">Every device on Shoppa is physically inspected before listing</p>
            <a href="{{ route('buyer.browse') }}" class="ml-auto shrink-0 text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">
                Browse now →
            </a>
        </div>
    </div>

    {{-- ── RECENT ORDERS ────────────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-xl bg-slate-800/60 ring-1 ring-white/[0.06]">
        <div class="flex items-center justify-between border-b border-white/[0.05] px-6 py-4">
            <h3 class="text-sm font-semibold text-white">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-sky-400 transition-colors hover:text-sky-300">View all</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                {{-- Empty state SVG: stylized inbox/box --}}
                <svg class="h-20 w-20 text-slate-700" viewBox="0 0 80 80" fill="none" aria-hidden="true">
                    <rect x="10" y="30" width="60" height="38" rx="6" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M10 44h18l4 6h16l4-6h18" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M28 20 L40 10 L52 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="40" y1="10" x2="40" y2="30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="40" cy="53" r="3" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <p class="mt-4 text-sm font-semibold text-slate-300">No orders yet</p>
                <p class="mt-1 text-xs text-slate-500 max-w-xs">Your order history will appear here once you make your first purchase on Shoppa.</p>
                <a href="{{ route('buyer.browse') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-lg bg-sky-500/15 px-4 py-2 text-sm font-medium text-sky-400
                          ring-1 ring-sky-500/25 transition-all hover:bg-sky-500/25 hover:text-sky-300">
                    <x-nav-icon name="search" class="h-4 w-4" />
                    Browse devices
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-white/[0.04]">
                @foreach($recentOrders as $order)
                    @php
                        $statusName  = $order->status?->name ?? 'pending';
                        $statusLabel = ucfirst(str_replace('_', ' ', $statusName));
                        $borderColor = match(true) {
                            in_array($statusName, ['completed', 'delivered']) => 'border-l-emerald-500',
                            in_array($statusName, ['shipped', 'processing'])  => 'border-l-sky-500',
                            in_array($statusName, ['cancelled', 'disputed'])  => 'border-l-red-500',
                            default                                           => 'border-l-amber-500',
                        };
                        $badgeColor = match(true) {
                            in_array($statusName, ['completed', 'delivered']) => 'emerald',
                            in_array($statusName, ['shipped', 'processing'])  => 'blue',
                            in_array($statusName, ['cancelled', 'disputed'])  => 'red',
                            default                                           => 'stone',
                        };
                        $itemName = $order->items->first()?->product?->name ?? 'Order #' . $order->id;
                    @endphp
                    <li class="flex items-center gap-4 border-l-2 {{ $borderColor }} py-3.5 pl-5 pr-6 transition-colors hover:bg-white/[0.03]">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $itemName }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">ORD-{{ $order->id }} &bull; {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <span class="text-sm font-semibold tabular-nums text-white">KSh {{ number_format($order->total_amount) }}</span>
                            <x-ui-badge :color="$badgeColor" size="xs">{{ $statusLabel }}</x-ui-badge>
                            <a href="{{ route('buyer.orders.index') }}" class="text-xs font-medium text-sky-400 hover:text-sky-300">View →</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>
