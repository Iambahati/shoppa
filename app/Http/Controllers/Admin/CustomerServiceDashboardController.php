<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerServiceDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'open_disputes'      => 0,
            'resolved_today'     => 0,
            'pending_refunds'    => 0,
            'avg_resolution'     => '—',
        ];

        $openDisputes = collect();

        return view('pages.admin.cs.dashboard', compact('stats', 'openDisputes'));
    }
}
