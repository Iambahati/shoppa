<?php

namespace App\Http\Controllers\Buyer;

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
        $hasOrders = Schema::hasTable('orders');
        $hasWish   = Schema::hasTable('wishlists');

        $stats = [
            'active_orders'    => $hasOrders ? rescue(fn() => \App\Models\Order::where('user_id', $user->id)->whereHas('status', fn($q) => $q->whereIn('name', ['pending', 'processing', 'shipped']))->count(), 0, false) : 0,
            'total_orders'     => $hasOrders ? rescue(fn() => \App\Models\Order::where('user_id', $user->id)->count(), 0, false) : 0,
            'wishlist_count'   => $hasWish   ? rescue(fn() => \App\Models\Wishlist::where('user_id', $user->id)->count(), 0, false) : 0,
            'devices_verified' => $hasOrders ? rescue(fn() => \App\Models\Order::where('user_id', $user->id)->whereHas('status', fn($q) => $q->where('name', 'completed'))->count(), 0, false) : 0,
        ];

        $chartData = array_fill(0, 7, 0);
        if ($hasOrders) {
            $chartData = array_map(
                fn($i) => rescue(fn() => \App\Models\Order::where('user_id', $user->id)->whereDate('created_at', now()->subDays($i))->count(), 0, false),
                range(6, 0)
            );
        }

        $recentOrders = $hasOrders
            ? rescue(fn() => \App\Models\Order::where('user_id', $user->id)->with(['status', 'items.product'])->latest()->take(5)->get(), new Collection(), false)
            : new Collection();

        return view('pages.buyer.dashboard', compact('user', 'stats', 'chartData', 'recentOrders'));
    }
}
