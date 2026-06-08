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
            'total_products'  => 0,
            'pending_review'  => 0,
            'published_today' => 0,
            'categories'      => 0,
        ];

        $recentProducts = collect();

        return view('pages.admin.content.dashboard', compact('stats', 'recentProducts'));
    }
}
