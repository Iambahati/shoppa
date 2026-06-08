<x-layouts.app>
    <x-slot:title>My listings</x-slot:title>

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">My listings</h2>
            <p class="mt-1 text-sm text-stone-500">Manage your device inventory.</p>
        </div>
        <a href="{{ route('vendor.listings.create') }}">
            <x-ui-button size="sm">
                <x-nav-icon name="layers" class="h-4 w-4" /> Add device
            </x-ui-button>
        </a>
    </div>

    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        @if($listings->isEmpty())
        <div class="py-16 text-center">
            <x-nav-icon name="layers" class="mx-auto h-10 w-10 text-stone-300" />
            <p class="mt-4 text-sm font-medium text-stone-700">No listings yet</p>
            <div class="mt-4">
                <a href="{{ route('vendor.listings.create') }}"><x-ui-button variant="secondary" size="sm">Add your first device</x-ui-button></a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-100">
                <thead class="bg-stone-50">
                    <tr>
                        @foreach(['Device', 'Price', 'Condition', 'Trust status', 'Stock', ''] as $h)
                        <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($listings as $listing)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-5 py-4">
                            <p class="text-sm font-medium text-stone-900">{{ $listing->name }}</p>
                            @if($listing->imei)
                            <p class="text-xs text-stone-400 font-mono">{{ $listing->imei }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm font-medium text-stone-900">{{ $listing->formattedPrice() }}</td>
                        <td class="px-5 py-4"><x-ui-badge color="stone">{{ ucfirst($listing->condition_grade ?? '—') }}</x-ui-badge></td>
                        <td class="px-5 py-4"><x-trust-cert-badge :status="$listing->verification_status ?? 'unverified'" /></td>
                        <td class="px-5 py-4 text-sm text-stone-500">{{ $listing->quantity }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('vendor.listings.edit', $listing) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">Edit</a>
                                <form method="POST" action="{{ route('vendor.listings.destroy', $listing) }}" class="inline"
                                    onsubmit="return confirm('Remove this listing?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($listings->hasPages())
        <div class="px-5 py-4 border-t border-stone-100">{{ $listings->links() }}</div>
        @endif
        @endif
    </div>
</x-layouts.app>