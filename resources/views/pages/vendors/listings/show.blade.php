<x-layouts.app>
    <x-slot:title>{{ $product->name }}</x-slot:title>

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('vendor.listings.index') }}" class="text-sm text-slate-400 hover:text-slate-200 transition-colors flex items-center gap-1">
            <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to listings
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main details --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="rounded-2xl bg-slate-800 ring-1 ring-white/5 p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <h2 class="text-lg font-semibold text-white">{{ $product->name }}</h2>
                    <a href="{{ route('vendor.listings.edit', $product) }}">
                        <x-ui-button variant="secondary" size="sm">Edit listing</x-ui-button>
                    </a>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    <x-trust-cert-badge :status="$product->verification_status ?? 'unverified'"
                        :cert-id="$product->trust_cert_uuid"
                        :issued-at="$product->cert_issued_at?->toDateString()" />
                    <x-ui-badge color="stone">{{ ucfirst($product->condition_grade ?? '—') }}</x-ui-badge>
                    <x-ui-badge color="blue">{{ ucfirst($product->device_type ?? '—') }}</x-ui-badge>
                </div>

                <p class="text-sm text-slate-300 leading-relaxed">{{ $product->description }}</p>
            </div>

            {{-- Device identifiers --}}
            <div class="rounded-2xl bg-slate-800 ring-1 ring-white/5 p-6">
                <h3 class="text-sm font-semibold text-white mb-4">Device identifiers</h3>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">IMEI</dt>
                        <dd class="mt-1 text-sm font-mono text-white">{{ $product->imei ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">Serial number</dt>
                        <dd class="mt-1 text-sm font-mono text-white">{{ $product->serial_number ?? '—' }}</dd>
                    </div>
                    @if($product->battery_health)
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">Battery health</dt>
                        <dd class="mt-1 text-sm font-medium {{ $product->battery_health >= 80 ? 'text-emerald-400' : 'text-amber-400' }}">
                            {{ $product->battery_health }}%
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Verification status callout --}}
            @if(($product->verification_status ?? 'unverified') === 'pending')
            <x-ui-alert type="info">
                <div>
                    <p class="font-medium">Awaiting verification</p>
                    <p class="mt-0.5">
                        Send this device to the Shoppa verification centre.
                        Fee: KSh {{ number_format(config('shoppa.verification.fee_min_ksh')) }}–{{ number_format(config('shoppa.verification.fee_max_ksh')) }}.
                        The listing will go live once a Trust Certificate is issued.
                    </p>
                </div>
            </x-ui-alert>
            @elseif(($product->verification_status ?? '') === 'rejected')
            <x-ui-alert type="error">
                <div>
                    <p class="font-medium">Verification failed</p>
                    <p class="mt-0.5">This device did not pass inspection. Contact support for details, or submit a corrected device.</p>
                </div>
            </x-ui-alert>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">

            <div class="rounded-2xl bg-slate-800 ring-1 ring-white/5 p-5">
                <h4 class="text-sm font-semibold text-white mb-4">Pricing &amp; stock</h4>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">Price</dt>
                        <dd class="mt-1 text-xl font-semibold text-white">{{ $product->formattedPrice() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">Stock</dt>
                        <dd class="mt-1 text-sm text-slate-300">{{ $product->quantity }} unit(s)</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">Category</dt>
                        <dd class="mt-1 text-sm text-slate-300">{{ $product->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400 uppercase tracking-wide">Listed</dt>
                        <dd class="mt-1 text-sm text-slate-300">{{ $product->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            <form method="POST" action="{{ route('vendor.listings.destroy', $product) }}"
                onsubmit="return confirm('Remove this listing permanently?')">
                @csrf @method('DELETE')
                <x-ui-button type="submit" variant="danger" class="w-full justify-center">
                    Remove listing
                </x-ui-button>
            </form>

        </div>

    </div>
</x-layouts.app>
