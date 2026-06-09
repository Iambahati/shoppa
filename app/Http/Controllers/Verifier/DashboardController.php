<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hasProds = Schema::hasTable('products');

        $certifiedToday = $hasProds ? rescue(fn() => \App\Models\Product::where('verification_status', 'verified')->whereDate('cert_issued_at', today())->count(), 0, false) : 0;
        $rejectedToday  = $hasProds ? rescue(fn() => \App\Models\Product::where('verification_status', 'rejected')->whereDate('updated_at', today())->count(), 0, false) : 0;
        $queueDepth     = $hasProds ? rescue(fn() => \App\Models\Product::whereIn('verification_status', ['pending', 'in_review'])->count(), 0, false) : 0;

        $stats = [
            'queue_depth'     => $queueDepth,
            'certified_today' => $certifiedToday,
            'rejected_today'  => $rejectedToday,
            'avg_time'        => '—',
        ];

        $chartData = $hasProds
            ? array_map(fn($i) => rescue(fn() => \App\Models\Product::where('verification_status', 'verified')->whereDate('cert_issued_at', now()->subDays($i))->count(), 0, false), range(6, 0))
            : array_fill(0, 7, 0);

        $certificationRing = [
            'certified' => $certifiedToday,
            'rejected'  => $rejectedToday,
            'remaining' => $queueDepth,
        ];

        $topQueue = ($hasProds && $queueDepth > 0)
            ? rescue(function () {
                return \App\Models\Product::whereIn('verification_status', ['pending', 'in_review'])
                    ->with('vendor')
                    ->oldest()
                    ->take(5)
                    ->get()
                    ->map(function ($product) {
                        $hours = $product->created_at->diffInHours(now());
                        return [
                            'model'   => $product,
                            'urgency' => $hours >= 4 ? 'high' : ($hours >= 2 ? 'medium' : 'low'),
                            'wait'    => $hours >= 1 ? $hours . 'h ' . ($product->created_at->diffInMinutes(now()) % 60) . 'm' : $product->created_at->diffInMinutes(now()) . 'm',
                        ];
                    });
            }, new Collection(), false)
            : new Collection();

        return view('pages.verifier.dashboard', compact(
            'stats', 'chartData', 'certificationRing', 'topQueue'
        ));
    }
}
