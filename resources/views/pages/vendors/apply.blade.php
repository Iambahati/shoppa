<x-layouts.app>
    <x-slot:title>Apply to sell on Shoppa</x-slot:title>

    <div class="mx-auto max-w-2xl">

        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-stone-900">Apply to sell on Shoppa</h2>
            <p class="mt-1 text-sm text-stone-500">
                Join Kenya's only verified electronics marketplace. Reach buyers who trust your devices are genuine.
            </p>
        </div>

        {{-- What happens next --}}
        <div class="mb-8 card p-5">
            <p class="text-xs font-semibold text-stone-500 uppercase tracking-wide mb-3">What happens after you apply</p>
            <ol class="space-y-3">
                @foreach([
                    ['icon' => 'user',   'text' => 'We review your application within 2–3 business days.'],
                    ['icon' => 'shield', 'text' => 'Send your devices to our verification centre. We inspect and certify each one.'],
                    ['icon' => 'store',  'text' => 'Verified devices go live on Shoppa and reach thousands of buyers.'],
                    ['icon' => 'box',    'text' => 'Orders come in, we handle escrow, you get paid after delivery confirmation.'],
                ] as $i => $step)
                    <li class="flex items-start gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold mt-0.5">
                            {{ $i + 1 }}
                        </span>
                        <p class="text-sm text-stone-600">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- Form --}}
        <div class="card p-6">
            <form method="POST" action="{{ route('vendor.apply.store') }}" class="space-y-5">
                @csrf

                <x-form.field
                    name="shop_name"
                    label="Shop name"
                    type="text"
                    placeholder="e.g. Nairobi Tech Hub"
                    :required="true"
                    hint="This is what buyers will see on your listings."
                />

                <x-form.field
                    name="phone"
                    label="Business phone"
                    type="tel"
                    placeholder="+254 7XX XXX XXX"
                    :required="true"
                />

                <x-form.field
                    name="location"
                    label="Shop location"
                    type="text"
                    placeholder="e.g. CBD Nairobi, Luthuli Avenue"
                    :required="true"
                    hint="Physical address where devices can be picked up or verified."
                />

                <x-form.field
                    name="description"
                    label="About your shop"
                    type="textarea"
                    placeholder="Tell us about your business — what you sell, how long you have been operating, your return policy..."
                    :required="true"
                    hint="Minimum 20 characters. This builds trust with buyers."
                />

                <div class="pt-2 flex items-center justify-between gap-4">
                    <a href="{{ route('buyer.dashboard') }}" class="text-sm text-stone-500 hover:text-stone-700 transition-colors">
                        Cancel
                    </a>
                    <x-ui.button type="submit">
                        Submit application
                    </x-ui.button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.app>
