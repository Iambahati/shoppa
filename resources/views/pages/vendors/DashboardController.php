<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user   = $request->user();
        $vendor = $user->vendor()->latest()->first();

        // Sprint 2 will replace these with real aggregates from vendor_earnings,
        // order_items and products. Shape is fixed so the view never breaks.
        $stats = [
            'active_listings'  => 0,
            'pending_listings' => 0,
            'total_sales'      => 0,
            'balance_ksh'      => '0.00',
        ];

        $recentListings = collect();

        return view('pages.vendor.dashboard', compact('user', 'vendor', 'stats', 'recentListings'));
    }
}