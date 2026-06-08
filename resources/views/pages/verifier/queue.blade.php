<x-layouts.dashboard>
    <x-slot:title>Verification queue</x-slot:title>

    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">Verification queue</h2>
            <p class="mt-1 text-sm text-stone-500">
                Devices awaiting physical inspection and Trust Certification.
            </p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <x-card.stat-card
            label="Pending inspection"
            :value="(string) $stats['pending']"
            icon="shield"
            icon-color="amber"
        />
        <x-card.stat-card
            label="Under review"
            :value="(string) $stats['in_review']"
            icon="cpu"
            icon-color="blue"
        />
        <x-card.stat-card
            label="Certified today"
            :value="(string) $stats['today']"
            icon="check"
            icon-color="emerald"
        />
    </div>

    {{-- Queue table --}}
    <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-stone-100">
            <h3 class="text-sm font-semibold text-stone-900">Pending devices</h3>
        </div>

        @if($queue->isEmpty())
            <div class="px-5 py-16 text-center">
                <span class="flex h-12 w-12 mx-auto items-center justify-center rounded-full bg-emerald-50">
                    <x-nav.icon name="shield" class="h-6 w-6 text-emerald-500" />
                </span>
                <p class="mt-4 text-sm font-medium text-stone-700">Queue is clear</p>
                <p class="mt-1 text-sm text-stone-400">All submitted devices have been processed.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-stone-50">
                        <tr>
                            @foreach(['Device', 'Seller', 'Submitted', 'IMEI', 'Status', ''] as $heading)
                                <th class="px-5 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wide">
                                    {{ $heading }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($queue as $product)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-medium text-stone-900">{{ $product->name }}</td>
                                <td class="px-5 py-4 text-sm text-stone-500">{{ $product->vendor?->name }}</td>
                                <td class="px-5 py-4 text-sm text-stone-400">{{ $product->created_at->diffForHumans() }}</td>
                                <td class="px-5 py-4 text-xs font-mono text-stone-500">{{ $product->imei ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    <x-trust.cert-badge :status="$product->verification_status" />
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('verifier.inspections.show', $product) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                        Inspect →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts.dashboard>
