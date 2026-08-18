@extends('layouts.app')

@section('title', 'Track Order - Bennylovescoffee')

@section('content')
<style>
body {
    background-color: #f5f5f5 !important;
}

.track-container {
    max-width: 1000px;
    margin: 40px auto 40px;
    padding: 0 20px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #000;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 24px;
    transition: all 0.3s ease;
}

.back-link:hover {
    color: #d4af37;
    transform: translateX(-4px);
}

.track-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #000;
}

.track-subtitle {
    color: #6b7280;
    margin-bottom: 32px;
    font-size: 16px;
}

/* Confirm Delivery Alert */
.confirm-delivery-alert {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.confirm-content {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.confirm-content i {
    font-size: 56px;
    color: white;
}

.confirm-content h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 4px;
}

.confirm-content p {
    font-size: 14px;
    opacity: 0.95;
}

.btn-confirm {
    background: white;
    color: #059669;
    padding: 14px 32px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-confirm:hover {
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

/* Tracking Number Card */
.tracking-number-card {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: white;
    padding: 28px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 2px solid #d4af37;
}

.tracking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.tracking-label {
    font-size: 14px;
    color: #d4af37;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.courier-badge {
    background: #d4af37;
    color: #000;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 13px;
}

.tracking-number {
    font-size: 36px;
    font-weight: 700;
    letter-spacing: 4px;
    font-family: 'Courier New', monospace;
    margin-bottom: 20px;
    color: #d4af37;
}

.tracking-actions {
    display: flex;
    gap: 12px;
}

.btn-copy {
    flex: 1;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid #d4af37;
    color: #d4af37;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-copy:hover {
    background: #d4af37;
    color: #000;
}

.btn-jne {
    flex: 1;
    background: #d4af37;
    border: 2px solid #d4af37;
    color: #000;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-jne:hover {
    background: #f0d872;
    border-color: #f0d872;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

/* Timeline Card */
.timeline-card {
    background: white;
    padding: 32px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
}

.timeline-title {
    font-size: 24px;
    font-weight: 700;
    color: #000;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Timeline */
.tracking-timeline {
    position: relative;
    padding-left: 40px;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, #10b981, #d1d5db);
}

.timeline-item {
    position: relative;
    padding-bottom: 32px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -24px;
    top: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    z-index: 2;
}

.timeline-dot.completed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 0 0 4px #f0fdf4;
}

.timeline-dot.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 0 0 4px #fffbeb;
    animation: pulse 2s infinite;
}

.timeline-dot.pending {
    background: #e5e7eb;
    color: #9ca3af;
    box-shadow: 0 0 0 4px #f9fafb;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 4px #fffbeb;
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 8px #fffbeb;
    }
}

.timeline-content {
    background: #f9fafb;
    padding: 16px 20px;
    border-radius: 8px;
    border-left: 4px solid transparent;
}

.timeline-item.completed .timeline-content {
    border-left-color: #10b981;
}

.timeline-item.active .timeline-content {
    border-left-color: #f59e0b;
    background: #fffbeb;
}

.timeline-status {
    font-size: 16px;
    font-weight: 700;
    color: #000;
    margin-bottom: 4px;
}

.timeline-date {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.timeline-location {
    font-size: 14px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Order Info Card */
.order-info-card {
    background: white;
    padding: 28px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #000;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 15px;
    color: #000;
    font-weight: 600;
}

/* Estimated Delivery */
.estimated-delivery {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.delivery-label {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 4px;
}

.delivery-date {
    font-size: 24px;
    font-weight: 700;
}

/* Responsive */
@media (max-width: 768px) {
    .track-container {
        margin-top: 40px;
    }
    
    .track-title {
        font-size: 24px;
    }
    
    .confirm-delivery-alert {
        flex-direction: column;
        align-items: stretch;
    }
    
    .confirm-content {
        flex-direction: column;
        text-align: center;
    }
    
    .btn-confirm {
        width: 100%;
        justify-content: center;
    }
    
    .tracking-number {
        font-size: 24px;
        letter-spacing: 2px;
    }
    
    .tracking-actions {
        flex-direction: column;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .timeline-card {
        padding: 24px 20px;
    }
}
</style>

<div class="track-container">
    <a href="{{ route('orders.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to My Orders
    </a>
    
    <h1 class="track-title">Track Your Order</h1>
    <p class="track-subtitle">Real-time tracking for order #{{ $order->order_number }}</p>
    
    <!-- Confirm Delivery Button (for shipped orders) -->
    @if($order->status === 'shipped')
    <div class="confirm-delivery-alert">
        <div class="confirm-content">
            <i class="fas fa-box-check"></i>
            <div>
                <h3>Package Received?</h3>
                <p>Confirm that you have received your order</p>
            </div>
        </div>
        <form action="{{ route('orders.confirmDelivery', $order) }}" method="POST" onsubmit="return confirm('Confirm that you have received this order?')">
            @csrf
            <button type="submit" class="btn-confirm">
                <i class="fas fa-check-circle"></i>
                Confirm Delivery
            </button>
        </form>
    </div>
    @endif

    <!-- Tracking Number Card -->
    @if($order->tracking_number)
    <div class="tracking-number-card">
        <div class="tracking-header">
            <div class="tracking-label">
                <i class="fas fa-barcode"></i> Tracking Number
            </div>
            <div class="courier-badge">
                <i class="fas fa-shipping-fast"></i> JNE
            </div>
        </div>
        
        <div class="tracking-number" id="trackingNumber">{{ $order->tracking_number }}</div>
        
        <div class="tracking-actions">
            <button class="btn-copy" onclick="copyTrackingNumber()">
                <i class="fas fa-copy"></i>
                Copy Number
            </button>
            <a href="https://www.jne.co.id/tracking-package" target="_blank" class="btn-jne">
                <i class="fas fa-external-link-alt"></i>
                Track on JNE Website
            </a>
        </div>
    </div>
    @endif
    
    <!-- Timeline Card -->
    <div class="timeline-card">
        <h2 class="timeline-title">
            <i class="fas fa-route"></i>
            Shipment Timeline
        </h2>
        
        <div class="tracking-timeline">
            <!-- Order Placed -->
            <div class="timeline-item completed">
                <div class="timeline-dot completed">
                    <i class="fas fa-check"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Order Placed</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-shopping-cart"></i>
                        Your order has been successfully placed
                    </div>
                </div>
            </div>
            
            <!-- Payment Confirmed -->
            @if($order->payment_proof_uploaded_at)
            <div class="timeline-item completed">
                <div class="timeline-dot completed">
                    <i class="fas fa-check"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Payment Confirmed</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        {{ $order->payment_proof_uploaded_at->format('M d, Y H:i') }}
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-credit-card"></i>
                        Payment has been verified
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Processing -->
            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
            <div class="timeline-item {{ $order->status === 'processing' ? 'active' : 'completed' }}">
                <div class="timeline-dot {{ $order->status === 'processing' ? 'active' : 'completed' }}">
                    <i class="fas {{ $order->status === 'processing' ? 'fa-spinner' : 'fa-check' }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Order Processing</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        {{ ($order->payment_proof_uploaded_at ?? $order->created_at)->addHours(2)->format('M d, Y H:i') }}
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-box"></i>
                        Your order is being prepared for shipment
                    </div>
                </div>
            </div>
            @else
            <div class="timeline-item pending">
                <div class="timeline-dot pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Order Processing</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        Pending
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-box"></i>
                        Waiting for payment confirmation
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Shipped -->
            @if(in_array($order->status, ['shipped', 'delivered']))
            <div class="timeline-item {{ $order->status === 'shipped' ? 'active' : 'completed' }}">
                <div class="timeline-dot {{ $order->status === 'shipped' ? 'active' : 'completed' }}">
                    <i class="fas {{ $order->status === 'shipped' ? 'fa-truck' : 'fa-check' }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Shipped</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        {{ ($order->shipped_at ?? now()->subDays(1))->format('M d, Y H:i') }}
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Package is on the way to destination
                    </div>
                </div>
            </div>
            @else
            <div class="timeline-item pending">
                <div class="timeline-dot pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Shipped</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        Pending
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-truck"></i>
                        Waiting for shipment
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Delivered -->
            @if($order->status === 'delivered')
            <div class="timeline-item completed">
                <div class="timeline-dot completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Delivered</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        {{ ($order->delivered_at ?? now())->format('M d, Y H:i') }}
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-home"></i>
                        Package has been delivered successfully
                    </div>
                </div>
            </div>
            @else
            <div class="timeline-item pending">
                <div class="timeline-dot pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-status">Delivered</div>
                    <div class="timeline-date">
                        <i class="fas fa-clock"></i>
                        {{ $order->shipping_etd ? 'Est. ' . $order->shipping_etd . ' days' : 'Pending' }}
                    </div>
                    <div class="timeline-location">
                        <i class="fas fa-home"></i>
                        Awaiting delivery
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Order Information -->
    <div class="order-info-card">
        <h3 class="info-title">Order Information</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Order Number</span>
                <span class="info-value">{{ $order->order_number }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value" style="text-transform: capitalize;">{{ $order->status }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Shipping Service</span>
                <span class="info-value">{{ $order->shipping_service ?? 'JNE Regular' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Recipient</span>
                <span class="info-value">{{ $order->customer_name }}</span>
            </div>
        </div>
        
        @if($order->shipping_etd && $order->status !== 'delivered')
        <div class="estimated-delivery">
            <div class="delivery-label">
                <i class="fas fa-clock"></i> Estimated Delivery
            </div>
            <div class="delivery-date">
                @if($order->shipped_at)
                    {{ $order->shipped_at->addDays((int)str_replace(' hari', '', $order->shipping_etd))->format('M d, Y') }}
                @else
                    {{ $order->shipping_etd }} days from shipment
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function copyTrackingNumber() {
    const trackingNumber = document.getElementById('trackingNumber').innerText;
    navigator.clipboard.writeText(trackingNumber).then(function() {
        // Show success message
        const btn = event.target.closest('.btn-copy');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.style.background = '#10b981';
        btn.style.borderColor = '#10b981';
        btn.style.color = 'white';
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.style.background = 'rgba(255, 255, 255, 0.1)';
            btn.style.borderColor = '#d4af37';
            btn.style.color = '#d4af37';
        }, 2000);
    });
}
</script>
@endsection
