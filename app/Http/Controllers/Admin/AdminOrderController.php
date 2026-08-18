<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // Get all orders with related data for DataTables
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // Check if request is JSON (from index page)
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tracking number added and order marked as shipped!');
    }

    public function cancel(Order $order)
    {
        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()->with('success', 'Order cancelled successfully!');
    }

    public function markAsDelivered(Order $order)
    {
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Order marked as delivered!');
    }
}