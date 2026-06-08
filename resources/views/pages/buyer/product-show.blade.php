<x-layouts.app>
    <x-slot:title>{{ $product->name ?? 'Device' }}</x-slot:title>

    <div class="mb-6">
        <a href="{{ route('buyer.browse') }}" class="text-sm text-stone-400 hover:text-stone-600 flex items-center gap-1 w-fit">
            <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to browse
        </a>
    </div>

    @isset($product)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Photos --}}
        <div class="space-y-4">
            <div class="aspect-square w-full rounded-2xl bg-stone-100 flex items-center justify-center ring-1 ring-stone-200">
                @php $media = $product->getFirstMedia('device_photos'); @endphp
                @if($media)
                <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-contain rounded-2xl" />
                @else
                <x-nav-icon name="package" class="h-20 w-20 text-stone-300" />
                @endif
            </div>
        </div>

        {{-- Details --}}
        <div class="space-y-5">

            {{-- Trust badge + title --}}
            <div>
                <x-trust-cert-badge
                    :status="$product->verification_status ?? 'unverified'"
                    :cert-id="$product->trust_cert_uuid"
                    :issued-at="$product->cert_issued_at?->toDateString()" />
                <h1 class="mt-3 text-2xl font-semibold text-stone-900">{{ $product->name }}</h1>
                <p class="mt-1 text-sm text-stone-500">Sold by <span class="font-medium text-stone-700">{{ $product->vendor?->name }}</span></p>
            </div>

            {{-- Price --}}
            <div class="flex items-end gap-3">
                <span class="text-3xl font-bold text-stone-900">{{ $product->formattedPrice() }}</span>
                <div class="flex flex-wrap gap-2 pb-1">
                    <x-ui-badge color="stone">{{ ucfirst($product->condition_grade ?? '—') }}</x-ui-badge>
                    <x-ui-badge color="blue">{{ ucfirst($product->device_type ?? '—') }}</x-ui-badge>
                    @if($product->battery_health)
                    <x-ui-badge :color="$product->battery_health >= 80 ? 'emerald' : 'amber'">
                        Battery {{ $product->battery_health }}%
                    </x-ui-badge>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <p class="text-sm text-stone-700 leading-relaxed">{{ $product->description }}</p>

            {{-- Escrow guarantee --}}
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 flex items-start gap-3">
                <x-nav-icon name="shield" class="h-5 w-5 shrink-0 text-emerald-600 mt-0.5" />
                <div>
                    <p class="text-sm font-medium text-emerald-900">Protected by Shoppa Escrow</p>
                    <p class="mt-0.5 text-xs text-emerald-700">
                        Your payment is held for {{ config('shoppa.escrow.release_after_days') }} days after delivery.
                        If the device doesn't match the listing, you get a full refund.
                    </p>
                </div>
            </div>

            {{-- CTA --}}
            @if($product->isVerified())
            <form method="POST" action="{{ route('buyer.orders.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <x-ui-button type="submit" class="w-full justify-center" size="lg">
                    Buy now — {{ $product->formattedPrice() }}
                </x-ui-button>
            </form>
            @else
            <x-ui-alert type="warning">
                This device is currently awaiting verification. Check back once it has been certified.
            </x-ui-alert>
            @endif

            {{-- Device identifiers (public-facing) --}}
            @if($product->trust_cert_uuid)
            <div class="rounded-xl bg-stone-50 border border-stone-200 p-4">
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-2">Trust Certificate</p>
                <p class="text-xs font-mono text-stone-700">{{ strtoupper($product->trust_cert_uuid) }}</p>
                <a href="{{ route('public.verify', $product->imei ?? $product->serial_number ?? $product->trust_cert_uuid) }}"
                    class="mt-2 inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                    <x-nav-icon name="qr" class="h-3.5 w-3.5" />
                    Verify on Shoppa
                </a>
            </div>
            @endif

        </div>
    </div>
    @else
    {{-- Placeholder until Sprint 3 wires real product queries --}}
    <x-ui-alert type="info">
        Full product detail page is wired in Sprint 3 (Product Catalog).
    </x-ui-alert>
    @endisset
</x-layouts.app>