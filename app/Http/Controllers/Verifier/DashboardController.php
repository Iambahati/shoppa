<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'queue_depth'     => 0,
            'certified_today' => 0,
            'rejected_today'  => 0,
            'avg_time'        => '—',
        ];

        $topQueue = collect();

        return view('pages.verifier.dashboard', compact('stats', 'topQueue'));
    }
}
