@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
        <div class="admin-card p-3 sm:p-4 md:p-5 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['total_orders'] }}</div>
            <div class="text-gray-600">Total Orders</div>
        </div>
        <div class="admin-card p-3 sm:p-4 md:p-5 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="text-gray-600">Total Revenue</div>
        </div>
        <div class="admin-card p-3 sm:p-4 md:p-5 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['total_products'] }}</div>
            <div class="text-gray-600">Total Products</div>
        </div>
        <div class="admin-card p-3 sm:p-4 md:p-5 text-center">
            <div class="text-3xl font-bold text-orange-600 mb-2">{{ $stats['total_customers'] }}</div>
            <div class="text-gray-600">Total Customers</div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="admin-card p-3 sm:p-4 md:p-5 mb-4">
        <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
        @if($recentOrders->count() > 0)
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Order #</th>
                        <th class="text-left py-2">Customer</th>
                        <th class="text-left py-2">Total</th>
                        <th class="text-left py-2">Status</th>
                        <th class="text-left py-2">Date</th>
                        <th class="text-left py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr class="border-b">
                        <td class="py-2">{{ $order->order_number }}</td>
                        <td class="py-2">{{ $order->customer_name }}</td>
                        <td class="py-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-sm {{ $order->getStatusBadgeClass() }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="py-2">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="py-2">
                            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                View All
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-600">No orders found.</p>
        @endif
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.products') }}" class="admin-card p-3 sm:p-4 md:p-5 text-center hover:shadow-lg transition">
            <i class="fas fa-box text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-lg font-semibold mb-2">Manage Products</h3>
            <p class="text-gray-600">Add, edit, or remove products</p>
        </a>
        
        <a href="{{ route('admin.orders.index') }}" class="admin-card p-3 sm:p-4 md:p-5 text-center hover:shadow-lg transition">
            <i class="fas fa-shopping-cart text-4xl text-green-600 mb-4"></i>
            <h3 class="text-lg font-semibold mb-2">Manage Orders</h3>
            <p class="text-gray-600">View and update order status</p>
        </a>
        
        <a href="{{ route('home') }}" target="_blank" class="admin-card p-3 sm:p-4 md:p-5 text-center hover:shadow-lg transition">
            <i class="fas fa-eye text-4xl text-purple-600 mb-4"></i>
            <h3 class="text-lg font-semibold mb-2">View Store</h3>
            <p class="text-gray-600">See how customers view the store</p>
        </a>
    </div>
@endsection