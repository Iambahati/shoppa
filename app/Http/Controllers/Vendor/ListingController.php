<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $vendor   = $request->user()->vendor;
        $listings = Product::where('vendor_id', $vendor->id)
            ->with(['category', 'status'])
            ->latest()
            ->paginate(20);

        return view('pages.vendor.listings.index', compact('listings'));
    }

    public function create(): View
    {
        $categories = ProductCategory::whereNull('parent_id')->with('children')->get();
        return view('pages.vendor.listings.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'description'         => ['required', 'string', 'min:20'],
            'price'               => ['required', 'numeric', 'min:0'],
            'quantity'            => ['required', 'integer', 'min:1'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'device_type'         => ['required', 'in:phone,laptop,tablet,other'],
            'condition_grade'     => ['required', 'in:premium,excellent,good'],
            'imei'                => ['nullable', 'string', 'max:20', 'unique:products,imei'],
            'serial_number'       => ['nullable', 'string', 'max:50'],
        ]);

        $vendor  = $request->user()->vendor;
        $product = Product::create(array_merge($validated, [
            'vendor_id'           => $vendor->id,
            'verification_status' => 'pending',
            'product_status_id'   => 1, // 'draft' — seeded in Sprint 3
        ]));

        activity()->causedBy($request->user())->performedOn($product)
            ->log("Created listing: {$product->name}");

        return redirect()->route('vendor.listings.show', $product)
            ->with('success', 'Listing created. Submit the device for verification to go live.');
    }

    public function show(Request $request, Product $product): View
    {
        $this->authorizeVendor($request, $product);
        $product->load(['category', 'variants', 'media']);
        return view('pages.vendor.listings.show', compact('product'));
    }

    public function edit(Request $request, Product $product): View
    {
        $this->authorizeVendor($request, $product);
        $categories = ProductCategory::whereNull('parent_id')->with('children')->get();
        return view('pages.vendor.listings.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeVendor($request, $product);

        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'description'         => ['required', 'string', 'min:20'],
            'price'               => ['required', 'numeric', 'min:0'],
            'quantity'            => ['required', 'integer', 'min:1'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'condition_grade'     => ['required', 'in:premium,excellent,good'],
        ]);

        $product->update($validated);

        return redirect()->route('vendor.listings.show', $product)
            ->with('success', 'Listing updated.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeVendor($request, $product);
        $product->delete();

        return redirect()->route('vendor.listings.index')
            ->with('success', 'Listing removed.');
    }

    private function authorizeVendor(Request $request, Product $product): void
    {
        $vendor = $request->user()->vendor;
        abort_if($product->vendor_id !== $vendor->id, 403, 'This listing does not belong to your shop.');
    }
}
