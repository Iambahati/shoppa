<x-layouts.app>
    <x-slot:title>Order #{{ $order->id }}</x-slot:title>

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('buyer.orders.index') }}" class="text-sm text-stone-400 hover:text-stone-600 transition-colors flex items-center gap-1">
            <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Order items --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-stone-100">
                    <h3 class="text-sm font-semibold text-stone-900">Order #{{ $order->id }}</h3>
                </div>
                <ul role="list" class="divide-y divide-stone-100">
                    @foreach($order->items as $item)
                    <li class="px-5 py-4 flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-stone-100">
                            <x-nav-icon name="package" class="h-5 w-5 text-stone-400" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-stone-900 truncate">{{ $item->product?->name ?? 'Product removed' }}</p>
                            <p class="text-xs text-stone-400 mt-0.5">Qty: {{ $item->quantity }} &middot; {{ 'KSh ' . number_format($item->price) }} each</p>
                        </div>
                        <p class="text-sm font-medium text-stone-900 shrink-0">{{ $item->lineTotal() }}</p>
                    </li>
                    @endforeach
                </ul>
                <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between">
                    <span class="text-sm font-medium text-stone-700">Total</span>
                    <span class="text-base font-semibold text-stone-900">{{ $order->formattedTotal() }}</span>
                </div>
            </div>

            {{-- Escrow info --}}
            @if($order->status?->name === 'delivered')
            <x-ui-alert type="info">
                <div>
                    <p class="font-medium">Your payment is held in escrow</p>
                    <p class="mt-0.5">Funds will be released to the seller in {{ config('shoppa.escrow.release_after_days') }} days unless you raise a dispute. If the device is as described, no action is needed.</p>
                    <div class="mt-3 flex gap-3">
                        <form method="POST" action="{{ route('buyer.orders.confirm', $order) }}">
                            @csrf
                            <x-ui-button type="submit" size="sm">Confirm receipt</x-ui-button>
                        </form>
                        <form method="POST" action="{{ route('buyer.orders.dispute', $order) }}">
                            @csrf
                            <x-ui-button type="submit" variant="danger" size="sm">Raise dispute</x-ui-button>
                        </form>
                    </div>
                </div>
            </x-ui-alert>
            @endif
        </div>

        {{-- Sidebar: status + shipment --}}
        <div class="space-y-4">
            <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-stone-900 mb-3">Order status</h4>
                @php
                $statusColor = match($order->status?->name) {
                'completed' => 'emerald',
                'processing' => 'blue',
                'cancelled' => 'red',
                'disputed' => 'amber',
                default => 'stone',
                };
                @endphp
                <x-ui-badge :color="$statusColor" size="md">{{ $order->status?->name ?? 'pending' }}</x-ui-badge>
                <p class="mt-3 text-xs text-stone-400">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            @if($order->shipment)
            <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-stone-900 mb-3">Delivery</h4>
                <p class="text-sm text-stone-700">{{ $order->shipment->carrier }}</p>
                <p class="text-xs text-stone-400 mt-1 font-mono">{{ $order->shipment->tracking_number }}</p>
                <x-ui-badge color="blue" class="mt-2">{{ $order->shipment->status?->name ?? '—' }}</x-ui-badge>
                @if($order->shipment->delivered_at)
                <p class="mt-2 text-xs text-emerald-600 font-medium">
                    Delivered {{ $order->shipment->delivered_at->format('d M Y') }}
                </p>
                @endif
            </div>
            @endif

            @if($order->isCancellable())
            <form method="POST" action="{{ route('buyer.orders.cancel', $order) }}"
                onsubmit="return confirm('Cancel this order?')">
                @csrf
                <x-ui-button type="submit" variant="danger" size="sm" class="w-full justify-center">
                    Cancel order
                </x-ui-button>
            </form>
            @endif
        </div>

    </div>
</x-layouts.app>