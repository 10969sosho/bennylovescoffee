@extends('layouts.app')

@section('title', 'Order Details - Bennylovescoffee')

@section('content')
<style>
body {
    background-color: #f5f5f5 !important;
}

.order-details-container {
    max-width: 1200px;
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

.order-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #000;
}

.order-subtitle {
    color: #6b7280;
    margin-bottom: 32px;
    font-size: 16px;
}

/* Success Alert */
.success-alert {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 16px 24px;
    border-radius: 8px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.success-alert i {
    font-size: 24px;
}

/* Order Grid */
.order-grid {
    display: grid;
    grid-template-columns: 1fr 450px;
    gap: 24px;
}

/* Cards */
.order-card {
    background: white;
    padding: 28px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
}

.card-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #000;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

/* Order Info Rows */
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
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
    color: #000;
    font-size: 14px;
    text-align: right;
}

/* Status Badges */
.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
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

/* Shipping Address */
.address-block {
    background: #f9fafb;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid #d4af37;
    margin-top: 12px;
}

.address-name {
    font-weight: 700;
    font-size: 16px;
    color: #000;
    margin-bottom: 4px;
}

.address-email {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.address-text {
    color: #374151;
    font-size: 14px;
    line-height: 1.6;
}

/* Order Items */
.order-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
}

.order-item:last-of-type {
    border-bottom: none;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f3f4f6;
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
    font-size: 32px;
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-size: 16px;
    font-weight: 700;
    color: #000;
    margin-bottom: 8px;
}

.item-meta {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 4px;
}

.item-price-label {
    color: #6b7280;
    font-size: 13px;
}

.item-total {
    text-align: right;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.item-total-price {
    font-size: 18px;
    font-weight: 700;
    color: #000;
}

/* Summary */
.order-summary {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    margin-top: 24px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 15px;
}

.summary-label {
    color: #6b7280;
    font-weight: 500;
}

.summary-value {
    color: #000;
    font-weight: 600;
}

.summary-divider {
    height: 2px;
    background: #e5e7eb;
    margin: 12px 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 16px 0 0;
    font-size: 18px;
}

.total-label {
    font-weight: 700;
    color: #000;
}

.total-value {
    font-weight: 700;
    color: #ef4444;
    font-size: 24px;
}

/* Payment Instructions */
.payment-instructions {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 2px solid #d4af37;
}

.payment-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 16px;
    color: #d4af37;
    display: flex;
    align-items: center;
    gap: 8px;
}

.bank-info {
    background: rgba(255, 255, 255, 0.05);
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 16px;
}

.bank-name {
    font-size: 14px;
    color: #d4af37;
    font-weight: 600;
    margin-bottom: 8px;
}

.account-number {
    font-size: 24px;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 8px;
    font-family: 'Courier New', monospace;
}

.account-name {
    font-size: 14px;
    color: #9ca3af;
}

.payment-amount {
    background: #d4af37;
    color: #000;
    padding: 16px;
    border-radius: 8px;
    text-align: center;
}

.payment-amount-label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}

.payment-amount-value {
    font-size: 28px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
}

.payment-note {
    font-size: 13px;
    color: #d1d5db;
    margin-top: 12px;
    padding: 12px;
    background: rgba(212, 175, 55, 0.1);
    border-radius: 6px;
    border-left: 3px solid #d4af37;
}

/* Upload Payment Proof */
.upload-proof-card {
    background: white;
    padding: 28px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
    border: 2px dashed #d4af37;
}

.upload-title {
    font-size: 20px;
    font-weight: 700;
    color: #000;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.upload-description {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 20px;
}

.upload-area {
    position: relative;
    border: 3px dashed #d1d5db;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    background: #f9fafb;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.upload-area:hover {
    border-color: #d4af37;
    background: #fffbf0;
}

.upload-area.dragover {
    border-color: #d4af37;
    background: #fffbf0;
    transform: scale(1.02);
}

.upload-placeholder i {
    font-size: 48px;
    color: #d4af37;
    margin-bottom: 16px;
}

.upload-placeholder p {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.upload-hint {
    font-size: 13px;
    color: #6b7280;
}

.upload-preview {
    position: relative;
}

.upload-preview img {
    max-width: 100%;
    max-height: 400px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.remove-image {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ef4444;
    color: white;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.remove-image:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.btn-upload {
    width: 100%;
    background: linear-gradient(135deg, #000000, #333333);
    color: #d4af37;
    padding: 16px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-upload:hover:not(:disabled) {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(212, 175, 55, 0.3);
}

.btn-upload:disabled {
    background: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
}

.error-message {
    color: #ef4444;
    font-size: 13px;
    margin-top: -10px;
    margin-bottom: 10px;
}

/* Confirm Delivery Card */
.confirm-delivery-card {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 2px solid #10b981;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
}

.confirm-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.confirm-header i {
    font-size: 48px;
    color: white;
}

.confirm-title {
    font-size: 20px;
    font-weight: 700;
    color: white;
    margin-bottom: 4px;
}

.confirm-text {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
}

.btn-confirm-delivery {
    width: 100%;
    background: white;
    color: #059669;
    padding: 16px;
    border-radius: 8px;
    border: none;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-confirm-delivery:hover {
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

/* Tracking Resi Card */
.tracking-resi-card {
    background: linear-gradient(135deg, #1a1a1a, #000000);
    color: white;
    padding: 28px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 2px solid #d4af37;
    box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
}

.resi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
}

.resi-title {
    font-size: 20px;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.resi-courier {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.7);
}

.resi-status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
}

.resi-status-badge.shipped {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.resi-status-badge.delivered {
    background: linear-gradient(135deg, #10b981, #059669);
}

.resi-number-box {
    background: rgba(212, 175, 55, 0.1);
    padding: 20px;
    border-radius: 8px;
    border: 2px dashed #d4af37;
    margin-bottom: 20px;
    text-align: center;
}

.resi-label {
    font-size: 11px;
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 8px;
}

.resi-number {
    font-size: 32px;
    font-weight: 700;
    color: #d4af37;
    font-family: 'Courier New', monospace;
    letter-spacing: 3px;
    margin-bottom: 12px;
    word-break: break-all;
}

.resi-date {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.resi-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.btn-copy-resi,
.btn-track-order,
.btn-jne-track {
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 2px solid;
    text-decoration: none;
}

.btn-copy-resi {
    background: rgba(255, 255, 255, 0.1);
    border-color: #d4af37;
    color: #d4af37;
    cursor: pointer;
}

.btn-copy-resi:hover {
    background: #d4af37;
    color: #000;
    transform: translateY(-2px);
}

.btn-track-order {
    background: #d4af37;
    border-color: #d4af37;
    color: #000;
}

.btn-track-order:hover {
    background: #f0d872;
    border-color: #f0d872;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
}

.btn-jne-track {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
}

.btn-jne-track:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: white;
    transform: translateY(-2px);
}

.resi-eta {
    background: rgba(139, 92, 246, 0.2);
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #c4b5fd;
    border-left: 3px solid #8b5cf6;
}

/* Payment Proof Uploaded */
.proof-uploaded-card {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.proof-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 12px;
}

.proof-header i {
    font-size: 28px;
}

.proof-date {
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.proof-image {
    background: white;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
}

.proof-image img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.proof-image img:hover {
    transform: scale(1.02);
}

.proof-note {
    background: rgba(255, 255, 255, 0.1);
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Image Modal */
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
    transition: all 0.3s ease;
}

.modal-close:hover {
    color: #d4af37;
}

/* Responsive */
@media (max-width: 1024px) {
    .order-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .order-details-container {
        margin-top: 40px;
    }
    
    .confirm-delivery-card {
        padding: 20px;
    }
    
    .confirm-header {
        flex-direction: column;
        text-align: center;
    }
    
    .confirm-header i {
        font-size: 40px;
    }
    
    .order-title {
        font-size: 24px;
    }
    
    .order-card {
        padding: 20px;
    }
    
    .order-item {
        flex-direction: column;
    }
    
    .item-total {
        align-items: flex-start;
        margin-top: 8px;
    }
    
    .upload-area {
        padding: 30px 20px;
    }
    
    .resi-actions {
        grid-template-columns: 1fr;
    }
    
    .resi-number {
        font-size: 20px;
        letter-spacing: 1px;
    }
}
</style>

<div class="order-details-container">
    <a href="{{ route('orders.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Back to Orders
    </a>
    
    @if(session('success'))
    <div class="success-alert">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    <h1 class="order-title">Order Details</h1>
    <p class="order-subtitle">Order #{{ $order->order_number }}</p>
    
    <div class="order-grid">
        <!-- Left Column -->
        <div>
            <!-- Payment Instructions (if pending) -->
            @if($order->status === 'pending')
            <div class="payment-instructions">
                <div class="payment-title">
                    <i class="fas fa-credit-card"></i>
                    Payment Instructions
                </div>
                <p style="color: #d1d5db; margin-bottom: 20px; font-size: 14px;">
                    Please transfer the exact amount to the bank account below:
                </p>
                
                <div class="bank-info">
                    <div class="bank-name">BANK BCA</div>
                    <div class="account-number">0884584422</div>
                    <div class="account-name">Benny</div>
                </div>
                
                <div class="payment-amount">
                    <div class="payment-amount-label">Total Amount to Transfer</div>
                    <div class="payment-amount-value">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>
                
                <div class="payment-note">
                    <i class="fas fa-info-circle"></i>
                    <strong>Important:</strong> Please transfer the exact amount and upload your payment proof below.
                </div>
            </div>

            <!-- Upload Payment Proof Form -->
            <div class="upload-proof-card">
                <h3 class="upload-title">
                    <i class="fas fa-upload"></i>
                    Upload Payment Proof
                </h3>
                <p class="upload-description">
                    After making the transfer, please upload your payment proof (screenshot or photo of transfer receipt)
                </p>
                
                <form action="{{ route('orders.uploadPayment', $order) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="upload-area" id="uploadArea">
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required hidden>
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to select image or drag and drop here</p>
                            <span class="upload-hint">JPG, PNG (Max 2MB)</span>
                        </div>
                        <div class="upload-preview" id="uploadPreview" style="display: none;">
                            <img id="previewImage" src="" alt="Preview">
                            <button type="button" class="remove-image" id="removeImage">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @error('payment_proof')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                    
                    <button type="submit" class="btn-upload" id="btnUpload" disabled>
                        <i class="fas fa-paper-plane"></i>
                        Upload Payment Proof
                    </button>
                </form>
            </div>
            @endif

            <!-- Payment Proof Uploaded (if processing) -->
            @if($order->status === 'processing' && $order->payment_proof)
            <div class="proof-uploaded-card">
                <div class="proof-header">
                    <i class="fas fa-check-circle"></i>
                    <span>Payment Proof Uploaded</span>
                </div>
                <p class="proof-date">
                    <i class="fas fa-clock"></i>
                    Uploaded on {{ $order->payment_proof_uploaded_at->format('M d, Y H:i') }}
                </p>
                <div class="proof-image">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" onclick="openImageModal(this.src)">
                </div>
                <p class="proof-note">
                    <i class="fas fa-info-circle"></i>
                    Your payment is being verified. We'll update the order status once confirmed.
                </p>
            </div>
            @endif

            <!-- Confirm Delivery Button (for shipped orders) -->
            @if($order->status === 'shipped')
            <div class="confirm-delivery-card">
                <div class="confirm-header">
                    <i class="fas fa-box-check"></i>
                    <div>
                        <div class="confirm-title">Package Received?</div>
                        <div class="confirm-text">Confirm that you have received your order</div>
                    </div>
                </div>
                
                <form action="{{ route('orders.confirmDelivery', $order) }}" method="POST" onsubmit="return confirm('Confirm that you have received this order?')">
                    @csrf
                    <button type="submit" class="btn-confirm-delivery">
                        <i class="fas fa-check-circle"></i>
                        Confirm Delivery - Pesanan Diterima
                    </button>
                </form>
            </div>
            @endif

            <!-- Tracking Number (if shipped/delivered) -->
            @if(in_array($order->status, ['shipped', 'delivered']) && $order->tracking_number)
            <div class="tracking-resi-card">
                <div class="resi-header">
                    <div>
                        <div class="resi-title">
                            <i class="fas fa-shipping-fast"></i>
                            Nomor Resi Pengiriman
                        </div>
                        <div class="resi-courier">JNE - {{ $order->shipping_service ?? 'Regular' }}</div>
                    </div>
                    <div class="resi-status-badge {{ $order->status === 'delivered' ? 'delivered' : 'shipped' }}">
                        @if($order->status === 'delivered')
                            <i class="fas fa-check-circle"></i> Delivered
                        @else
                            <i class="fas fa-truck"></i> On Delivery
                        @endif
                    </div>
                </div>
                
                <div class="resi-number-box">
                    <div class="resi-label">TRACKING NUMBER</div>
                    <div class="resi-number" id="resiNumber">{{ $order->tracking_number }}</div>
                    <div class="resi-date">
                        <i class="fas fa-calendar-alt"></i>
                        Shipped: {{ ($order->shipped_at ?? now())->format('M d, Y H:i') }}
                    </div>
                </div>
                
                <div class="resi-actions">
                    <button class="btn-copy-resi" onclick="copyResiNumber()">
                        <i class="fas fa-copy"></i>
                        Copy Resi
                    </button>
                    <a href="{{ route('orders.track', $order) }}" class="btn-track-order">
                        <i class="fas fa-route"></i>
                        Track Package
                    </a>
                    <a href="https://www.jne.co.id/tracking-package" target="_blank" class="btn-jne-track">
                        <i class="fas fa-external-link-alt"></i>
                        Track on JNE
                    </a>
                </div>
                
                @if($order->shipping_etd && $order->status !== 'delivered')
                <div class="resi-eta">
                    <i class="fas fa-clock"></i>
                    <span>Estimated arrival: {{ ($order->shipped_at ?? now())->addDays((int)str_replace(' hari', '', $order->shipping_etd))->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Order Information -->
            <div class="order-card">
                <h2 class="card-title">Order Information</h2>
                <div class="info-row">
                    <span class="info-label">Order Number</span>
                    <span class="info-value">{{ $order->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                    </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                <div class="info-row">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                </div>
                @if($order->shipping_service)
                <div class="info-row">
                    <span class="info-label">Shipping Service</span>
                    <span class="info-value">{{ $order->shipping_service }}</span>
                    </div>
                @endif
                @if($order->shipping_etd)
                <div class="info-row">
                    <span class="info-label">Estimated Delivery</span>
                    <span class="info-value">{{ $order->shipping_etd }} days</span>
                </div>
                @endif
            </div>
            
            <!-- Shipping Information -->
            <div class="order-card">
                <h2 class="card-title">Shipping Information</h2>
                <div class="address-block">
                    <div class="address-name">
                        <i class="fas fa-user"></i> {{ $order->customer_name }}
                    </div>
                    <div class="address-email">
                        <i class="fas fa-envelope"></i>
                        {{ $order->customer_email }}
                    </div>
                    @if($order->customer_phone)
                    <div class="address-phone">
                        <i class="fas fa-phone"></i>
                        {{ $order->customer_phone }}
                    </div>
                    @endif
                    <div class="address-text">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $order->shipping_address }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Order Items -->
        <div>
            <div class="order-card">
                <h2 class="card-title">Order Items</h2>
                
                <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                    @foreach($order->orderItems as $item)
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
                            <div class="item-name">{{ $item->getItemName() }}</div>
                            <div class="item-meta">
                                <span><i class="fas fa-box"></i> Qty: {{ $item->quantity }}</span>
                                <span><i class="fas fa-tag"></i> @Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="item-total">
                            <div class="item-total-price">
                                Rp{{ number_format($item->total, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">Rp{{ number_format($order->orderItems->sum('total'), 0, ',', '.') }}</span>
                        </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping Cost</span>
                        <span class="summary-value">
                            @if($order->shipping_cost > 0)
                                Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @else
                                Free
                            @endif
                        </span>
                        </div>
                    <div class="summary-divider"></div>
                    <div class="summary-total">
                        <span class="total-label">Total</span>
                        <span class="total-value">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
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
// Upload Area Interactions
const uploadArea = document.getElementById('uploadArea');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const uploadPreview = document.getElementById('uploadPreview');
const fileInput = document.getElementById('payment_proof');
const previewImage = document.getElementById('previewImage');
const removeImageBtn = document.getElementById('removeImage');
const btnUpload = document.getElementById('btnUpload');

if (uploadArea) {
    // Click to select file
    uploadArea.addEventListener('click', function(e) {
        if (e.target.id !== 'removeImage' && !e.target.closest('.remove-image')) {
            fileInput.click();
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            // Set file to input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            handleFile(file);
        } else {
            alert('Please upload an image file (JPG, PNG)');
        }
    });

    // Remove image
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.value = '';
            uploadPlaceholder.style.display = 'block';
            uploadPreview.style.display = 'none';
            btnUpload.disabled = true;
        });
    }
}

function handleFile(file) {
    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        fileInput.value = '';
        return;
    }

    // Validate file type
    if (!file.type.match('image.*')) {
        alert('Please upload an image file (JPG, PNG)');
        fileInput.value = '';
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        uploadPlaceholder.style.display = 'none';
        uploadPreview.style.display = 'block';
        btnUpload.disabled = false;
    };
    reader.readAsDataURL(file);
}

// Image Modal
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

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Copy Resi Number
function copyResiNumber() {
    const resiNumber = document.getElementById('resiNumber').innerText;
    navigator.clipboard.writeText(resiNumber).then(function() {
        // Show success message
        const btn = event.target.closest('.btn-copy-resi');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.style.background = '#10b981';
        btn.style.borderColor = '#10b981';
        btn.style.color = 'white';
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.style.background = 'rgba(255, 255, 255, 0.1)';
            btn.style.borderColor = '#d4af37';
            btn.style.color = '#d4af37';
        }, 2000);
    }).catch(function(err) {
        alert('Failed to copy: ' + err);
    });
}
</script>
@endsection