<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $vendors = Vendor::with('user')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) =>
                $q->where('name', 'ilike', "%{$s}%")
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pages.admin.vendors.index', compact('vendors'));
    }

    public function show(Vendor $vendor): View
    {
        $vendor->load(['user', 'products', 'earnings']);
        return view('pages.admin.vendors.show', compact('vendor'));
    }

    public function approve(Request $request, Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        activity()->causedBy($request->user())->performedOn($vendor)
            ->log("Approved vendor: {$vendor->name}");

        // Sprint 2: dispatch VendorApprovedNotification to vendor user

        return back()->with('success', "{$vendor->name} has been approved.");
    }

    public function reject(Request $request, Vendor $vendor): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $vendor->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        activity()->causedBy($request->user())->performedOn($vendor)
            ->log("Rejected vendor: {$vendor->name}");

        return back()->with('success', "{$vendor->name} application rejected.");
    }
}
