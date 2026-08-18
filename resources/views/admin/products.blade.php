@extends('layouts.app')

@section('title', 'Manage Products - Bennylovescoffee')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Products</h1>
        <a href="{{ route('products.index') }}" class="add-to-cart">
            View Store
        </a>
    </div>
    
    @if($products->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Image</th>
                    <th class="text-left py-2">Name</th>
                    <th class="text-left py-2">Category</th>
                    <th class="text-left py-2">Price</th>
                    <th class="text-left py-2">Stock</th>
                    <th class="text-left py-2">Status</th>
                    <th class="text-left py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b">
                    <td class="py-2">
                        <div class="product-image w-16 h-16">
                            <div class="product-placeholder grinder-product"></div>
                        </div>
                    </td>
                    <td class="py-2">
                        <div>
                            <div class="font-semibold">{{ $product->name }}</div>
                            <div class="text-sm text-gray-600">{{ Str::limit($product->description, 50) }}</div>
                        </div>
                    </td>
                    <td class="py-2">{{ $product->category->name }}</td>
                    <td class="py-2">
                        @if($product->discount_price)
                        <div>
                            <div class="text-sm line-through text-gray-500">${{ number_format($product->price, 2) }}</div>
                            <div class="font-semibold text-red-600">${{ number_format($product->discount_price, 2) }}</div>
                        </div>
                        @else
                        <div class="font-semibold">${{ number_format($product->price, 2) }}</div>
                        @endif
                    </td>
                    <td class="py-2">{{ $product->stock }}</td>
                    <td class="py-2">
                        <span class="px-2 py-1 rounded text-sm {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-box text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-2xl font-semibold mb-4">No products found</h2>
        <p class="text-gray-600">Products will appear here once they are added to the database.</p>
    </div>
    @endif
</div>
@endsection
