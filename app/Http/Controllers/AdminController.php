<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'client')->count(),
            'total_users' => User::where('role', 'client')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    public function products(Request $request)
    {
        // Handle filter reset
        if ($request->has('reset')) {
            session()->forget('admin_product_filters');
            return redirect()->route('admin.products');
        }

        $filterKeys = ['search', 'category', 'brand', 'status', 'price_range'];
        $hasFilters = false;

        // Check if request has any filter parameters
        foreach ($filterKeys as $key) {
            if ($request->has($key)) {
                $hasFilters = true;
                break;
            }
        }

        // Update session or restore from session
        if ($hasFilters) {
            session(['admin_product_filters' => $request->only($filterKeys)]);
        } elseif (session()->has('admin_product_filters') && !$request->has('page')) {
            // If no filters in request and not paginating, restore from session
            $filters = session('admin_product_filters');
            if (!empty($filters)) {
                return redirect()->route('admin.products', $filters);
            }
        }

        $query = Product::with(['category', 'brand', 'variants']);

        // Search by name, brand, category, or variants (prioritizing name relevance)
        if ($request->has('search') && filled($request->search)) {
            $searchTerm = trim($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($bq) use ($searchTerm) {
                      $bq->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('category', function($cq) use ($searchTerm) {
                      $cq->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('variants', function($vq) use ($searchTerm) {
                      $vq->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });

            // Order by exact name prefix match first so most relevant products appear on top
            $query->orderByRaw("CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                ELSE 3 
            END", [$searchTerm . '%', '%' . $searchTerm . '%']);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by price range
        if ($request->has('price_range') && $request->price_range) {
            switch ($request->price_range) {
                case '0-100000':
                    $query->where('price', '<=', 100000);
                    break;
                case '100000-500000':
                    $query->whereBetween('price', [100000, 500000]);
                    break;
                case '500000-1000000':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case '1000000+':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // Add withQueryString() to persist filters in pagination links
        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        // Store current page for redirect after create/update/delete
        session(['admin_products_page' => $request->get('page', 1)]);
        
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function createProduct()
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $useVariants = $request->has('use_variants');
        
        // Dynamic validation based on variant usage
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
            // If using variants, make standard fields optional
            $validationRules['price'] = 'nullable|numeric|min:0|max:1000000000';
            $validationRules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
            $validationRules['stock'] = 'nullable|integer|min:0|max:1000000000';
            $validationRules['weight'] = 'required|integer|min:0|max:100000';
            $validationRules['variants'] = 'required|array|min:1';
            $validationRules['variants.*.name'] = 'required|string|max:255';
            $validationRules['variants.*.quantity'] = 'required|integer|min:0|max:1000000000';
            $validationRules['variants.*.price'] = 'required|numeric|min:0|max:1000000000';
        } else {
            // If not using variants, make standard fields required
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
            // Set default values for required fields
            'price' => 0,
            'discount_percentage' => 0,
            'stock' => 0,
            'weight' => $request->weight ?? 0,
        ];

        // Override with actual values if not using variants
        if (!$useVariants) {
            $data['price'] = $request->price ?? 0;
            $data['discount_percentage'] = $request->discount_percentage ?? 0;
            $data['stock'] = $request->stock ?? 0;
        }

        // Handle main photo
        if ($request->hasFile('main_photo')) {
            $mainPhotoPath = $request->file('main_photo')->store('products', 'public');
            $data['main_photo'] = $mainPhotoPath;
        }

        // Handle additional photos
        if ($request->hasFile('photos')) {
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('products', 'public');
            }
            $data['photos'] = $photoPaths;
        }

        $product = Product::create($data);

        // Handle variants
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

        $page = session('admin_products_page', 1);
        return redirect()->route('admin.products', ['page' => $page])
            ->with('success', 'Product created successfully!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $product->load('variants');
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $useVariants = $request->has('use_variants');
        
        // Dynamic validation based on variant usage
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
            // If using variants, make standard fields optional
            $validationRules['price'] = 'nullable|numeric|min:0|max:1000000000';
            $validationRules['discount_percentage'] = 'nullable|numeric|min:0|max:100';
            $validationRules['stock'] = 'nullable|integer|min:0|max:1000000000';
            $validationRules['weight'] = 'required|integer|min:0|max:100000';
            $validationRules['variants'] = 'required|array|min:1';
            $validationRules['variants.*.name'] = 'required|string|max:255';
            $validationRules['variants.*.quantity'] = 'required|integer|min:0|max:1000000000';
            $validationRules['variants.*.price'] = 'required|numeric|min:0|max:1000000000';
        } else {
            // If not using variants, make standard fields required
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
            // Set default values for required fields
            'price' => 0,
            'discount_percentage' => 0,
            'stock' => 0,
            'weight' => 0,
        ];

        // Override with actual values if not using variants
        if (!$useVariants) {
            $data['price'] = $request->price ?? 0;
            $data['discount_percentage'] = $request->discount_percentage ?? 0;
            $data['stock'] = $request->stock ?? 0;
            $data['weight'] = $request->weight ?? 0;
        } else {
            $data['weight'] = $request->weight ?? 0;
        }

        // Handle main photo
        if ($request->hasFile('main_photo')) {
            // Delete old main photo if exists
            if ($product->main_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->main_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->main_photo);
            }
            
            $mainPhotoPath = $request->file('main_photo')->store('products', 'public');
            $data['main_photo'] = $mainPhotoPath;
        }

        // Handle additional photos
        if ($request->hasFile('photos')) {
            // Delete old photos if exist
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

        // Handle variants - delete existing and create new ones
        if ($useVariants && $request->has('variants') && is_array($request->variants)) {
            DB::transaction(function () use ($product, $request) {
                // Delete existing variants. order_items are kept (variant_id set to NULL)
                // thanks to the nullOnDelete FK, so order history is preserved.
                $product->variants()->delete();

                // Create new variants
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
            // If not using variants, delete all existing variants
            DB::transaction(function () use ($product) {
                $product->variants()->delete();
            });
        }

        $page = session('admin_products_page', 1);
        return redirect()->route('admin.products', ['page' => $page])
            ->with('success', 'Product updated successfully!');
    }

    public function destroyProduct(Product $product)
    {
        // Prevent deleting products that have order history (would cascade-delete order items)
        if ($product->orderItems()->exists()) {
            return redirect()->route('admin.products', ['page' => session('admin_products_page', 1)])
                ->with('error', 'Produk tidak dapat dihapus karena sudah memiliki riwayat order. Nonaktifkan produk sebagai gantinya.');
        }

        $product->delete();
        $page = session('admin_products_page', 1);
        return redirect()->route('admin.products', ['page' => $page])
            ->with('success', 'Product deleted successfully!');
    }

    public function deleteMainPhoto(Product $product)
    {
        try {
            // Delete main photo file
            if ($product->main_photo && Storage::disk('public')->exists($product->main_photo)) {
                Storage::disk('public')->delete($product->main_photo);
            }

            // Update product to remove main photo
            $product->update(['main_photo' => null]);

            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Main photo deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Main photo deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting main photo: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting main photo: ' . $e->getMessage());
        }
    }

    public function deleteAdditionalPhoto(Request $request, Product $product)
    {
        try {
            $photoIndex = $request->input('photo_index');
            
            if ($product->photos && isset($product->photos[$photoIndex])) {
                $photoPath = $product->photos[$photoIndex];
                
                // Delete photo file
                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }

                // Remove photo from array
                $photos = $product->photos;
                unset($photos[$photoIndex]);
                $photos = array_values($photos); // Re-index array

                // Update product
                $product->update(['photos' => $photos]);
            }

            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'message' => 'Photo deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Photo deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting photo: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error deleting photo: ' . $e->getMessage());
        }
    }

    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
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
