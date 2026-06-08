<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrowseController extends Controller
{
    public function index(Request $request): View
    {
        // Sprint 3: replace with
        // Product::verified()->with(['vendor','media','category'])
        //     ->filter($request->only(['category','min_price','max_price','condition']))
        //     ->paginate(24);
        $products   = collect();
        $categories = collect();

        return view('pages.buyer.browse', compact('products', 'categories'));
    }

    public function show(Request $request, int $product): View
    {
        // Sprint 3: Product::verified()->with([...])->findOrFail($product)
        return view('pages.buyer.product-show', ['productId' => $product]);
    }
}
