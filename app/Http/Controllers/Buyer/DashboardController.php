<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'active_orders'    => 2,
            'total_orders'     => 11,
            'wishlist_count'   => 8,
            'devices_verified' => 9,
        ];

        // 7-day order activity for sparkline
        $chartData = [1, 0, 2, 1, 3, 1, 2];

        $recentOrders = collect([
            ['id' => 'ORD-4821', 'item' => 'iPhone 14 Pro Max 256GB',    'amount' => 89500,  'status' => 'Delivered',  'status_color' => 'emerald', 'age' => '2 days ago'],
            ['id' => 'ORD-4756', 'item' => 'Samsung Galaxy S23 Ultra',   'amount' => 74000,  'status' => 'In Transit', 'status_color' => 'blue',    'age' => '4 days ago'],
            ['id' => 'ORD-4702', 'item' => 'MacBook Pro M2 14"',          'amount' => 165000, 'status' => 'Processing', 'status_color' => 'amber',   'age' => '6 days ago'],
            ['id' => 'ORD-4688', 'item' => 'AirPods Pro 2nd Gen',         'amount' => 18500,  'status' => 'Delivered',  'status_color' => 'emerald', 'age' => '1 week ago'],
            ['id' => 'ORD-4601', 'item' => 'iPad Air 5th Gen',            'amount' => 58000,  'status' => 'Delivered',  'status_color' => 'emerald', 'age' => '2 weeks ago'],
        ]);

        return view('pages.buyer.dashboard', compact('user', 'stats', 'chartData', 'recentOrders'));
    }
}
