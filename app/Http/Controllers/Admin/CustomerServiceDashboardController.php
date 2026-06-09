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
            'open_disputes'   => 7,
            'resolved_today'  => 5,
            'pending_refunds' => 3,
            'avg_resolution'  => '2.4d',
        ];

        // 7-day resolved disputes for sparkline
        $chartData = [4, 6, 3, 8, 5, 7, 5];

        // Urgency breakdown for visual tiles
        $urgencyBreakdown = [
            'high'   => 2,
            'medium' => 3,
            'low'    => 2,
        ];

        $openDisputes = collect([
            ['id' => 'DSP-441', 'order_id' => 'ORD-4512', 'buyer' => 'Amara Ochieng',  'reason' => 'Item not as described – iPhone screen has dead pixels', 'priority' => 'high',   'age' => '2h ago'],
            ['id' => 'DSP-440', 'order_id' => 'ORD-4498', 'buyer' => 'David Kamau',    'reason' => 'Package not received after 7 days',                     'priority' => 'high',   'age' => '6h ago'],
            ['id' => 'DSP-438', 'order_id' => 'ORD-4471', 'buyer' => 'Grace Muthoni',  'reason' => 'Refund not processed after return',                      'priority' => 'medium', 'age' => '1d ago'],
            ['id' => 'DSP-436', 'order_id' => 'ORD-4455', 'buyer' => 'John Otieno',    'reason' => 'Battery health lower than listed grade',                  'priority' => 'medium', 'age' => '2d ago'],
            ['id' => 'DSP-433', 'order_id' => 'ORD-4419', 'buyer' => 'Stella Wanjiku', 'reason' => 'IMEI mismatch with certified device',                    'priority' => 'low',    'age' => '3d ago'],
        ]);

        return view('pages.admin.cs.dashboard', compact(
            'stats', 'chartData', 'urgencyBreakdown', 'openDisputes'
        ));
    }
}
