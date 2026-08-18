@extends('layouts.app')

@section('title', 'My Orders - Bennylovescoffee')

@section('content')
<style>
body {
    background-color: #f5f5f5 !important;
}

.orders-container {
    max-width: 1200px;
    margin: 40px auto 40px;
    padding: 0 20px;
}

.orders-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.orders-title {
    font-size: 32px;
    font-weight: 700;
    color: #000;
}

.orders-count {
    color: #6b7280;
    font-size: 16px;
    margin-top: 4px;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.filter-tab {
    padding: 10px 20px;
    border-radius: 20px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.filter-tab:hover {
    border-color: #d4af37;
    color: #d4af37;
}

.filter-tab.active {
    background: linear-gradient(135deg, #000000, #333333);
    border-color: #000000;
    color: #d4af37;
}

/* Order Card */
.order-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.order-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    border-color: #d4af37;
}

/* Order Header */
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
    margin-bottom: 20px;
}

.order-info {
    flex: 1;
}

.order-number {
    font-size: 18px;
    font-weight: 700;
    color: #000;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.order-date {
    color: #6b7280;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.order-meta {
    text-align: right;
}

/* Status Badge */
.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
    margin-bottom: 12px;
}

.status-pending {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #000;
}

.status-processing {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.status-shipped {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
}

.status-delivered {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-cancelled {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.order-total {
    font-size: 24px;
    font-weight: 700;
    color: #ef4444;
}

/* Order Items */
.order-items {
    margin-bottom: 20px;
}

.items-header {
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
}

.order-item:hover {
    background: #f3f4f6;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: white;
    border: 1px solid #e5e7eb;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
    color: #999;
    font-size: 24px;
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-size: 15px;
    font-weight: 600;
    color: #000;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.item-meta {
    color: #6b7280;
    font-size: 13px;
}

.item-price {
    text-align: right;
    font-weight: 700;
    color: #000;
    font-size: 15px;
}

/* Order Footer */
.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 2px solid #f3f4f6;
}

.shipping-info {
    flex: 1;
}

.shipping-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.shipping-name {
    font-weight: 600;
    color: #000;
    font-size: 14px;
    margin-bottom: 2px;
}

.shipping-address {
    color: #6b7280;
    font-size: 13px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.order-actions {
    display: flex;
    gap: 12px;
}

.btn-view-details {
    background: linear-gradient(135deg, #000000, #333333);
    color: #d4af37;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 2px solid #000;
}

.btn-view-details:hover {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    border-color: #d4af37;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.btn-track {
    background: white;
    color: #000;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
}

.btn-track:hover {
    border-color: #d4af37;
    color: #d4af37;
}

/* Empty State */
.empty-orders {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 12px;
}

.filter-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    display: none;
}

.filter-empty-state.active {
    display: block;
}

.empty-icon {
    font-size: 80px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 28px;
    font-weight: 700;
    color: #000;
    margin-bottom: 12px;
}

.empty-text {
    color: #6b7280;
    font-size: 16px;
    margin-bottom: 32px;
}

.empty-filter-icon {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.empty-filter-title {
    font-size: 22px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
}

.empty-filter-text {
    color: #6b7280;
    font-size: 15px;
}

.btn-shop {
    background: linear-gradient(135deg, #000000, #333333);
    color: #d4af37;
    padding: 16px 40px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-shop:hover {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(212, 175, 55, 0.3);
}

/* Pagination */
.pagination-wrapper {
    margin-top: 32px;
    display: flex;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .orders-container {
        margin-top: 40px;
    }
    
    .orders-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .orders-title {
        font-size: 24px;
    }
    
    .order-header {
        flex-direction: column;
        gap: 12px;
    }
    
    .order-meta {
        text-align: left;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .order-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .btn-view-details,
    .btn-track {
        width: 100%;
        justify-content: center;
    }
    
    .filter-tabs {
        gap: 6px;
    }
    
    .filter-tab {
        padding: 8px 16px;
        font-size: 13px;
    }
}
</style>

<div class="orders-container">
    <div class="orders-header">
        <div>
            <h1 class="orders-title">My Orders</h1>
            <p class="orders-count">
                @if($orders->total() > 0)
                    {{ $orders->total() }} {{ $orders->total() == 1 ? 'order' : 'orders' }} found
                @else
                    No orders yet
                @endif
            </p>
        </div>
    </div>
    
    @if($orders->count() > 0)
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterOrders('all')">
            <i class="fas fa-list"></i> All Orders
        </button>
        <button class="filter-tab" onclick="filterOrders('pending')">
            <i class="fas fa-clock"></i> Pending
        </button>
        <button class="filter-tab" onclick="filterOrders('processing')">
            <i class="fas fa-cog"></i> Processing
        </button>
        <button class="filter-tab" onclick="filterOrders('shipped')">
            <i class="fas fa-shipping-fast"></i> Shipped
        </button>
        <button class="filter-tab" onclick="filterOrders('delivered')">
            <i class="fas fa-check-circle"></i> Delivered
        </button>
        <button class="filter-tab" onclick="filterOrders('cancelled')">
            <i class="fas fa-times-circle"></i> Cancelled
        </button>
    </div>
    
    <!-- Filter Empty States -->
    <div class="filter-empty-state" id="emptyPending">
        <i class="fas fa-clock empty-filter-icon"></i>
        <h3 class="empty-filter-title">Tidak Ada Pesanan Pending</h3>
        <p class="empty-filter-text">Belum ada pesanan yang menunggu pembayaran</p>
    </div>
    
    <div class="filter-empty-state" id="emptyProcessing">
        <i class="fas fa-cog empty-filter-icon"></i>
        <h3 class="empty-filter-title">Tidak Ada Pesanan Diproses</h3>
        <p class="empty-filter-text">Belum ada pesanan yang sedang diproses</p>
    </div>
    
    <div class="filter-empty-state" id="emptyShipped">
        <i class="fas fa-shipping-fast empty-filter-icon"></i>
        <h3 class="empty-filter-title">Tidak Ada Pesanan Dikirim</h3>
        <p class="empty-filter-text">Belum ada pesanan yang sedang dalam pengiriman</p>
    </div>
    
    <div class="filter-empty-state" id="emptyDelivered">
        <i class="fas fa-check-circle empty-filter-icon"></i>
        <h3 class="empty-filter-title">Tidak Ada Pesanan Selesai</h3>
        <p class="empty-filter-text">Belum ada pesanan yang telah selesai diterima</p>
    </div>
    
    <div class="filter-empty-state" id="emptyCancelled">
        <i class="fas fa-times-circle empty-filter-icon"></i>
        <h3 class="empty-filter-title">Tidak Ada Pesanan Dibatalkan</h3>
        <p class="empty-filter-text">Belum ada pesanan yang dibatalkan</p>
    </div>

    <!-- Orders List -->
    <div class="orders-list">
        @foreach($orders as $order)
        <div class="order-card" data-status="{{ $order->status }}">
            <!-- Order Header -->
            <div class="order-header">
                <div class="order-info">
                    <div class="order-number">
                        <i class="fas fa-receipt"></i>
                        {{ $order->order_number }}
                    </div>
                    <div class="order-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
                <div class="order-meta">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <div class="order-total">
                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="order-items">
                <div class="items-header">
                    <i class="fas fa-box"></i>
                    {{ $order->orderItems->count() }} {{ $order->orderItems->count() == 1 ? 'Item' : 'Items' }}
                </div>
                @foreach($order->orderItems->take(3) as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->product->main_photo)
                            <img src="{{ asset('storage/' . $item->product->main_photo) }}" 
                                 alt="{{ $item->product->name }}">
                        @elseif($item->product->foto)
                            <img src="{{ asset('storage/' . $item->product->foto) }}" 
                                 alt="{{ $item->product->name }}">
                        @elseif($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}">
                        @else
                            <div class="item-placeholder">
                                <i class="fas fa-coffee"></i>
                        </div>
                        @endif
                        </div>
                    <div class="item-details">
                        <div class="item-name">{{ $item->product->name }}</div>
                        <div class="item-meta">
                            Qty: {{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="item-price">
                        Rp{{ number_format($item->total, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                
                @if($order->orderItems->count() > 3)
                <div style="text-align: center; padding: 8px; color: #6b7280; font-size: 13px;">
                    <i class="fas fa-ellipsis-h"></i>
                    +{{ $order->orderItems->count() - 3 }} more items
                </div>
                @endif
            </div>
            
            <!-- Order Footer -->
            <div class="order-footer">
                <div class="shipping-info">
                    <div class="shipping-label">
                        <i class="fas fa-shipping-fast"></i> Shipping To
                    </div>
                    <div class="shipping-name">{{ $order->customer_name }}</div>
                    <div class="shipping-address">{{ $order->shipping_address }}</div>
                </div>
                <div class="order-actions">
                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                    <a href="{{ route('orders.track', $order) }}" class="btn-track">
                        <i class="fas fa-map-marker-alt"></i>
                        Track Order
                    </a>
                    @endif
                    <a href="{{ route('orders.show', $order) }}" class="btn-view-details">
                        <i class="fas fa-eye"></i>
                    View Details
                </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
    
    @else
    <!-- Empty State -->
    <div class="empty-orders">
        <i class="fas fa-shopping-bag empty-icon"></i>
        <h2 class="empty-title">No Orders Yet</h2>
        <p class="empty-text">You haven't placed any orders. Start shopping to see your orders here!</p>
        <a href="{{ route('products.index') }}" class="btn-shop">
            <i class="fas fa-shopping-cart"></i>
            Start Shopping
        </a>
    </div>
    @endif
</div>

<script>
function filterOrders(status) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.closest('.filter-tab').classList.add('active');
    
    // Hide all empty states first
    document.querySelectorAll('.filter-empty-state').forEach(emptyState => {
        emptyState.classList.remove('active');
    });
    
    // Filter orders
    const orderCards = document.querySelectorAll('.order-card');
    let visibleCount = 0;
    
    orderCards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show empty state if no orders found
    if (visibleCount === 0 && status !== 'all') {
        const emptyStateId = 'empty' + status.charAt(0).toUpperCase() + status.slice(1);
        const emptyState = document.getElementById(emptyStateId);
        if (emptyState) {
            emptyState.classList.add('active');
        }
    }
}
</script>
@endsection