<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['vendor', 'category'])
            ->when($request->status, fn ($q, $s) => $q->where('verification_status', $s))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pages.admin.products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load(['vendor', 'category', 'variants', 'verifier']);
        return view('pages.admin.products.show', compact('product'));
    }

    // create / store / edit / update / destroy — Sprint 3
    public function create(): View        { abort(501, 'Sprint 3.'); }
    public function store(Request $r)     { abort(501, 'Sprint 3.'); }
    public function edit(Product $p): View { abort(501, 'Sprint 3.'); }
    public function update(Request $r, Product $p) { abort(501, 'Sprint 3.'); }
    public function destroy(Product $p)   { abort(501, 'Sprint 3.'); }
}
