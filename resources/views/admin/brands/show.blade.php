@extends('admin.layouts.admin')

@section('title', 'Brand Details')
@section('page-title', 'Brand Details')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $brand->name }}</h2>
                <p class="text-gray-600 mt-2">Brand Details</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.brands.edit', $brand) }}" 
                   class="admin-btn text-white px-6 py-3 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>Edit Brand
                </a>
                <a href="{{ route('admin.brands.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Brands
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Brand Information -->
            <div class="lg:col-span-2">
                <div class="admin-card p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Brand Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brand Name</label>
                            <p class="text-lg text-gray-800">{{ $brand->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <p class="text-gray-600">{{ $brand->slug }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <p class="text-gray-800">{{ $brand->description ?: 'No description provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            @if($brand->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactive
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                            <p class="text-gray-600">{{ $brand->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                            <p class="text-gray-600">{{ $brand->updated_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brand Image & Stats -->
            <div class="space-y-6">
                <!-- Brand Image -->
                <div class="admin-card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Brand Image</h3>
                    @if($brand->image)
                        <img src="{{ asset('storage/' . $brand->image) }}" 
                             alt="{{ $brand->name }}" 
                             class="w-full h-48 object-cover rounded-lg border-2 border-gray-200">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-award text-gray-400 text-4xl mb-2"></i>
                                <p class="text-gray-500">No image uploaded</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Brand Statistics -->
                <div class="admin-card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Products</span>
                            <span class="font-semibold text-blue-600">{{ $brand->products_count }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Active Products</span>
                            <span class="font-semibold text-green-600">{{ $brand->products()->where('is_active', true)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Products -->
        @if($brand->products->count() > 0)
            <div class="mt-8">
                <div class="admin-card p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Products in this Brand</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-4 px-6 font-semibold text-gray-800">Product</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-800">Category</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-800">Price</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-800">Stock</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-800">Status</th>
                                    <th class="text-left py-4 px-6 font-semibold text-gray-800">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brand->products as $product)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                @if($product->main_photo)
                                                    <img src="{{ asset('storage/' . $product->main_photo) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="w-12 h-12 object-cover rounded-lg">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-gray-800">{{ $product->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $product->slug }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-gray-600">{{ $product->category->name ?? 'No Category' }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-gray-600">{{ $product->stock }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($product->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition" 
                                               title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
