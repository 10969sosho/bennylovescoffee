@extends('admin.layouts.admin')

@section('title', 'Order Details - Admin')

@section('content')
<style>
.order-detail-container {
    padding: 24px;
}

.order-header {
    background: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.order-title {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.order-subtitle {
    color: #6b7280;
    font-size: 14px;
}

.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.order-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 14px;
}

.info-value {
    font-weight: 600;
    color: #1a1a1a;
    font-size: 14px;
    text-align: right;
}

/* Payment Proof Section */
.payment-proof-card {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 2px solid #38bdf8;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.proof-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.proof-header i {
    font-size: 24px;
    color: #0284c7;
}

.proof-title {
    font-size: 18px;
    font-weight: 700;
    color: #0c4a6e;
}

.proof-uploaded-date {
    color: #075985;
    font-size: 13px;
    margin-bottom: 16px;
}

.proof-image-container {
    background: white;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
}

.proof-image-container img {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.proof-image-container img:hover {
    transform: scale(1.02);
}

.proof-verification {
    display: flex;
    gap: 12px;
}

.btn-verify {
    flex: 1;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-verify:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-reject {
    flex: 1;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Order Items */
.order-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
}

.item-name {
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.item-meta {
    color: #6b7280;
    font-size: 13px;
}

.item-price {
    text-align: right;
    font-weight: 700;
    color: #1a1a1a;
}

/* Actions Section */
.actions-card {
    background: linear-gradient(135deg, #1a1a1a, #000000);
    color: white;
    padding: 24px;
    border-radius: 12px;
}

.action-form {
    margin-bottom: 20px;
}

.action-form:last-child {
    margin-bottom: 0;
}

.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-input,
.form-select {
    width: 100%;
    padding: 12px;
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #d4af37;
    background: rgba(255, 255, 255, 0.1);
}

.form-select option {
    background: #1a1a1a;
    color: white;
}

.btn-action {
    width: 100%;
    padding: 14px 20px;
    border-radius: 8px;
    border: 2px solid #d4af37;
    background: #d4af37;
    color: #000;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: #f0d872;
    border-color: #f0d872;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
}

.btn-action-secondary {
    background: transparent;
    color: #d4af37;
}

.btn-action-secondary:hover {
    background: rgba(212, 175, 55, 0.1);
}

.btn-action-danger {
    border-color: #ef4444;
    background: transparent;
    color: #ef4444;
}

.btn-action-danger:hover {
    background: #ef4444;
    color: white;
}

/* Modal */
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    justify-content: center;
    align-items: center;
}

.image-modal.active {
    display: flex;
}

.modal-content {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: white;
    font-size: 40px;
    font-weight: 700;
    cursor: pointer;
}

.modal-close:hover {
    color: #d4af37;
}

/* Alert */
.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}
</style>

<div class="order-detail-container">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Order Header -->
    <div class="order-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div class="order-title">Order #{{ $order->order_number }}</div>
                <div class="order-subtitle">Created on {{ $order->created_at->format('M d, Y H:i') }}</div>
            </div>
            <span class="status-badge status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <!-- Payment Proof (if uploaded) -->
    @if($order->payment_proof)
    <div class="payment-proof-card">
        <div class="proof-header">
            <i class="fas fa-receipt"></i>
            <div>
                <div class="proof-title">Payment Proof - Bukti Transfer</div>
                <div class="proof-uploaded-date">
                    <i class="fas fa-clock"></i>
                    Uploaded: {{ $order->payment_proof_uploaded_at->format('M d, Y H:i') }}
                </div>
            </div>
        </div>

        <div class="proof-image-container">
            <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                 alt="Payment Proof" 
                 onclick="openImageModal(this.src)">
        </div>

        <div style="background: white; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #6b7280; font-weight: 600;">Order Total:</span>
                <span style="color: #1a1a1a; font-weight: 700; font-size: 18px;">
                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                </span>
            </div>
            <div style="font-size: 13px; color: #6b7280;">
                <i class="fas fa-info-circle"></i>
                Verify that the transfer amount matches the order total
            </div>
        </div>

        @if($order->status === 'processing')
        <div class="proof-verification">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" style="flex: 1;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="processing">
                <button type="submit" class="btn-verify">
                    <i class="fas fa-check"></i>
                    Payment Verified
                </button>
            </form>
            <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" style="flex: 1;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-reject" onclick="return confirm('Cancel this order?')">
                    <i class="fas fa-times"></i>
                    Reject & Cancel
                </button>
            </form>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Grid -->
    <div class="order-grid">
        <!-- Left Column -->
        <div>
            <!-- Order Items -->
            <div class="card">
                <h3 class="card-title">Order Items</h3>
                @foreach($order->orderItems as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->product->main_photo)
                            <img src="{{ asset('storage/' . $item->product->main_photo) }}" alt="{{ $item->product->name }}">
                        @elseif($item->product->foto)
                            <img src="{{ asset('storage/' . $item->product->foto) }}" alt="{{ $item->product->name }}">
                        @else
                            <div style="width: 100%; height: 100%; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-coffee" style="color: #9ca3af;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="item-details">
                        <div class="item-name">{{ $item->product->name }}</div>
                        @if($item->variant_name)
                            <div class="item-meta">Variant: {{ $item->variant_name }}</div>
                        @endif
                        <div class="item-meta">
                            Quantity: {{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="item-price">
                        Rp{{ number_format($item->total, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach

                <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f3f4f6;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Subtotal:</span>
                        <span style="font-weight: 600;">Rp{{ number_format($order->orderItems->sum('total'), 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Shipping:</span>
                        <span style="font-weight: 600;">Rp{{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 2px solid #f3f4f6;">
                        <span style="font-size: 18px; font-weight: 700;">Total:</span>
                        <span style="font-size: 20px; font-weight: 700; color: #ef4444;">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="card" style="margin-top: 24px;">
                <h3 class="card-title">Customer & Shipping Information</h3>
                <div class="info-row">
                    <span class="info-label">Customer Name</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
                @if($order->customer_phone)
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $order->customer_phone }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Shipping Address</span>
                    <span class="info-value" style="text-align: right; max-width: 60%;">{{ $order->shipping_address }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Shipping Service</span>
                    <span class="info-value">{{ $order->shipping_service ?? 'JNE Regular' }}</span>
                </div>
                @if($order->tracking_number)
                <div class="info-row">
                    <span class="info-label">Tracking Number</span>
                    <span class="info-value" style="font-family: 'Courier New', monospace; color: #d4af37;">{{ $order->tracking_number }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Actions -->
        <div>
            <div class="actions-card">
                <h3 class="card-title" style="color: #d4af37;">Order Actions</h3>

                <!-- Add Tracking Number (for processing orders) -->
                @if($order->status === 'processing' && !$order->tracking_number)
                <form action="{{ route('admin.orders.update-tracking', $order) }}" method="POST" class="action-form">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-barcode"></i> Add Tracking Number (Resi)
                        </label>
                        <input type="text" name="tracking_number" class="form-input" 
                               placeholder="e.g., JNE123456789" required>
                    </div>
                    <button type="submit" class="btn-action">
                        <i class="fas fa-shipping-fast"></i>
                        Ship Order & Add Resi
                    </button>
                </form>
                @endif

                <!-- Update Status -->
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="action-form">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-sync-alt"></i> Update Order Status
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending - Waiting Payment</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing - Payment Received</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped - On Delivery</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered - Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-action btn-action-secondary">
                        <i class="fas fa-save"></i>
                        Update Status
                    </button>
                </form>

                <!-- Mark as Delivered -->
                @if($order->status === 'shipped')
                <form action="{{ route('admin.orders.deliver', $order) }}" method="POST" class="action-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-action">
                        <i class="fas fa-check-circle"></i>
                        Mark as Delivered
                    </button>
                </form>
                @endif

                <!-- Cancel Order -->
                @if(!in_array($order->status, ['delivered', 'cancelled']))
                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="action-form">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-action btn-action-danger" 
                            onclick="return confirm('Are you sure you want to cancel this order?')">
                        <i class="fas fa-ban"></i>
                        Cancel Order
                    </button>
                </form>
                @endif

                <!-- Order Timeline Info -->
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(212, 175, 55, 0.3);">
                    <div style="font-size: 12px; color: rgba(255, 255, 255, 0.7); margin-bottom: 12px;">
                        <i class="fas fa-info-circle"></i> ORDER TIMELINE
                    </div>
                    @if($order->payment_proof_uploaded_at)
                    <div style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 8px;">
                        <i class="fas fa-upload"></i> Payment uploaded: {{ $order->payment_proof_uploaded_at->format('M d, H:i') }}
                    </div>
                    @endif
                    @if($order->shipped_at)
                    <div style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 8px;">
                        <i class="fas fa-truck"></i> Shipped: {{ $order->shipped_at->format('M d, H:i') }}
                    </div>
                    @endif
                    @if($order->delivered_at)
                    <div style="font-size: 13px; color: rgba(255, 255, 255, 0.9);">
                        <i class="fas fa-check"></i> Delivered: {{ $order->delivered_at->format('M d, H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="image-modal" id="imageModal" onclick="closeImageModal()">
    <span class="modal-close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.classList.add('active');
    modalImg.src = src;
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection
