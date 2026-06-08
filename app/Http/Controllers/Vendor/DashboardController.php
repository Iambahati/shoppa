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
        $vendor = $user->vendor()->first();

        // Shape defined now, real queries wired in Sprint 2
        $stats = [
            'active_listings'  => 0,
            'pending_listings' => 0,
            'orders_to_fulfil' => 0,
            'total_earned_ksh' => '0.00',
        ];

        $recentListings = collect();

        return view('pages.vendors.dashboard', compact('user', 'vendor', 'stats', 'recentListings'));
    }
}
