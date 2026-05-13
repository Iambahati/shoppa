<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    {{-- Welcome header --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-stone-900">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
            {{ explode(' ', $user->name)[0] }}
        </h2>
        <p class="mt-1 text-sm text-stone-500">
            Here's what's happening with your account.
        </p>
    </div>

    {{-- Stats row --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
        <x-card.stat-card
            label="Active orders"
            :value="(string) $stats['active_orders']"
            icon="box"
            icon-color="blue"
        />
        <x-card.stat-card
            label="Total orders"
            :value="(string) $stats['total_orders']"
            icon="layers"
            icon-color="emerald"
        />
        <x-card.stat-card
            label="Wishlist"
            :value="(string) $stats['wishlist_count']"
            icon="search"
            icon-color="purple"
        />
        <x-card.stat-card
            label="Verified devices"
            :value="(string) $stats['devices_verified']"
            icon="shield"
            icon-color="emerald"
        />
    </div>

    {{-- Trust callout --}}
    <div class="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 p-5 flex items-start gap-4">
        <x-trust.verified-pill size="lg" class="shrink-0 mt-0.5" />
        <div>
            <p class="text-sm font-medium text-emerald-900">Every device on Shoppa is physically inspected</p>
            <p class="mt-0.5 text-sm text-emerald-700">
                Before a listing goes live, our verification team checks IMEI legitimacy, hardware authenticity, and condition grading.
                <a href="{{ route('buyer.browse') }}" class="font-medium underline underline-offset-2 hover:no-underline">Browse verified devices →</a>
            </p>
        </div>
    </div>

    {{-- Recent orders --}}
    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-stone-900">Recent orders</h3>
            <a href="{{ route('buyer.orders.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                View all
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="px-5 py-12 text-center">
                <x-nav.icon name="box" class="mx-auto h-8 w-8 text-stone-300" />
                <p class="mt-3 text-sm text-stone-500">No orders yet.</p>
                <a href="{{ route('buyer.browse') }}" class="mt-3 inline-block">
                    <x-ui.button variant="secondary" size="sm">Browse devices</x-ui.button>
                </a>
            </div>
        @else
            <ul role="list" class="divide-y divide-stone-100">
                @foreach($recentOrders as $order)
                    <li class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-stone-50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-stone-900">Order #{{ $order->id }}</p>
                            <p class="text-xs text-stone-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-ui.badge color="stone">{{ $order->status->name ?? '—' }}</x-ui.badge>
                            <a href="{{ route('buyer.orders.show', $order) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                                View →
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</x-layouts.app>