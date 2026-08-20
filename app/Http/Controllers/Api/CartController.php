<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'variant'])
            ->get();

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

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $variant = null;
        $availableStock = $product->stock;
        if ($request->variant_id) {
            $variant = ProductVariant::where('id', $request->variant_id)
                ->where('product_id', $product->id)
                ->first();
            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid variant selected!'
                ], 422);
            }
            $availableStock = $variant->quantity;
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        // Check total quantity (including what is already in the cart)
        $newQuantity = ($cartItem?->quantity ?? 0) + $request->quantity;
        if ($newQuantity > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => "Stock insufficient, remaining: {$availableStock}"
            ], 422);
        }

        if ($cartItem) {
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!'
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $maxQuantity = $cart->variant_id ? $cart->variant->quantity : $cart->product->stock;
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $maxQuantity
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'quantity' => $cart->quantity,
            'total' => $cart->getTotalPrice()
        ]);
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart!'
        ]);
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!'
        ]);
    }
}
