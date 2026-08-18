<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\BiteshipService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->getTotalPrice();
        });

        // Calculate total weight
        $totalWeight = $cartItems->sum(function ($item) {
            // If product has weight, use it, otherwise default to 200g
            $weight = $item->product->weight > 0 ? $item->product->weight : 200;
            return $weight * $item->quantity;
        });

        // Get user data for auto-fill
        $user = Auth::user();

        return view('orders.checkout', compact('cartItems', 'subtotal', 'totalWeight', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_service' => 'required|string',
            'shipping_etd' => 'nullable|string',
            'destination_area_id' => 'required|string', // Biteship Area ID
            'area_name' => 'required|string', // Biteship Area Name (City, Prov, etc)
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->getTotalPrice();
        });
        
        $shippingCost = $request->shipping_cost;
        $totalAmount = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            // Update user phone if provided
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($request->customer_phone) {
                $user->update(['phone' => $request->customer_phone]);
            }

            // Build complete shipping address
            $fullAddress = $request->shipping_address . ', ' . 
                          $request->area_name . ', ' .
                          $request->postal_code;

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . time() . '-' . Auth::id(),
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $fullAddress,
                'payment_method' => 'bank_transfer', // Default
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'shipping_service' => $request->shipping_service,
                'shipping_etd' => $request->shipping_etd,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $price = $cartItem->variant_id ? $cartItem->variant->price : $cartItem->product->getCurrentPrice();
                $variantName = $cartItem->variant_id ? $cartItem->variant->name : null;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'variant_name' => $variantName,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'total' => $cartItem->getTotalPrice(),
                ]);

                // Update stock and sold count
                if ($cartItem->variant_id) {
                    $cartItem->variant->decrement('quantity', $cartItem->quantity);
                } else {
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }
                
                // Update sold count for the product
                $cartItem->product->increment('sold_count', $cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment proof can only be uploaded for pending orders.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        try {
            // Delete old payment proof if exists
            if ($order->payment_proof) {
                Storage::delete($order->payment_proof);
            }

            // Store new payment proof
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Update order
            $order->update([
                'payment_proof' => $path,
                'payment_proof_uploaded_at' => now(),
                'status' => 'processing', // Auto update status to processing
            ]);

            return redirect()->back()->with('success', 'Payment proof uploaded successfully! Your order is now being processed.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload payment proof. Please try again.');
        }
    }

    public function track(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Generate tracking number if not exists (for demo purposes)
        if (!$order->tracking_number && in_array($order->status, ['shipped', 'delivered'])) {
            $order->update([
                'tracking_number' => 'JNE' . strtoupper(substr(md5($order->order_number), 0, 12)),
                'shipped_at' => $order->shipped_at ?? now()->subDays(2),
            ]);
        }

        return view('orders.track', compact('order'));
    }

    public function confirmDelivery(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow confirmation for shipped orders
        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'Only shipped orders can be confirmed as delivered.');
        }

        // Update order to delivered
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you! Order confirmed as delivered.');
    }
}
