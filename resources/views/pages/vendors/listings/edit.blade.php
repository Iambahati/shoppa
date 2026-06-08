<x-layouts.app>
    <x-slot:title>Edit listing</x-slot:title>

    <div class="mx-auto max-w-2xl">

        <div class="mb-6">
            <a href="{{ route('vendor.listings.show', $product) }}" class="text-sm text-stone-400 hover:text-stone-600 flex items-center gap-1 w-fit">
                <x-nav-icon name="chevron-r" class="h-4 w-4 rotate-180" /> Back to listing
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-stone-900">Edit listing</h2>
            <p class="mt-1 text-sm text-stone-500">
                Changes to price or description do not affect existing Trust Certificates.
                Changes to device identity (IMEI, serial) require re-verification.
            </p>
        </div>

        <div class="rounded-xl bg-white ring-1 ring-stone-950/5 shadow-sm p-6">
            <form method="POST" action="{{ route('vendor.listings.update', $product) }}" class="space-y-5">
                @csrf @method('PUT')

                <x-form-field name="name" label="Device name" type="text" :required="true" />

                <x-form-field name="description" label="Description" type="textarea" :required="true" />

                <div class="grid grid-cols-2 gap-4">
                    <x-form-field name="price" label="Price (KSh)" type="number" :required="true" />
                    <x-form-field name="quantity" label="Quantity" type="number" :required="true" />
                </div>

                <div class="space-y-1">
                    <label for="product_category_id" class="block text-sm font-medium text-stone-700">
                        Category <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="product_category_id" name="product_category_id" required
                        class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('product_category_id', $product->product_category_id) == $cat->id)>{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                        <option value="{{ $child->id }}" @selected(old('product_category_id', $product->product_category_id) == $child->id)>&nbsp;&nbsp;&nbsp;{{ $child->name }}</option>
                        @endforeach
                        @endforeach
                    </select>
                    @error('product_category_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1">
                    <label for="condition_grade" class="block text-sm font-medium text-stone-700">
                        Condition <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <select id="condition_grade" name="condition_grade" required
                        class="block w-full rounded-lg border-0 py-2 px-3 text-stone-900 ring-1 ring-inset ring-stone-300 text-sm focus:ring-2 focus:ring-inset focus:ring-emerald-600">
                        @foreach(['new' => 'Brand new', 'like_new' => 'Like new', 'good' => 'Good', 'fair' => 'Fair'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('condition_grade', $product->condition_grade) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('condition_grade')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($product->verification_status === 'verified')
                <x-ui-alert type="warning">
                    This listing has a valid Trust Certificate. Editing core fields will not revoke the certificate automatically — contact a Verifier if the device details have changed.
                </x-ui-alert>
                @endif

                <div class="pt-2 flex items-center justify-between gap-4">
                    <a href="{{ route('vendor.listings.show', $product) }}" class="text-sm text-stone-500 hover:text-stone-700">Cancel</a>
                    <x-ui-button type="submit">Save changes</x-ui-button>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>