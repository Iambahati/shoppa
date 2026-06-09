<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContentManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hasProds = Schema::hasTable('products');
        $hasCats  = Schema::hasTable('product_categories');

        $totalProducts  = $hasProds ? rescue(fn() => \App\Models\Product::count(), 0, false) : 0;
        $pendingReview  = $hasProds ? rescue(fn() => \App\Models\Product::whereIn('verification_status', ['pending', 'in_review'])->count(), 0, false) : 0;
        $publishedToday = $hasProds ? rescue(fn() => \App\Models\Product::where('verification_status', 'verified')->whereDate('cert_issued_at', today())->count(), 0, false) : 0;
        $categories     = $hasCats  ? rescue(fn() => \App\Models\ProductCategory::count(), 0, false) : 0;

        $stats = [
            'total_products'  => $totalProducts,
            'pending_review'  => $pendingReview,
            'published_today' => $publishedToday,
            'categories'      => $categories,
        ];

        $chartData = $hasProds
            ? array_map(fn($i) => rescue(fn() => \App\Models\Product::where('verification_status', 'verified')->whereDate('cert_issued_at', now()->subDays($i))->count(), 0, false), range(6, 0))
            : array_fill(0, 7, 0);

        $funnel = [
            'submitted' => $hasProds ? rescue(fn() => \App\Models\Product::whereIn('verification_status', ['pending', 'in_review'])->count(), 0, false) : 0,
            'in_review' => $hasProds ? rescue(fn() => \App\Models\Product::where('verification_status', 'in_review')->count(), 0, false) : 0,
            'approved'  => $hasProds ? rescue(fn() => \App\Models\Product::where('verification_status', 'verified')->count(), 0, false) : 0,
            'rejected'  => $hasProds ? rescue(fn() => \App\Models\Product::where('verification_status', 'rejected')->count(), 0, false) : 0,
        ];

        $recentProducts = $hasProds
            ? rescue(fn() => \App\Models\Product::with(['vendor', 'category'])->latest()->take(6)->get(), new Collection(), false)
            : new Collection();

        return view('pages.admin.content.dashboard', compact(
            'stats', 'chartData', 'funnel', 'recentProducts'
        ));
    }
}
