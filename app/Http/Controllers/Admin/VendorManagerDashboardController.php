<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'pending_applications' => 0,
            'active_vendors'       => 0,
            'suspended_vendors'    => 0,
            'approvals_this_week'  => 0,
        ];

        $pendingVendors = collect();

        return view('pages.admin.vendor-manager.dashboard', compact('stats', 'pendingVendors'));
    }
}
