<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('customer');

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->has('customer_id') && !empty($request->customer_id)) {
            $query->where('customer_id', $request->customer_id);
        }

        // Search by order ID
        if ($request->has('search') && !empty($request->search)) {
            $query->where('id', 'like', '%' . $request->search . '%');
        }

        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(10);
        $customers = Customer::orderBy('name')->get();

        return view('content.orders.index', compact('orders', 'customers'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items']);
        return view('content.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,pending,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Update the payment details.
     */
    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $paidAmount = $validated['paid_amount'];
        $remainingAmount = max(0, $order->total_amount - $paidAmount);

        $order->paid_amount = $paidAmount;
        $order->remaining_amount = $remainingAmount;
        $order->status = $remainingAmount > 0 ? 'pending' : 'completed';
        $order->save();

        return redirect()->back()->with('success', 'Payment updated successfully.');
    }
}
