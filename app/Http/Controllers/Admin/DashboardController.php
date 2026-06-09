<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_users'         => 1247,
            'pending_vendor_apps' => 8,
            'orders_today'        => 34,
            'disputes_open'       => 3,
        ];

        // 7-day order volume for KPI sparkline
        $chartData = [18, 24, 19, 31, 28, 34, 41];

        // 30-day order volume for area chart
        $orderVolume30d = [12,15,11,18,22,28,19,14,17,20,24,31,27,21,18,22,19,28,34,32,25,21,19,24,29,36,41,31,28,34];

        // Vendor application pipeline
        $vendorPipeline = [
            'pending'  => 8,
            'approved' => 94,
            'rejected' => 17,
        ];

        $recentUsers = collect([
            ['name' => 'Amara Ochieng',  'email' => 'amara@example.co.ke',     'role' => 'Buyer',          'joined' => '2 min ago'],
            ['name' => 'David Kimani',   'email' => 'dkimani@example.co.ke',   'role' => 'Vendor',         'joined' => '14 min ago'],
            ['name' => 'Faith Wanjiru',  'email' => 'faith.w@example.co.ke',   'role' => 'Buyer',          'joined' => '1 hr ago'],
            ['name' => 'Samuel Otieno',  'email' => 'samotieno@example.co.ke', 'role' => 'Buyer',          'joined' => '2 hrs ago'],
            ['name' => 'Joyce Njeri',    'email' => 'jnjeri@example.co.ke',    'role' => 'Vendor',         'joined' => '3 hrs ago'],
            ['name' => 'Brian Mwangi',   'email' => 'bmwangi@example.co.ke',   'role' => 'Buyer',          'joined' => 'Yesterday'],
            ['name' => 'Cynthia Akinyi', 'email' => 'cakinyi@example.co.ke',   'role' => 'Buyer',          'joined' => 'Yesterday'],
            ['name' => 'Peter Mwangi',   'email' => 'pmwangi@example.co.ke',   'role' => 'Content Manager','joined' => '2 days ago'],
        ]);

        return view('pages.admin.dashboard', compact(
            'stats', 'chartData', 'orderVolume30d', 'vendorPipeline', 'recentUsers'
        ));
    }
}
