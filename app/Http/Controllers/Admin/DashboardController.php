<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Enums\RoleName;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Sprint 2 will wire these to real aggregates.
        // Keeping the variable shape stable so the view never breaks.
        $stats = [
            'total_users'           => User::count(),
            'pending_vendor_apps'   => 0, // Vendor::where('status', 'pending')->count()
            'orders_today'          => 0,
            'disputes_open'         => 0,
        ];

        return view('pages.admin.dashboard', compact('stats'));
    }
}