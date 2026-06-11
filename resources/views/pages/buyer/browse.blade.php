<x-layouts.app>
    <x-slot:title>Browse verified devices</x-slot:title>

    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-stone-900">Browse devices</h2>
            <p class="mt-1 text-sm text-stone-500">
                Every listing below has been physically inspected by the Shoppa verification team.
            </p>
        </div>
        <x-trust.verified-pill size="lg" />
    </div>

    @if($products->isEmpty())
        <div class="card py-20 text-center">
            <span class="flex h-14 w-14 mx-auto items-center justify-center rounded-full bg-stone-100">
                <x-nav.icon name="search" class="h-7 w-7 text-stone-400" />
            </span>
            <p class="mt-4 text-sm font-medium text-stone-700">No devices listed yet</p>
            <p class="mt-1 text-sm text-stone-400 max-w-xs mx-auto">
                Verified listings will appear here once sellers complete verification.
            </p>
        </div>
    @else
        {{-- Sprint 3: product grid renders here --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {{-- @foreach($products as $product) <x-card.device-card :product="$product" /> @endforeach --}}
        </div>
    @endif

</x-layouts.app>
