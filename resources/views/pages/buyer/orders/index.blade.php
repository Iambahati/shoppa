<x-layouts.app>
    <x-slot:title>My orders</x-slot:title>

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-stone-900">My orders</h2>
        <p class="mt-1 text-sm text-stone-500">All your purchases — past and present.</p>
    </div>

    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        @if($orders->isEmpty())
        <div class="py-16 text-center">
            <x-nav-icon name="box" class="mx-auto h-10 w-10 text-stone-300" />
            <p class="mt-4 text-sm font-medium text-stone-700">No orders yet</p>
            <p class="mt-1 text-sm text-stone-400">When you purchase a device, your orders will appear here.</p>
            <div class="mt-5">
                <a href="{{ route('buyer.browse') }}"><x-ui-button variant="secondary" size="sm">Browse verified devices</x-ui-button></a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-100">
                <thead class="bg-stone-50">
                    <tr>
                        @foreach(['Order', 'Items', 'Total', 'Status', 'Date', ''] as $h)
                        <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-5 py-4 text-sm font-medium text-stone-900">#{{ $order->id }}</td>
                        <td class="px-5 py-4 text-sm text-stone-500">{{ $order->items->count() }} item(s)</td>
                        <td class="px-5 py-4 text-sm font-medium text-stone-900">{{ $order->formattedTotal() }}</td>
                        <td class="px-5 py-4">
                            @php
                            $statusColor = match($order->status?->name) {
                            'completed' => 'emerald',
                            'processing' => 'blue',
                            'cancelled' => 'red',
                            'disputed' => 'amber',
                            default => 'stone',
                            };
                            @endphp
                            <x-ui-badge :color="$statusColor">{{ $order->status?->name ?? 'pending' }}</x-ui-badge>
                        </td>
                        <td class="px-5 py-4 text-sm text-stone-400">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('buyer.orders.show', $order) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">View →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-stone-100">{{ $orders->links() }}</div>
        @endif
        @endif
    </div>
</x-layouts.app>