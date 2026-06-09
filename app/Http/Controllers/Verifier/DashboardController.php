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
            'queue_depth'     => 14,
            'certified_today' => 9,
            'rejected_today'  => 2,
            'avg_time'        => '18 min',
        ];

        // 7-day certifications for sparkline
        $chartData = [6, 8, 11, 7, 12, 9, 9];

        // For the donut ring progress chart
        $certificationRing = [
            'certified' => 9,
            'rejected'  => 2,
            'remaining' => 14,
        ];

        $topQueue = collect([
            ['name' => 'iPhone 13 Pro – Graphite',     'imei' => '352099001761481', 'vendor' => 'TechHub KE',     'wait' => '4h 22m', 'urgency' => 'high'],
            ['name' => 'Samsung Galaxy A54 – Violet',  'imei' => '490154203237518', 'vendor' => 'Gadget World',   'wait' => '3h 05m', 'urgency' => 'high'],
            ['name' => 'MacBook Air M2 – Midnight',    'imei' => null,              'vendor' => 'iStore Nairobi', 'wait' => '2h 41m', 'urgency' => 'medium'],
            ['name' => 'Google Pixel 7a – Charcoal',   'imei' => '356938035643809', 'vendor' => 'TechHub KE',     'wait' => '1h 58m', 'urgency' => 'medium'],
            ['name' => 'iPad Mini 6th Gen – Pink',     'imei' => null,              'vendor' => 'Gadget World',   'wait' => '45m',    'urgency' => 'low'],
        ]);

        return view('pages.verifier.dashboard', compact(
            'stats', 'chartData', 'certificationRing', 'topQueue'
        ));
    }
}
