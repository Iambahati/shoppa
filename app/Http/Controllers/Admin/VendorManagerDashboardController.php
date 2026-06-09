<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'pending_applications' => 8,
            'active_vendors'       => 94,
            'suspended_vendors'    => 5,
            'approvals_this_week'  => 12,
        ];

        // 7-day approvals for sparkline
        $chartData = [3, 5, 2, 4, 6, 4, 3];

        // Vendor status breakdown for progress bars
        $vendorBreakdown = [
            'active'    => 94,
            'pending'   => 8,
            'suspended' => 5,
            'rejected'  => 17,
        ];

        $pendingVendors = collect([
            ['name' => 'NairobiTech Solutions',  'owner' => 'Peter Mwangi',   'category' => 'Phones & Tablets', 'applied' => '3 hrs ago',  'docs' => true],
            ['name' => 'Gadget Palace EA',        'owner' => 'Linda Achieng',  'category' => 'Laptops',          'applied' => '7 hrs ago',  'docs' => true],
            ['name' => 'TechParts Kenya',         'owner' => 'Samuel Njoroge', 'category' => 'Accessories',      'applied' => '1 day ago',  'docs' => false],
            ['name' => 'Digital Haven',           'owner' => 'Ruth Kamau',     'category' => 'Phones & Tablets', 'applied' => '2 days ago', 'docs' => true],
            ['name' => 'Smart Devices KE',        'owner' => 'Charles Ouma',   'category' => 'Wearables',        'applied' => '2 days ago', 'docs' => true],
            ['name' => 'Electrozone Mombasa',     'owner' => 'Hassan Abdulla', 'category' => 'Phones & Tablets', 'applied' => '3 days ago', 'docs' => false],
        ]);

        return view('pages.admin.vendor-manager.dashboard', compact(
            'stats', 'chartData', 'vendorBreakdown', 'pendingVendors'
        ));
    }
}
