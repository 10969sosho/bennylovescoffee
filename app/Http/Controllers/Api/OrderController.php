<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $order->load('orderItems.product');

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!'
            ], 400);
        }

        $total = $cartItems->sum(function ($item) {
            return $item->getTotalPrice();
        });

        return response()->json([
            'success' => true,
            'data' => [
                'cart_items' => $cartItems,
                'total' => $total
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer,credit_card',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'variant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty!'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Re-validate stock availability (with row locks to prevent over-selling)
            foreach ($cartItems as $cartItem) {
                if ($cartItem->variant_id) {
                    $variant = ProductVariant::where('id', $cartItem->variant_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$variant || $variant->quantity < $cartItem->quantity) {
                        throw new \Exception('Stock insufficient for variant of "' . $cartItem->product->name . '".');
                    }
                } else {
                    $product = Product::where('id', $cartItem->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$product || $product->stock < $cartItem->quantity) {
                        throw new \Exception('Stock insufficient for "' . $cartItem->product->name . '".');
                    }
                }
            }

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'total_amount' => $cartItems->sum(function ($item) {
                    return $item->getTotalPrice();
                }),
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'variant_name' => $cartItem->variant_id ? $cartItem->variant->name : null,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->variant_id ? $cartItem->variant->price : $cartItem->product->getCurrentPrice(),
                    'total' => $cartItem->getTotalPrice(),
                ]);

                // Update product stock
                if ($cartItem->variant_id) {
                    $cartItem->variant->decrement('quantity', $cartItem->quantity);
                } else {
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                $cartItem->product->increment('sold_count', $cartItem->quantity);

                if (!$cartItem->variant_id) {
                    $flashItem = $cartItem->product->getCurrentFlashSaleItem();
                    if ($flashItem) {
                        $flashItem->increment('sold_count', $cartItem->quantity);
                    }
                }
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'data' => $order->load('orderItems.product')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 422);
        }
    }
}
