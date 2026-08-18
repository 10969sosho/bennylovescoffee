<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\FeaturedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeaturedProductController extends Controller
{
    /**
     * Display a listing of featured products
     */
    public function index()
    {
        $featuredProducts = FeaturedProduct::with('product.category')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.featured-products.index', compact('featuredProducts'));
    }

    /**
     * Show the form for selecting products to feature
     */
    public function create()
    {
        // Get products that are not currently featured
        $availableProducts = Product::where('is_active', true)
            ->whereDoesntHave('featuredProduct', function($query) {
                $query->where('is_active', true);
            })
            ->with('category')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.featured-products.create', compact('availableProducts'));
    }

    /**
     * Store newly featured products
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
        ]);

        DB::transaction(function() use ($request) {
            $productIds = $request->product_ids;

            // Get current max sort order
            $maxSortOrder = FeaturedProduct::max('sort_order') ?? 0;

            foreach ($productIds as $index => $productId) {
                FeaturedProduct::create([
                    'product_id' => $productId,
                    'title' => null, // Use product name instead
                    'description' => null, // Use product description instead
                    'sort_order' => $maxSortOrder + $index + 1,
                    'is_active' => true,
                    'featured_at' => now(),
                ]);
            }
        });

        return redirect()->route('admin.featured-products.index')
            ->with('success', 'Products added to featured successfully!');
    }

    /**
     * Update sort order of featured products
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:featured_products,id',
        ]);

        DB::transaction(function() use ($request) {
            foreach ($request->order as $index => $featuredProductId) {
                FeaturedProduct::where('id', $featuredProductId)
                    ->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Toggle active status of featured product
     */
    public function toggleStatus(FeaturedProduct $featuredProduct)
    {
        $featuredProduct->update([
            'is_active' => !$featuredProduct->is_active,
            'unfeatured_at' => $featuredProduct->is_active ? now() : null
        ]);

        $status = $featuredProduct->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Featured product {$status} successfully!");
    }

    /**
     * Remove product from featured
     */
    public function destroy(FeaturedProduct $featuredProduct)
    {
        $featuredProduct->delete();

        return redirect()->route('admin.featured-products.index')
            ->with('success', 'Product removed from featured successfully!');
    }


    /**
     * Get available products for AJAX search
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->whereDoesntHave('featuredProduct', function($q) {
                $q->where('is_active', true);
            })
            ->with('category')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
