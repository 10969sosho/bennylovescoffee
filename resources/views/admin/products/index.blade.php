@extends('admin.layouts.admin')

@section('title', 'Manage Products')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Products Management</h2>
            <a href="{{ route('admin.products.create') }}" class="admin-btn text-white px-6 py-3 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="admin-card p-6 mb-6 border-2 border-blue-200">
            <div class="flex items-center mb-4">
                <i class="fas fa-search text-blue-500 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Search & Filters</h3>
            </div>
            <form method="GET" action="{{ route('admin.products') }}" class="space-y-6">
                <!-- Search Bar -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-search mr-2 text-blue-500"></i>Search Products
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by product name or description..."
                                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="admin-btn text-white px-6 py-3 rounded-lg border-2 border-blue-600">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                            <a href="{{ route('admin.products', ['reset' => 1]) }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-filter mr-2 text-blue-500"></i>Filter Options
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select id="category" name="category" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                            <select id="brand" name="brand" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <select id="price_range" name="price_range" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">All Prices</option>
                                <option value="0-100000" {{ request('price_range') == '0-100000' ? 'selected' : '' }}>Under Rp 100,000</option>
                                <option value="100000-500000" {{ request('price_range') == '100000-500000' ? 'selected' : '' }}>Rp 100,000 - 500,000</option>
                                <option value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'selected' : '' }}>Rp 500,000 - 1,000,000</option>
                                <option value="1000000+" {{ request('price_range') == '1000000+' ? 'selected' : '' }}>Above Rp 1,000,000</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search Results Info -->
        @if(request()->hasAny(['search', 'category', 'status', 'price_range']))
        <div class="admin-card p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-filter text-blue-500"></i>
                    <span class="text-sm text-gray-600">
                        @if(request('search'))
                            Searching for: <strong>"{{ request('search') }}"</strong>
                        @endif
                        @if(request('category'))
                            | Category: <strong>{{ $categories->where('id', request('category'))->first()->name ?? 'Unknown' }}</strong>
                        @endif
                        @if(request('brand'))
                            | Brand: <strong>{{ $brands->where('id', request('brand'))->first()->name ?? 'Unknown' }}</strong>
                        @endif
                        @if(request('status'))
                            | Status: <strong>{{ ucfirst(request('status')) }}</strong>
                        @endif
                        @if(request('price_range'))
                            | Price: <strong>{{ request('price_range') }}</strong>
                        @endif
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Found {{ $products->total() }} product(s)
                </div>
            </div>
        </div>
        @endif

        <!-- Products Table -->
        <div class="admin-card overflow-hidden border-2 border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Category</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Stock</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Weight</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-200">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y-2 divide-gray-200">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition border-b border-gray-200">
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-20 w-20">
                                        @if($product->main_photo)
                                            <img src="{{ asset('storage/' . $product->main_photo) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="h-20 w-20 object-cover rounded-lg photo-preview border-2 border-gray-200">
                                        @else
                                            <div class="h-20 w-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300">
                                                <i class="fas fa-image text-2xl text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($product->description, 60) }}</div>
                                        @if($product->discount_percentage)
                                            <div class="text-xs text-red-600 font-semibold mt-1">
                                                -{{ $product->discount_percentage }}% OFF
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <span class="text-sm text-gray-900">{{ $product->category->name }}</span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="text-sm text-gray-900">
                                    @if($product->hasVariants())
                                        <!-- Price Range for Products with Variants -->
                                        <div class="text-blue-600 font-semibold">{{ $product->getPriceRange() }}</div>
                                        <div class="text-xs text-gray-500">{{ $product->getActiveVariants()->count() }} variant(s)</div>
                                    @else
                                        <!-- Single Price for Products without Variants -->
                                        @if($product->discount_percentage)
                                            <div class="text-green-600 font-semibold">Rp {{ number_format($product->getCurrentPrice(), 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @else
                                            <div class="text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center">
                                    @if($product->hasVariants())
                                        <!-- Total Stock for Products with Variants -->
                                        <span class="text-sm text-gray-900">{{ $product->getTotalStock() }}</span>
                                        <span class="ml-2 text-xs text-blue-600">
                                            ({{ $product->getActiveVariants()->count() }} variants)
                                        </span>
                                    @else
                                        <!-- Single Stock for Products without Variants -->
                                        <span class="text-sm text-gray-900">{{ $product->stock }}</span>
                                    @endif
                                    @if($product->sold_count > 0)
                                        <span class="ml-2 text-xs text-green-600">
                                            ({{ $product->sold_count }} sold)
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                @if($product->weight > 0)
                                    <span class="text-sm text-gray-900">{{ $product->weight }} g</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Empty</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap border-r border-gray-200">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition border border-red-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box text-4xl mb-4"></i>
                                <p class="text-lg">No products found</p>
                                <p class="text-sm">Try adjusting your filters or create a new product</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        // Initialize Select2 for filters
        $(document).ready(function() {
            $('#category, #brand, #status, #price_range').select2({
                placeholder: 'Select option...',
                allowClear: true,
                width: '100%'
            });

            // Auto-submit form when filters change
            $('#category, #brand, #status, #price_range').on('change', function() {
                $(this).closest('form').submit();
            });

            // Search with Enter key
            $('#search').on('keypress', function(e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });

            // Clear search when clear button is clicked
            $('a[href="{{ route('admin.products') }}"]').on('click', function() {
                $('#search').val('');
            });
        });
    </script>
@endsection
