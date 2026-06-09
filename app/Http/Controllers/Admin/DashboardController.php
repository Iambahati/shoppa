<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hasOrders = Schema::hasTable('orders');

        $stats = [
            'total_users'         => User::count(),
            'pending_vendor_apps' => Vendor::where('status', 'pending')->count(),
            'orders_today'        => $hasOrders ? rescue(fn() => \App\Models\Order::whereDate('created_at', today())->count(), 0, false) : 0,
            'disputes_open'       => $hasOrders ? rescue(fn() => \App\Models\Order::whereHas('status', fn($q) => $q->where('name', 'disputed'))->count(), 0, false) : 0,
        ];

        $orderVolume30d = $hasOrders
            ? array_map(fn($i) => rescue(fn() => \App\Models\Order::whereDate('created_at', now()->subDays($i))->count(), 0, false), range(29, 0))
            : array_fill(0, 30, 0);
        $chartData = array_slice($orderVolume30d, -7);

        $vendorPipeline = [
            'pending'  => Vendor::where('status', 'pending')->count(),
            'approved' => Vendor::where('status', 'approved')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
        ];

        $recentUsers = User::with('role')->latest()->take(8)->get();

        return view('pages.admin.dashboard', compact(
            'stats', 'chartData', 'orderVolume30d', 'vendorPipeline', 'recentUsers'
        ));
    }
}
