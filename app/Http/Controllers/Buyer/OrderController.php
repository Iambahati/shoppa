<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['status', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('pages.buyer.orders.index', compact('orders'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Sprint 5: validate cart, create order, initiate escrow
        abort(501, 'Implemented in Sprint 5.');
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['status', 'items.product', 'shipment.status', 'payment']);

        return view('pages.buyer.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('view', $order);

        if (! $order->isCancellable()) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        // Sprint 5: update status, release escrow if held
        abort(501, 'Implemented in Sprint 5.');
    }

    public function confirmReceipt(Request $request, Order $order): RedirectResponse
    {
        // Sprint 6: trigger escrow release
        abort(501, 'Implemented in Sprint 6.');
    }

    public function dispute(Request $request, Order $order): RedirectResponse
    {
        // Sprint 6: freeze escrow, open dispute ticket
        abort(501, 'Implemented in Sprint 6.');
    }
}
