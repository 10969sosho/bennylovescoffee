<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_users' => User::where('role', 'client')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_orders' => $recentOrders
            ]
        ]);
    }

    public function products()
    {
        $products = Product::with('category')->latest()->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!',
            'data' => $order
        ]);
    }

    public function storeProduct(Request $request)
    {
        $useVariants = $request->has('use_variants');

        $validationRules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'main_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($useVariants) {
            $validationRules['price'] = 'nullable|numeric|min:0|max:1000000000';
            $validationRules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
            $validationRules['stock'] = 'nullable|integer|min:0|max:1000000000';
            $validationRules['weight'] = 'required|integer|min:0|max:100000';
            $validationRules['variants'] = 'required|array|min:1';
            $validationRules['variants.*.name'] = 'required|string|max:255';
            $validationRules['variants.*.quantity'] = 'required|integer|min:0|max:1000000000';
            $validationRules['variants.*.price'] = 'required|numeric|min:0|max:1000000000';
        } else {
            $validationRules['price'] = 'required|numeric|min:0|max:1000000000';
            $validationRules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
            $validationRules['stock'] = 'required|integer|min:0|max:1000000000';
            $validationRules['weight'] = 'required|integer|min:0|max:100000';
        }

        $request->validate($validationRules);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'is_active' => $request->has('is_active'),
            'slug' => $this->generateUniqueSlug($request->name),
            'rating' => 0,
            'sold_count' => 0,
            'price' => 0,
            'discount_percentage' => 0,
            'stock' => 0,
            'weight' => $request->weight ?? 0,
        ];

        if (!$useVariants) {
            $data['price'] = $request->price ?? 0;
            $data['discount_percentage'] = $request->discount_percentage ?? 0;
            $data['stock'] = $request->stock ?? 0;
        }

        if ($request->hasFile('main_photo')) {
            $data['main_photo'] = $request->file('main_photo')->store('products', 'public');
        }

        if ($request->hasFile('photos')) {
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('products', 'public');
            }
            $data['photos'] = $photoPaths;
        }

        $product = Product::create($data);

        if ($useVariants && $request->has('variants') && is_array($request->variants)) {
            DB::transaction(function () use ($product, $request) {
                foreach ($request->variants as $variantData) {
                    if (!empty($variantData['name']) && !empty($variantData['price'])) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'quantity' => $variantData['quantity'] ?? 0,
                            'price' => $variantData['price'],
                            'is_active' => true,
                        ]);
                    }
                }
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'data' => $product->load('variants')
        ], 201);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $useVariants = $request->has('use_variants');

        $validationRules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'main_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($useVariants) {
            $validationRules['price'] = 'nullable|numeric|min:0|max:1000000000';
            $validationRules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
            $validationRules['stock'] = 'nullable|integer|min:0|max:1000000000';
            $validationRules['weight'] = 'required|integer|min:0|max:100000';
            $validationRules['variants'] = 'required|array|min:1';
            $validationRules['variants.*.name'] = 'required|string|max:255';
            $validationRules['variants.*.quantity'] = 'required|integer|min:0|max:1000000000';
            $validationRules['variants.*.price'] = 'required|numeric|min:0|max:1000000000';
        } else {
            $validationRules['price'] = 'required|numeric|min:0|max:1000000000';
            $validationRules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
            $validationRules['stock'] = 'required|integer|min:0|max:1000000000';
            $validationRules['weight'] = 'required|integer|min:0|max:100000';
        }

        $request->validate($validationRules);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'is_active' => $request->has('is_active'),
            'slug' => $product->name !== $request->name
                ? $this->generateUniqueSlug($request->name, $product->id)
                : $product->slug,
            'price' => 0,
            'discount_percentage' => 0,
            'stock' => 0,
            'weight' => $request->weight ?? 0,
        ];

        if (!$useVariants) {
            $data['price'] = $request->price ?? 0;
            $data['discount_percentage'] = $request->discount_percentage ?? 0;
            $data['stock'] = $request->stock ?? 0;
        }

        if ($request->hasFile('main_photo')) {
            if ($product->main_photo && Storage::disk('public')->exists($product->main_photo)) {
                Storage::disk('public')->delete($product->main_photo);
            }
            $data['main_photo'] = $request->file('main_photo')->store('products', 'public');
        }

        if ($request->hasFile('photos')) {
            if ($product->photos) {
                foreach ($product->photos as $photo) {
                    if (Storage::disk('public')->exists($photo)) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('products', 'public');
            }
            $data['photos'] = $photoPaths;
        }

        $product->update($data);

        if ($useVariants && $request->has('variants') && is_array($request->variants)) {
            DB::transaction(function () use ($product, $request) {
                $product->variants()->delete();
                foreach ($request->variants as $variantData) {
                    if (!empty($variantData['name']) && !empty($variantData['price'])) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'quantity' => $variantData['quantity'] ?? 0,
                            'price' => $variantData['price'],
                            'is_active' => true,
                        ]);
                    }
                }
            });
        } else {
            DB::transaction(function () use ($product) {
                $product->variants()->delete();
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'data' => $product->load('variants')
        ]);
    }

    public function destroyProduct(Product $product)
    {
        if (OrderItem::where('product_id', $product->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Product cannot be deleted because it has order history.'
            ], 422);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }

    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = Product::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = Product::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
