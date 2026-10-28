@extends('layouts.app')

@section('title', 'Shopping Cart - Bennylovescoffee')

@section('content')
<style>
body {
    background-color: #f5f5f5 !important;
}

.cart-container {
    max-width: 1200px;
    margin: 120px auto 40px;
    padding: 0 20px;
}

.cart-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #000;
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 24px;
}

/* Cart Header */
.cart-header {
    background: white;
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.select-all {
    display: flex;
    align-items: center;
    gap: 8px;
}

.select-all input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #d4af37;
}

.select-all label {
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    user-select: none;
}

.delete-selected-btn {
    color: #ef4444;
    font-size: 14px;
    font-weight: 500;
    border: none;
    background: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: none;
}

.delete-selected-btn.show {
    display: block;
}

.delete-selected-btn:hover {
    color: #dc2626;
}

/* Seller Group */
.seller-group {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.seller-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.seller-header input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #d4af37;
}

.seller-name {
    font-size: 14px;
    font-weight: 600;
    color: #000;
}

.verified-badge {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
}

/* Cart Item */
.cart-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-checkbox {
    margin-top: 4px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #d4af37;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.discount-badge {
    position: absolute;
    top: 4px;
    left: 4px;
    background: linear-gradient(135deg, #ef4444, #ff6b6b);
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.item-info {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-size: 14px;
    color: #000;
    margin-bottom: 8px;
    line-height: 1.4;
    font-weight: 500;
}

.item-price {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.original-price {
    text-decoration: line-through;
    color: #9ca3af;
    font-size: 13px;
}

.current-price {
    color: #ef4444;
    font-size: 16px;
    font-weight: 700;
}

.item-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: #6b7280;
}

.stars {
    color: #fbbf24;
}

.item-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-btn {
    width: 28px;
    height: 28px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    color: #374151;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    border-color: #d4af37;
    color: #000;
}

.qty-input {
    width: 40px;
    text-align: center;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 4px;
    font-size: 14px;
    font-weight: 500;
}

.item-total {
    text-align: right;
}

.total-label {
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 2px;
}

.total-price {
    font-size: 14px;
    font-weight: 700;
    color: #000;
}

.item-buttons {
    display: flex;
    gap: 8px;
}

.icon-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: none;
    cursor: pointer;
    color: #9ca3af;
    font-size: 14px;
    transition: all 0.3s ease;
}

.icon-btn:hover {
    color: #ef4444;
}

/* Desktop Order Summary */
.desktop-order-summary {
    display: block;
}

.mobile-order-summary {
    display: none;
}

/* Ensure mobile order summary is hidden on desktop */
@media (min-width: 769px) {
    .mobile-order-summary {
        display: none !important;
    }
    
    .desktop-order-summary {
        display: block !important;
    }
}

/* Order Summary */
.order-summary {
    background: white;
    padding: 20px;
    border-radius: 8px;
    height: fit-content;
    position: sticky;
    top: 100px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.summary-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #000;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
}

.summary-label {
    font-size: 14px;
    color: #000;
    font-weight: 500;
}

.summary-value {
    font-size: 18px;
    font-weight: 700;
    color: #000;
}

.checkout-btn {
    width: 100%;
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
    padding: 14px;
    border-radius: 8px;
    border: none;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.checkout-btn:hover {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 8px;
}

.empty-icon {
    font-size: 80px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 24px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 12px;
}

.empty-text {
    color: #6b7280;
    margin-bottom: 24px;
}

.shop-btn {
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
    padding: 12px 32px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.shop-btn:hover {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
}

@media (max-width: 768px) {
    .cart-layout {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .cart-container {
        margin-top: 80px;
        padding: 0 16px;
        padding-bottom: 200px; /* More space for fixed summary */
    }
    
    /* Keep bottom navigation at original position */
    .mobile-bottom-bar {
        bottom: 0px !important; /* Keep bottom nav at bottom */
    }
    
    .cart-title {
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    /* Mobile Cart Header */
    .cart-header {
        padding: 12px 16px;
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .select-all {
        width: 100%;
        justify-content: space-between;
    }
    
    .delete-selected-btn {
        width: 100%;
        text-align: center;
        padding: 8px;
        background: #fef2f2;
        border-radius: 6px;
        border: 1px solid #fecaca;
    }
    
    /* Mobile Seller Group */
    .seller-group {
        padding: 16px;
        margin-bottom: 12px;
    }
    
    .seller-header {
        margin-bottom: 12px;
        padding-bottom: 8px;
    }
    
    /* Mobile Cart Item - Compact Layout */
    .cart-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
        position: relative;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .item-checkbox {
        margin-top: 4px;
        width: 22px;
        height: 22px;
        cursor: pointer;
        accent-color: #d4af37;
        transform: scale(1.8);
        transform-origin: left center;
    }
    
    .seller-header input[type="checkbox"],
    .select-all input[type="checkbox"] {
        width: 22px;
        height: 22px;
        transform: scale(1.8);
        transform-origin: left center;
        margin-right: 12px;
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-info {
        flex: 1;
        min-width: 0;
    }
    
    .item-title {
        font-size: 14px;
        color: #000;
        margin-bottom: 6px;
        line-height: 1.4;
        font-weight: 500;
    }
    
    .item-price {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    
    .current-price {
        color: #ef4444;
        font-size: 14px;
        font-weight: 700;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #9ca3af;
        font-size: 12px;
    }
    
    .item-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: #6b7280;
    }
    
    .item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .qty-btn {
        width: 44px;
        height: 44px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        color: #374151;
        transition: all 0.3s ease;
    }
    
    .qty-btn:hover {
        background: linear-gradient(135deg, #d4af37, #f0d872);
        border-color: #d4af37;
        color: #000;
    }
    
    .qty-input {
        width: 44px;
        height: 44px;
        text-align: center;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 2px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .item-total {
        text-align: right;
    }
    
    .total-label {
        font-size: 10px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    
    .total-price {
        font-size: 12px;
        font-weight: 700;
        color: #000;
    }
    
    .item-buttons {
        display: flex;
        gap: 6px;
    }
    
    .icon-btn {
        width: 44px;
        height: 44px;
        border: none;
        background: #fef2f2;
        border-radius: 8px;
        cursor: pointer;
        color: #ef4444;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .icon-btn:hover {
        color: #ef4444;
    }
    
    /* Mobile Order Summary - Show mobile, hide desktop */
    .desktop-order-summary {
        display: none !important;
    }
    
    .mobile-order-summary {
        display: block !important;
    }
    
    /* Mobile Order Summary - Sticky above bottom nav */
    .mobile-order-summary .order-summary {
        position: sticky !important;
        bottom: 80px !important; /* Above bottom navigation */
        left: 0 !important;
        right: 0 !important;
        padding: 16px !important;
        border-radius: 16px 16px 0 0 !important; /* Rounded top corners */
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1) !important;
        z-index: 1001 !important; /* Above bottom nav */
        margin: 0 !important;
        background: white !important;
        border-top: 1px solid #e5e7eb !important;
        max-height: 200px !important; /* Prevent it from being too tall */
        width: 100% !important; /* Full width */
    }
    
    .summary-title {
        font-size: 16px;
        margin-bottom: 12px;
    }
    
    .summary-row {
        margin-bottom: 8px;
    }
    
    .summary-label {
        font-size: 14px;
    }
    
    .summary-value {
        font-size: 16px;
    }
    
    .checkout-btn {
        padding: 12px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 8px;
    }
    
    /* Mobile Empty Cart */
    .empty-cart {
        padding: 60px 20px;
        margin-bottom: 160px; /* Space for fixed summary + bottom nav */
    }
    
    .empty-icon {
        font-size: 60px;
        margin-bottom: 16px;
    }
    
    .empty-title {
        font-size: 20px;
        margin-bottom: 8px;
    }
    
    .empty-text {
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .shop-btn {
        padding: 14px 28px;
        font-size: 16px;
    }
}

/* Very small screens (e.g. 360px) - keep cart rows from getting cramped */
@media (max-width: 400px) {
    .cart-container {
        padding: 0 10px;
        padding-bottom: 210px;
    }

    .seller-group {
        padding: 12px;
    }

    .cart-item {
        gap: 8px;
        flex-wrap: wrap;
    }

    .item-image {
        width: 52px;
        height: 52px;
    }

    .item-title {
        font-size: 13px;
    }

    .item-actions {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .quantity-control {
        gap: 4px;
    }

    .qty-btn {
        width: 40px;
        height: 40px;
    }

    .qty-input {
        width: 40px;
        height: 40px;
    }

    .item-buttons .icon-btn {
        width: 40px;
        height: 40px;
    }
}
</style>

<div class="cart-container">
    <h1 class="cart-title">Keranjang</h1>
    
    @if($cartItems->count() > 0)
    <div class="cart-layout">
        <!-- Cart Items -->
        <div>
            <!-- Cart Header -->
            <div class="cart-header">
                <div class="select-all">
                    <input type="checkbox" id="select-all">
                    <label for="select-all">Pilih Semua ({{ $cartItems->count() }})</label>
                </div>
                <button class="delete-selected-btn" id="delete-selected">Hapus</button>
            </div>
            
            <!-- Seller Groups -->
            @php
                $groupedItems = $cartItems->groupBy('product.category_id');
            @endphp
            
            @foreach($groupedItems as $categoryId => $items)
            <div class="seller-group">
                <div class="seller-header">
                    <input type="checkbox" class="seller-checkbox">
                    <span class="seller-name">Bennylovescoffee</span>
                    <span class="verified-badge">
                        <i class="fas fa-check-circle"></i>
                        Official
                    </span>
                </div>
                
                @foreach($items as $cartItem)
                <div class="cart-item">
                    <input type="checkbox" class="item-checkbox" data-cart-id="{{ $cartItem->id }}">
                    
                    <div class="item-image">
                        @if($cartItem->product->main_photo)
                            <img src="{{ asset('storage/' . $cartItem->product->main_photo) }}" 
                                 alt="{{ $cartItem->product->name }}">
                        @else
                            <div style="width: 100%; height: 100%; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-coffee" style="font-size: 24px; color: #9ca3af;"></i>
                            </div>
                        @endif
                        
                        @if($cartItem->product->discount_percentage > 0)
                        <div class="discount-badge">{{ $cartItem->product->discount_percentage }}%</div>
                        @endif
                    </div>
                    
                    <div class="item-info">
                        <div class="item-title">{{ $cartItem->getItemName() }}</div>
                        
                        <div class="item-price">
                            @if($cartItem->variant_id)
                                <span class="current-price">Rp{{ number_format($cartItem->variant->price, 0, ',', '.') }}</span>
                            @else
                                @if($cartItem->product->discount_percentage > 0)
                                    <span class="original-price">Rp{{ number_format($cartItem->product->price, 0, ',', '.') }}</span>
                                    <span class="current-price">Rp{{ number_format($cartItem->product->getDiscountedPrice(), 0, ',', '.') }}</span>
                                @else
                                    <span class="current-price">Rp{{ number_format($cartItem->product->price, 0, ',', '.') }}</span>
                                @endif
                            @endif
                        </div>
                        
                        <div class="item-rating">
                            <span class="stars">★★★★★</span>
                            <span>{{ $cartItem->product->sold_count }} terjual</span>
                        </div>
                    </div>
                    
                    <div class="item-actions">
                        <div class="quantity-control">
                            <button type="button" class="qty-btn" onclick="decreaseQuantity({{ $cartItem->id }})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity-{{ $cartItem->id }}" value="{{ $cartItem->quantity }}" 
                                   min="1" max="{{ $cartItem->variant_id ? $cartItem->variant->quantity : $cartItem->product->stock }}" class="qty-input" readonly>
                            <button type="button" class="qty-btn" onclick="increaseQuantity({{ $cartItem->id }})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <div class="item-total">
                            <div class="total-label">Total</div>
                            <div class="total-price">Rp{{ number_format($cartItem->getTotalPrice(), 0, ',', '.') }}</div>
                        </div>
                        
                        <div class="item-buttons">
                            <form action="{{ route('cart.remove', $cartItem) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        
        <!-- Order Summary - Desktop Only -->
        <div class="desktop-order-summary">
            <div class="order-summary">
                <h2 class="summary-title">Ringkasan belanja</h2>
                
                <div class="summary-row">
                    <span class="summary-label">Total</span>
                    <span class="summary-value">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <a href="{{ route('orders.checkout') }}" class="checkout-btn">
                    <i class="fas fa-shopping-bag"></i>
                    Beli ({{ $cartItems->count() }})
                </a>
            </div>
        </div>
    </div>
    
    <!-- Mobile Order Summary - Fixed at bottom -->
    <div class="mobile-order-summary">
        <div class="order-summary">
            <h2 class="summary-title">Ringkasan belanja</h2>
            
            <div class="summary-row">
                <span class="summary-label">Total</span>
                <span class="summary-value">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            <a href="{{ route('orders.checkout') }}" class="checkout-btn">
                <i class="fas fa-shopping-bag"></i>
                Beli ({{ $cartItems->count() }})
            </a>
        </div>
    </div>
    @else
    <div class="empty-cart">
        <i class="fas fa-shopping-cart empty-icon"></i>
        <h2 class="empty-title">Keranjang Anda Kosong</h2>
        <p class="empty-text">Tambahkan beberapa produk untuk memulai belanja!</p>
        <a href="{{ route('products.index') }}" class="shop-btn">
            <i class="fas fa-shopping-bag"></i>
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

<script>
// CSRF Token untuk AJAX
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Select All Functionality
const selectAllCheckbox = document.getElementById('select-all');
const itemCheckboxes = document.querySelectorAll('.item-checkbox');
const sellerCheckboxes = document.querySelectorAll('.seller-checkbox');
const deleteSelectedBtn = document.getElementById('delete-selected');

// Select All Handler
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        
        // Check/uncheck all item checkboxes
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        // Check/uncheck all seller checkboxes
        sellerCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        updateDeleteButtonVisibility();
    });
}

// Seller Checkbox Handler
sellerCheckboxes.forEach(sellerCheckbox => {
    sellerCheckbox.addEventListener('change', function() {
        const sellerGroup = this.closest('.seller-group');
        const groupItemCheckboxes = sellerGroup.querySelectorAll('.item-checkbox');
        const isChecked = this.checked;
        
        groupItemCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        updateSelectAllState();
        updateDeleteButtonVisibility();
    });
});

// Item Checkbox Handler
itemCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const sellerGroup = this.closest('.seller-group');
        const sellerCheckbox = sellerGroup.querySelector('.seller-checkbox');
        const groupItemCheckboxes = sellerGroup.querySelectorAll('.item-checkbox');
        
        // Check if all items in this seller group are checked
        const allChecked = Array.from(groupItemCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(groupItemCheckboxes).some(cb => cb.checked);
        
        sellerCheckbox.checked = allChecked;
        sellerCheckbox.indeterminate = someChecked && !allChecked;
        
        updateSelectAllState();
        updateDeleteButtonVisibility();
    });
});

// Update Select All State
function updateSelectAllState() {
    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
    const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
    
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }
}

// Update Delete Button Visibility
function updateDeleteButtonVisibility() {
    const checkedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
    
    if (checkedCount > 0) {
        deleteSelectedBtn.classList.add('show');
        deleteSelectedBtn.textContent = `Hapus (${checkedCount})`;
    } else {
        deleteSelectedBtn.classList.remove('show');
    }
}

// Delete Selected Items
if (deleteSelectedBtn) {
    deleteSelectedBtn.addEventListener('click', function() {
        const selectedItems = Array.from(itemCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.getAttribute('data-cart-id'));
        
        if (selectedItems.length === 0) {
            alert('Pilih item yang ingin dihapus!');
            return;
        }
        
        if (confirm(`Yakin ingin menghapus ${selectedItems.length} item dari keranjang?`)) {
            // Delete each selected item
            selectedItems.forEach(cartId => {
                fetch(`/cart/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Item deleted:', data);
                })
                .catch(error => {
                    console.error('Error deleting item:', error);
                });
            });
            
            // Reload page after all deletions
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    });
}

// Auto-update quantity ke database
function updateQuantityInDB(cartItemId, newQuantity) {
    fetch(`/cart/${cartItemId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Quantity updated:', data);
        // Reload page untuk update total
        location.reload();
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        alert('Gagal mengupdate quantity!');
    });
}

function increaseQuantity(cartItemId) {
    const input = document.getElementById('quantity-' + cartItemId);
    const max = parseInt(input.getAttribute('max'));
    let value = parseInt(input.value);
    
    if (value < max) {
        const newValue = value + 1;
        input.value = newValue;
        
        // Auto-update ke database
        updateQuantityInDB(cartItemId, newValue);
    }
}

function decreaseQuantity(cartItemId) {
    const input = document.getElementById('quantity-' + cartItemId);
    let value = parseInt(input.value);
    
    if (value > 1) {
        const newValue = value - 1;
        input.value = newValue;
        
        // Auto-update ke database
        updateQuantityInDB(cartItemId, newValue);
    }
}

// Konfirmasi delete
document.querySelectorAll('form[action*="/cart/"]').forEach(form => {
    if (form.querySelector('button[type="submit"]')) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menghapus item ini dari keranjang?')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endsection