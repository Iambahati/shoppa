<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InspectionController extends Controller
{
    /**
     * Show a single device inspection form.
     * Sprint 4: load product with attributes, prior inspection history,
     * IMEI check result, and media.
     */
    public function show(Request $request, int $product): View
    {
        // $product = Product::with(['vendor', 'attributes', 'media'])->findOrFail($product);
        return view('pages.verifier.inspect', ['productId' => $product]);
    }

    /**
     * Store an inspection report.
     * Sprint 4: validate IMEI uniqueness, record condition grade,
     * log verifier ID to activity_log, transition status to in_review.
     */
    public function store(Request $request, int $product)
    {
        abort(501, 'Wired in Sprint 4.');
    }

    /**
     * Issue Trust Certificate — transitions product to verified.
     * Sprint 4: generate signed UUID cert, create QR, store via Spatie Media.
     */
    public function certify(Request $request, int $product)
    {
        abort(501, 'Wired in Sprint 4.');
    }

    /**
     * Reject a device with reason.
     * Sprint 4: notify vendor, log rejection reason.
     */
    public function reject(Request $request, int $product)
    {
        abort(501, 'Wired in Sprint 4.');
    }
}
