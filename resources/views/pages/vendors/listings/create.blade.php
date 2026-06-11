<x-layouts.app>
    <x-slot:title>Add device</x-slot:title>

    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('vendor.listings.index') }}" class="text-sm text-stone-500 hover:text-stone-700 transition-colors flex items-center gap-1">
                <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to listings
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-stone-900">Add a device</h2>
            <p class="mt-1 text-sm text-stone-500">
                New listings are saved as drafts and hidden from buyers until a Shoppa verifier certifies the device.
            </p>
        </div>

        <div class="card p-6">
            <form method="POST" action="{{ route('vendor.listings.store') }}" class="space-y-5">
                @csrf

                <x-form-field name="name" label="Device name" type="text"
                    placeholder="e.g. Apple iPhone 15 Pro Max 256GB Natural Titanium" :required="true"
                    hint="Be specific — include brand, model, storage, and colour." />

                <x-form-field name="description" label="Description" type="textarea"
                    placeholder="Describe the device condition, any accessories included, original box status..." :required="true" />

                <div class="grid grid-cols-2 gap-4">
                    <x-form-field name="price" label="Price (KSh)" type="number"
                        placeholder="85000" :required="true" />
                    <x-form-field name="quantity" label="Quantity" type="number"
                        placeholder="1" :required="true" />
                </div>

                <div class="space-y-1">
                    <label for="product_category_id" class="block text-sm font-medium text-stone-700">
                        Category <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="product_category_id" name="product_category_id" required
                        class="form-input">
                        <option value="" disabled selected>Select a category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('product_category_id')==$cat->id)>{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                        <option value="{{ $child->id }}" @selected(old('product_category_id')==$child->id)>&nbsp;&nbsp;&nbsp;{{ $child->name }}</option>
                        @endforeach
                        @endforeach
                    </select>
                    @error('product_category_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="device_type" class="block text-sm font-medium text-stone-700">Device type <span class="text-red-500">*</span></label>
                        <select id="device_type" name="device_type" required
                            class="form-input">
                            @foreach(['phone' => 'Phone', 'laptop' => 'Laptop', 'tablet' => 'Tablet', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('device_type')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label for="condition_grade" class="block text-sm font-medium text-stone-700">Condition <span class="text-red-500">*</span></label>
                        <select id="condition_grade" name="condition_grade" required
                            class="form-input">
                            @foreach(['new' => 'Brand new', 'like_new' => 'Like new', 'good' => 'Good', 'fair' => 'Fair'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('condition_grade')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <x-form-field name="imei" label="IMEI number" type="text"
                    placeholder="356938035643809"
                    hint="Required for phones. Dial *#06# on the device to find the IMEI." />

                <x-form-field name="serial_number" label="Serial number" type="text"
                    placeholder="C02X1234ABCD"
                    hint="Found in Settings → About on most devices." />

                <div class="rounded-lg border border-stone-200 bg-stone-50 px-4 py-3 flex items-start gap-3">
                    <x-trust-verified-pill size="sm" />
                    <p class="text-xs text-stone-600 leading-relaxed">
                        After saving, your device will be queued for physical verification at our centre.
                        Verification fee: KSh {{ number_format(config('shoppa.verification.fee_min_ksh')) }}–{{ number_format(config('shoppa.verification.fee_max_ksh')) }}.
                        The listing goes live only after a Trust Certificate is issued.
                    </p>
                </div>

                <div class="pt-2 flex items-center justify-between gap-4">
                    <a href="{{ route('vendor.listings.index') }}" class="text-sm text-stone-500 hover:text-stone-700 transition-colors">Cancel</a>
                    <x-ui-button type="submit">Save listing</x-ui-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
