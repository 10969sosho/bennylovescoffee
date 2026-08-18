@extends('layouts.app')

@section('title', 'Manage Orders - Bennylovescoffee')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Orders</h1>
        <a href="{{ route('admin.dashboard') }}" class="add-to-cart">
            Back to Dashboard
        </a>
    </div>
    
    @if($orders->count() > 0)
    <div class="space-y-6">
        @foreach($orders as $order)
        <div class="product-card p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold">Order #{{ $order->order_number }}</h3>
                    <p class="text-gray-600">{{ $order->customer_name }} - {{ $order->customer_email }}</p>
                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $order->getStatusBadgeClass() }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <p class="text-lg font-semibold mt-2">${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
            
            <div class="mb-4">
                <h4 class="font-semibold mb-2">Order Items:</h4>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-4">
                        <div class="product-image w-12 h-12">
                            <div class="product-placeholder grinder-product"></div>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">${{ number_format($item->total, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Shipping to: {{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                    <p class="text-sm text-gray-600">Payment: {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                    <a href="{{ route('orders.show', $order) }}" class="add-to-cart">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-2xl font-semibold mb-4">No orders found</h2>
        <p class="text-gray-600">Orders will appear here once customers start placing orders.</p>
    </div>
    @endif
</div>
@endsection
