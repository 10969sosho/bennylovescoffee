<?php

namespace App\Http\Controllers;

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

        $subtotal = $cartItems->sum(function ($item) {
            return $item->getTotalPrice();
        });

        $total = $subtotal;

        return view('cart.index', compact('cartItems', 'subtotal', 'total'));
    }

    public function add(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        // If variant is selected, validate against variant stock
        if ($request->variant_id) {
            $variant = ProductVariant::find($request->variant_id);
            if (!$variant || $variant->product_id !== $product->id) {
                return redirect()->back()->with('error', 'Invalid variant selected!');
            }
            
            $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $variant->quantity
            ]);
        } else {
            // If no variant, validate against product stock
            $request->validate([
                'quantity' => 'required|integer|min:1|max:' . $product->stock
            ]);
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        $itemName = $request->variant_id ? $product->name . ' - ' . ProductVariant::find($request->variant_id)->name : $product->name;
        return redirect()->back()->with('success', $itemName . ' berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        $maxQuantity = $cart->variant_id ? $cart->variant->quantity : $cart->product->stock;
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $maxQuantity
        ]);

        $cart->update(['quantity' => $request->quantity]);

        // Check if request is AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'quantity' => $cart->quantity,
                'total' => $cart->getTotalPrice()
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
}
