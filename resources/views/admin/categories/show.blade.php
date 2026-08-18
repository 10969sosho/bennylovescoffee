@extends('admin.layouts.admin')

@section('title', 'View Category')
@section('page-title', 'View Category')

@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">View Category</h2>
                <p class="text-gray-600 mt-2">Category details and information</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="admin-btn text-white px-6 py-3 rounded-lg">
                    <i class="fas fa-edit mr-2"></i>Edit Category
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Categories
                </a>
            </div>
        </div>

        <div class="space-y-6">
                <!-- Category Details -->
                <div class="admin-card p-6 border-2 border-blue-200">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-info-circle text-blue-500 mr-3 text-xl"></i>
                        <h3 class="text-xl font-semibold text-gray-800">Category Details</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Category Icon and Name -->
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-md">
                                @else
                                    <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center border-2 border-blue-300">
                                        <i class="fas fa-tag text-blue-600 text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                                <div class="flex items-center space-x-4">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="text-sm text-gray-500">ID: {{ $category->id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Description</h4>
                            <div class="prose max-w-none">
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $category->description ?: 'No description provided' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Products in this Category -->
            <div class="admin-card p-6 border-2 border-green-200">
                <div class="flex items-center mb-6">
                    <i class="fas fa-box text-green-500 mr-3 text-xl"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Products in this Category</h3>
                </div>
                
                @if($category->products && $category->products->count() > 0)
                    <div class="space-y-4">
                        @foreach($category->products as $product)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-shrink-0">
                                    @if($product->main_photo)
                                        <img src="{{ Storage::url($product->main_photo) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300">
                                            <i class="fas fa-box text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ Str::limit($product->description, 100) }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-sm font-medium text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        @if($product->discount_percentage > 0)
                                            <span class="text-sm text-red-600">-{{ $product->discount_percentage }}%</span>
                                        @endif
                                        <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                                        <span class="text-sm text-gray-500">Sold: {{ $product->sold_count }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-box text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-600 mb-2">No Products</h4>
                        <p class="text-gray-500">No products found in this category</p>
                    </div>
                @endif
            </div>

            <!-- Category Information & Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category Information -->
                <div class="admin-card p-6 border-2 border-gray-200">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Category Information</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Category ID</span>
                            <span class="text-sm text-gray-900">#{{ $category->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Status</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Products</span>
                            <span class="text-sm text-gray-900">{{ $category->products_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Created</span>
                            <span class="text-sm text-gray-900">{{ $category->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600">Updated</span>
                            <span class="text-sm text-gray-900">{{ $category->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="admin-card p-6 border-2 border-orange-200">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-cogs text-orange-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Actions</h3>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition border border-blue-200">
                            <i class="fas fa-edit mr-2"></i>Edit Category
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition border border-red-200">
                                <i class="fas fa-trash mr-2"></i>Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
