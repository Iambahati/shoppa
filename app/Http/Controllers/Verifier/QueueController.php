<?php

namespace App\Http\Controllers\Verifier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(Request $request): View
    {
        // Sprint 4 replaces this with:
        // Product::where('verification_status', 'pending')
        //     ->with(['vendor', 'media'])
        //     ->latest()
        //     ->paginate(20);
        $queue = collect();

        $stats = [
            'pending'   => 0,
            'in_review' => 0,
            'today'     => 0,
        ];

        return view('pages.verifier.queue', compact('queue', 'stats'));
    }
}
