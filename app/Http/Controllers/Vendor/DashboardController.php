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

        $stats = [
            'active_listings'  => 23,
            'pending_listings' => 4,
            'orders_to_fulfil' => 7,
            'total_earned_ksh' => '182450.00',
        ];

        // 7-day revenue (KSh) for bar chart + sparkline
        $chartData = [14200, 18900, 16500, 22400, 19800, 27300, 31100];

        $listingBreakdown = [
            'active'   => 23,
            'pending'  => 4,
            'rejected' => 2,
        ];

        $recentListings = collect([
            ['name' => 'iPhone 14 Pro Max 256GB – Midnight',  'price' => 89500,  'status' => 'verified',  'age' => '2 hrs ago'],
            ['name' => 'Samsung Galaxy S23 Ultra – Phantom',  'price' => 74000,  'status' => 'verified',  'age' => '1 day ago'],
            ['name' => 'MacBook Pro M2 14" – Space Grey',     'price' => 165000, 'status' => 'pending',   'age' => '1 day ago'],
            ['name' => 'Google Pixel 8 Pro – Obsidian',       'price' => 62000,  'status' => 'verified',  'age' => '2 days ago'],
            ['name' => 'iPad Air 5th Gen – Starlight',         'price' => 58000,  'status' => 'in_review', 'age' => '3 days ago'],
        ]);

        return view('pages.vendors.dashboard', compact(
            'user', 'vendor', 'stats', 'chartData', 'listingBreakdown', 'recentListings'
        ));
    }
}
