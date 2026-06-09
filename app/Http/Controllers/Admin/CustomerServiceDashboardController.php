<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CustomerServiceDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hasOrders  = Schema::hasTable('orders');
        $hasRefunds = Schema::hasTable('order_refunds');

        $openDisputes = ($hasOrders)
            ? rescue(fn() => \App\Models\Order::whereHas('status', fn($q) => $q->where('name', 'disputed'))->with(['user', 'status', 'items.product'])->latest()->get(), new Collection(), false)
            : new Collection();

        $resolvedToday = $hasOrders
            ? rescue(fn() => \App\Models\Order::whereHas('status', fn($q) => $q->whereIn('name', ['completed', 'cancelled']))->whereDate('updated_at', today())->count(), 0, false)
            : 0;

        $pendingRefunds = $hasRefunds
            ? rescue(fn() => \App\Models\OrderRefund::whereHas('status', fn($q) => $q->where('name', 'pending'))->count(), 0, false)
            : 0;

        $stats = [
            'open_disputes'   => $openDisputes->count(),
            'resolved_today'  => $resolvedToday,
            'pending_refunds' => $pendingRefunds,
            'avg_resolution'  => '—',
        ];

        $chartData = $hasOrders
            ? array_map(fn($i) => rescue(fn() => \App\Models\Order::whereHas('status', fn($q) => $q->whereIn('name', ['completed', 'cancelled']))->whereDate('updated_at', now()->subDays($i))->count(), 0, false), range(6, 0))
            : array_fill(0, 7, 0);

        $urgencyBreakdown = $openDisputes->reduce(function ($carry, $dispute) {
            $age      = $dispute->created_at->diffInHours(now());
            $priority = $age >= 24 ? 'high' : ($age >= 8 ? 'medium' : 'low');
            $carry[$priority]++;
            return $carry;
        }, ['high' => 0, 'medium' => 0, 'low' => 0]);

        $disputesWithPriority = $openDisputes->take(5)->map(function ($dispute) {
            $age = $dispute->created_at->diffInHours(now());
            return [
                'model'    => $dispute,
                'priority' => $age >= 24 ? 'high' : ($age >= 8 ? 'medium' : 'low'),
                'age'      => $dispute->created_at->diffForHumans(),
            ];
        });

        return view('pages.admin.cs.dashboard', compact(
            'stats', 'chartData', 'urgencyBreakdown', 'openDisputes', 'disputesWithPriority'
        ));
    }
}
