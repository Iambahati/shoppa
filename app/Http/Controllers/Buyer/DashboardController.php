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
            'active_orders'    => 0,
            'total_orders'     => 0,
            'wishlist_count'   => 0,
            'devices_verified' => 0,
        ];

        $recentOrders = collect();

        return view('pages.buyer.dashboard', compact('user', 'stats', 'recentOrders'));
    }
}
