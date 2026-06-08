<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorApplicationRequest;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        // If user already has a vendor record, send them to their dashboard
        if ($request->user()->vendor()->exists()) {
            return redirect()->route('vendor.dashboard');
        }

        return view('pages.vendor.apply');
    }

    public function store(VendorApplicationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Guard: one application per user
        if ($user->vendor()->exists()) {
            return redirect()->route('vendor.dashboard')
                ->with('info', 'You already have an active vendor application.');
        }

        Vendor::create([
            'user_id'     => $user->id,
            'name'        => $request->validated('shop_name'),
            'description' => $request->validated('description'),
            // Sprint 2 will add status, kyc_document_path, location fields
        ]);

        // Activity log — Spatie activity() helper (Sprint 2 wires full logging)
        activity()
            ->causedBy($user)
            ->log('Submitted vendor application');

        return redirect()->route('buyer.dashboard')
            ->with('success', 'Your application has been submitted. We will review it within 2–3 business days.');
    }
}
