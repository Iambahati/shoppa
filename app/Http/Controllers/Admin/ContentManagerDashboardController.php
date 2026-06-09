<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentManagerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_products'  => 486,
            'pending_review'  => 22,
            'published_today' => 14,
            'categories'      => 18,
        ];

        // 7-day publications for sparkline
        $chartData = [8, 12, 10, 15, 11, 14, 14];

        // Publication funnel
        $funnel = [
            'submitted' => 22,
            'in_review' => 7,
            'approved'  => 486,
            'rejected'  => 34,
        ];

        $recentProducts = collect([
            ['name' => 'iPhone 14 Pro Max 256GB – Deep Purple',  'vendor' => 'TechHub KE',    'status' => 'pending',   'category' => 'Phone',  'age' => '5 min ago'],
            ['name' => 'Samsung Galaxy S23 FE – Graphite',       'vendor' => 'Gadget World',  'status' => 'pending',   'category' => 'Phone',  'age' => '22 min ago'],
            ['name' => 'Dell XPS 15 2024 – Platinum Silver',     'vendor' => 'iStore Nairobi','status' => 'in_review', 'category' => 'Laptop', 'age' => '1 hr ago'],
            ['name' => 'Apple Watch Series 9 – Midnight',        'vendor' => 'TechHub KE',    'status' => 'verified',  'category' => 'Watch',  'age' => '2 hrs ago'],
            ['name' => 'Sony WH-1000XM5 Headphones – Black',    'vendor' => 'AudioZone KE',  'status' => 'pending',   'category' => 'Audio',  'age' => '3 hrs ago'],
            ['name' => 'Google Pixel 8 Pro – Hazel',             'vendor' => 'Gadget World',  'status' => 'verified',  'category' => 'Phone',  'age' => '5 hrs ago'],
        ]);

        return view('pages.admin.content.dashboard', compact(
            'stats', 'chartData', 'funnel', 'recentProducts'
        ));
    }
}
