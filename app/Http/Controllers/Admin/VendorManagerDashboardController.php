<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class VendorManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $approvalsThisWeek = Vendor::where('status', 'approved')
            ->whereBetween('approved_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $stats = [
            'pending_applications' => Vendor::where('status', 'pending')->count(),
            'active_vendors'       => Vendor::where('status', 'approved')->count(),
            'suspended_vendors'    => Vendor::where('status', 'suspended')->count(),
            'approvals_this_week'  => $approvalsThisWeek,
        ];

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $chartData[] = Vendor::where('status', 'approved')
                ->whereDate('approved_at', now()->subDays($i))
                ->count();
        }

        $vendorBreakdown = [
            'active'    => $stats['active_vendors'],
            'pending'   => $stats['pending_applications'],
            'suspended' => $stats['suspended_vendors'],
            'rejected'  => Vendor::where('status', 'rejected')->count(),
        ];

        $withRelations = Schema::hasTable('media') ? ['user', 'media'] : ['user'];
        $pendingVendors = Vendor::where('status', 'pending')
            ->with($withRelations)
            ->latest()
            ->take(6)
            ->get();

        return view('pages.admin.vendor-manager.dashboard', compact(
            'stats', 'chartData', 'vendorBreakdown', 'pendingVendors'
        ));
    }
}
