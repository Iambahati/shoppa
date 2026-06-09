<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user      = $request->user();
        $vendor    = $user->vendor()->first();
        $hasProds  = Schema::hasTable('products');
        $hasOrders = Schema::hasTable('orders');
        $hasEarn   = Schema::hasTable('vendor_earnings');

        if ($vendor && $hasProds) {
            $listingBreakdown = [
                'active'   => rescue(fn() => \App\Models\Product::where('vendor_id', $vendor->id)->where('verification_status', 'verified')->count(), 0, false),
                'pending'  => rescue(fn() => \App\Models\Product::where('vendor_id', $vendor->id)->whereIn('verification_status', ['pending', 'in_review'])->count(), 0, false),
                'rejected' => rescue(fn() => \App\Models\Product::where('vendor_id', $vendor->id)->where('verification_status', 'rejected')->count(), 0, false),
            ];
            $recentListings = rescue(fn() => \App\Models\Product::where('vendor_id', $vendor->id)->with('category')->latest()->take(5)->get(), new Collection(), false);
        } else {
            $listingBreakdown = ['active' => 0, 'pending' => 0, 'rejected' => 0];
            $recentListings   = new Collection();
        }

        $stats = [
            'active_listings'  => $listingBreakdown['active'],
            'pending_listings' => $listingBreakdown['pending'],
            'orders_to_fulfil' => ($hasOrders && $vendor) ? rescue(fn() => \App\Models\Order::whereHas('items.product', fn($q) => $q->where('vendor_id', $vendor->id))->whereHas('status', fn($q) => $q->whereIn('name', ['pending', 'processing']))->count(), 0, false) : 0,
            'total_earned_ksh' => ($hasEarn && $vendor)   ? rescue(fn() => \App\Models\VendorEarning::where('vendor_id', $vendor->id)->sum('amount'), 0, false) : 0,
        ];

        $chartData = array_fill(0, 7, 0);
        if ($hasEarn && $vendor) {
            $chartData = array_map(
                fn($i) => rescue(fn() => \App\Models\VendorEarning::where('vendor_id', $vendor->id)->whereDate('created_at', now()->subDays($i))->sum('amount'), 0, false),
                range(6, 0)
            );
        }

        return view('pages.vendors.dashboard', compact(
            'user', 'vendor', 'stats', 'chartData', 'listingBreakdown', 'recentListings'
        ));
    }
}
