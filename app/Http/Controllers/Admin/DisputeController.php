<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisputeController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.disputes.index', ['disputes' => collect()]);
    }

    public function show(int $dispute): View
    {
        abort(501, 'Sprint 6.');
    }

    public function resolve(Request $request, int $dispute)
    {
        abort(501, 'Sprint 6.');
    }
}
