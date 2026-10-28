@extends('layouts.app')

@section('title', 'Checkout - Bennylovescoffee')

@section('content')
<style>
body {
    background-color: #f5f5f5 !important;
}

.checkout-container {
    max-width: 1200px;
    margin: 40px auto 40px;
    padding: 0 20px;
}

.checkout-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #000;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 450px;
    gap: 24px;
}

/* Shipping Form */
.shipping-form {
    background: white;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #000;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #000;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #d4af37;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.error-message {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

/* Order Summary */
.order-summary {
    background: white;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 100px;
    height: fit-content;
}

.summary-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #000;
}

.summary-items {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.summary-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f3f4f6;
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
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-size: 14px;
    font-weight: 600;
    color: #000;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.item-qty {
    font-size: 12px;
    color: #6b7280;
}

.item-price {
    text-align: right;
    font-size: 14px;
    font-weight: 700;
    color: #000;
    white-space: nowrap;
}

/* Summary Totals */
.summary-totals {
    padding: 16px 0;
    border-top: 2px solid #e5e7eb;
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.summary-label {
    color: #6b7280;
    font-weight: 500;
}

.summary-value {
    color: #000;
    font-weight: 600;
}

.shipping-loading {
    color: #d4af37;
    font-style: italic;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
    margin-top: 12px;
}

.total-label {
    font-size: 18px;
    font-weight: 700;
    color: #000;
}

.total-value {
    font-size: 20px;
    font-weight: 700;
    color: #ef4444;
}

/* Place Order Button */
.place-order-btn {
    width: 100%;
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
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

.place-order-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(212, 175, 55, 0.3);
}

.place-order-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Empty Cart */
.empty-checkout {
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

@media (max-width: 1024px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    
    .order-summary {
        position: static;
    }
}

@media (max-width: 768px) {
    .checkout-container {
        margin-top: 40px;
        padding: 0 16px;
        padding-bottom: 180px; /* Space for sticky summary + bottom nav */
    }
    
    .checkout-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .checkout-title {
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    .shipping-form {
        padding: 16px;
    }
    
    .form-title {
        font-size: 18px;
        margin-bottom: 16px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-label {
        font-size: 14px;
        margin-bottom: 6px;
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        padding: 14px;
        font-size: 16px; /* Larger font for mobile */
        border-radius: 8px;
        width: 100%;
        min-height: 48px; /* Minimum touch target */
    }
    
    .form-select {
        font-size: 16px !important; /* Prevent zoom on iOS */
        padding: 14px 12px;
        background-color: white;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    
    /* Style dropdown options for mobile */
    .form-select option {
        font-size: 16px !important;
        padding: 12px 8px !important;
        line-height: 1.5 !important;
        min-height: 48px !important;
        background-color: white !important;
        color: #000 !important;
    }
    
    /* Custom dropdown styling for better mobile experience */
    .form-select:focus {
        border-color: #d4af37;
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    /* Ensure dropdown list is full width and readable */
    .form-select option:hover,
    .form-select option:focus,
    .form-select option:checked {
        background-color: #f3f4f6 !important;
        color: #000 !important;
        font-weight: 500 !important;
    }
    
    /* Additional mobile dropdown improvements */
    .form-select {
        font-size: 16px !important;
        line-height: 1.5 !important;
        height: auto !important;
        min-height: 48px !important;
    }
    
    /* Make dropdown options more touch-friendly */
    .form-select option {
        font-size: 18px !important;
        padding: 20px 16px !important;
        line-height: 1.8 !important;
        min-height: 60px !important;
        background-color: white !important;
        color: #000 !important;
        border-bottom: 1px solid #f3f4f6 !important;
        font-weight: 500 !important;
    }
    
    /* Selected option styling */
    .form-select option:checked {
        background-color: #d4af37 !important;
        color: white !important;
        font-weight: 600 !important;
    }
    
    /* Override any global select styling */
    select.form-select {
        width: 100% !important;
        font-size: 18px !important;
        padding: 16px !important;
        min-height: 60px !important;
        line-height: 1.6 !important;
    }
    
    /* Force larger dropdown on mobile */
    @media (max-width: 768px) {
        select, 
        select.form-select,
        select#province,
        select#city,
        select#shipping_service {
            font-size: 18px !important;
            padding: 16px !important;
            min-height: 60px !important;
            line-height: 1.6 !important;
        }
        
        select option,
        select.form-select option,
        select#province option,
        select#city option,
        select#shipping_service option {
            font-size: 18px !important;
            padding: 20px 16px !important;
            min-height: 60px !important;
            line-height: 1.8 !important;
        }
    }
    
    .form-textarea {
        min-height: 100px;
        font-size: 16px;
    }
    
    /* Order Summary Mobile - sticky above the global bottom nav */
    .order-summary {
        position: sticky;
        bottom: 70px;
        z-index: 999;
        margin-top: 20px;
        border-radius: 12px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .place-order-btn {
        padding: 18px;
        font-size: 16px;
        border-radius: 12px;
    }
}
</style>

<div class="checkout-container">
    <h1 class="checkout-title">Checkout</h1>
    
    @if($cartItems->count() > 0)
    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
        <input type="hidden" name="shipping_service" id="shipping_service_input">
        <input type="hidden" name="shipping_etd" id="shipping_etd_input">
        
        <div class="checkout-grid">
            <!-- Shipping Information -->
            <div class="shipping-form">
                <h2 class="form-title">Shipping Information</h2>
                
                <div class="form-group">
                    <label for="customer_name" class="form-label">Full Name *</label>
                    <input type="text" name="customer_name" id="customer_name" 
                           value="{{ old('customer_name', auth()->user()->name) }}" 
                           class="form-input" required>
                    @error('customer_name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="customer_email" class="form-label">Email *</label>
                    <input type="email" name="customer_email" id="customer_email" 
                           value="{{ old('customer_email', auth()->user()->email) }}" 
                           class="form-input" required>
                    @error('customer_email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="customer_phone" class="form-label">Phone Number *</label>
                    <input type="tel" name="customer_phone" id="customer_phone" 
                           value="{{ old('customer_phone', auth()->user()->phone) }}" 
                           class="form-input" required placeholder="e.g. 081234567890">
                    @error('customer_phone')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="location-search" class="form-label">Search Location (Kecamatan / City / Postal Code) *</label>
                    <div style="position: relative;">
                        <input type="text" id="location-search" class="form-input" placeholder="Type district, city or postal code..." autocomplete="off">
                        <div id="area-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 1000; display: none;"></div>
                    </div>
                    <input type="hidden" name="destination_area_id" id="destination_area_id" required>
                    <input type="hidden" name="area_name" id="area_name" required>
                </div>
                
                <div class="form-group">
                    <label for="shipping_address" class="form-label">Complete Address *</label>
                    <textarea name="shipping_address" id="shipping_address" 
                              class="form-textarea" 
                              placeholder="Street address, building number, etc." required>{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="postal_code" class="form-label">Postal Code *</label>
                    <input type="text" name="postal_code" id="postal_code" 
                           value="{{ old('postal_code') }}" 
                           class="form-input" 
                           placeholder="e.g., 60293" required>
                    @error('postal_code')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="shipping_service" class="form-label">Shipping Service *</label>
                    <select name="shipping_service_select" id="shipping_service" class="form-select" required disabled>
                        <option value="">Select City First</option>
                    </select>
                    @error('shipping_service')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="summary-title">Order Summary</h2>
                
                <div class="summary-items">
                    @foreach($cartItems as $cartItem)
                    <div class="summary-item">
                        <div class="item-image">
                            @if($cartItem->product->main_photo)
                                <img src="{{ asset('storage/' . $cartItem->product->main_photo) }}" 
                                     alt="{{ $cartItem->product->name }}">
                            @elseif($cartItem->product->foto)
                                <img src="{{ asset('storage/' . $cartItem->product->foto) }}" 
                                     alt="{{ $cartItem->product->name }}">
                            @elseif($cartItem->product->image)
                                <img src="{{ asset('storage/' . $cartItem->product->image) }}" 
                                     alt="{{ $cartItem->product->name }}">
                            @else
                                <div class="item-placeholder">
                                    <i class="fas fa-coffee"></i>
                                </div>
                            @endif
                        </div>
                        <div class="item-details">
                            <div class="item-name">{{ $cartItem->product->name }}</div>
                            <div class="item-qty">Qty: {{ $cartItem->quantity }}</div>
                        </div>
                        <div class="item-price">
                            Rp{{ number_format($cartItem->getTotalPrice(), 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="summary-totals">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping:</span>
                        <span class="summary-value shipping-value">
                            <span id="shipping-cost-display">Select shipping</span>
                        </span>
                    </div>
                    <div class="summary-total">
                        <span class="total-label">Total:</span>
                        <span class="total-value" id="total-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <button type="submit" class="place-order-btn" id="placeOrderBtn" disabled>
                    <i class="fas fa-check-circle"></i>
                    Place Order
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="empty-checkout">
        <i class="fas fa-shopping-cart empty-icon"></i>
        <h2 class="empty-title">Your cart is empty</h2>
        <p class="empty-text">Add some products to checkout!</p>
        <a href="{{ route('products.index') }}" class="shop-btn">
            <i class="fas fa-shopping-bag"></i>
            Continue Shopping
        </a>
    </div>
    @endif
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const subtotal = Number('{{ $subtotal }}');
const totalWeight = Number('{{ $totalWeight }}');
const searchAreaUrl = "{{ route('biteship.search_area') }}";
const ratesUrl = "{{ route('biteship.rates') }}";

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search Location
const searchInput = document.getElementById('location-search');
const resultsDiv = document.getElementById('area-results');
const destinationIdInput = document.getElementById('destination_area_id');
const areaNameInput = document.getElementById('area_name');
const postalCodeInput = document.getElementById('postal_code');

searchInput?.addEventListener('input', debounce(function(e) {
    const query = e.target.value;
    
    if (query.length < 3) {
        resultsDiv.style.display = 'none';
        return;
    }

    fetch(`${searchAreaUrl}?q=${encodeURIComponent(query)}`, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.areas.length > 0) {
            resultsDiv.innerHTML = '';
            data.areas.forEach(area => {
                const div = document.createElement('div');
                div.style.padding = '10px';
                div.style.cursor = 'pointer';
                div.style.borderBottom = '1px solid #eee';
                div.onmouseover = function() { this.style.backgroundColor = '#f9fafb'; };
                div.onmouseout = function() { this.style.backgroundColor = 'white'; };
                
                // Construct display name
                // Use the formatted name from API or construct it manually using correct keys
                const displayName = area.name;
                div.textContent = displayName;
                
                div.onclick = function() {
                    selectArea(area, displayName);
                };
                
                resultsDiv.appendChild(div);
            });
            resultsDiv.style.display = 'block';
        } else {
            resultsDiv.innerHTML = '<div style="padding: 10px; color: #666;">No locations found</div>';
            resultsDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error searching area:', error);
    });
}, 500));

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (e.target !== searchInput && e.target !== resultsDiv) {
        resultsDiv.style.display = 'none';
    }
});

function selectArea(area, displayName) {
    // Set values
    searchInput.value = displayName;
    destinationIdInput.value = area.id;
    areaNameInput.value = displayName;
    postalCodeInput.value = area.postal_code;
    
    // Hide results
    resultsDiv.style.display = 'none';
    
    // Enable shipping service select
    const shippingSelect = document.getElementById('shipping_service');
    shippingSelect.disabled = false;
    shippingSelect.innerHTML = '<option value="">Loading services...</option>';
    
    // Fetch rates
    fetchRates(area.id);
}

function fetchRates(destinationAreaId) {
    const shippingSelect = document.getElementById('shipping_service');
    
    fetch(ratesUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            destination_area_id: destinationAreaId,
            items: [
                // We'll send a dummy item to get rates based on weight
                // In a real app, you might want to send actual cart items
                {
                    name: 'Cart Items',
                    value: subtotal,
                    quantity: 1,
                    weight: totalWeight
                }
            ]
        })
    })
    .then(response => response.json())
    .then(data => {
        shippingSelect.innerHTML = '<option value="">Select Shipping Service</option>';
        
        if (data.success && data.pricing && data.pricing.length > 0) {
            // Sort by price (cheapest first)
            data.pricing.sort((a, b) => a.price - b.price);
            
            data.pricing.forEach(service => {
                const option = document.createElement('option');
                // Create a unique value for the option
                const serviceValue = JSON.stringify({
                    courier: service.courier_name,
                    service: service.courier_service_name,
                    cost: service.price,
                    etd: service.duration,
                    description: service.description
                });
                
                option.value = serviceValue;
                option.textContent = `${service.courier_name} ${service.courier_service_name} - Rp${new Intl.NumberFormat('id-ID').format(service.price)} (${service.duration})`;
                
                shippingSelect.appendChild(option);
            });
        } else {
            shippingSelect.innerHTML = '<option value="">No shipping services available</option>';
        }
    })
    .catch(error => {
        console.error('Error fetching rates:', error);
        shippingSelect.innerHTML = '<option value="">Error loading services</option>';
    });
}

// Update total when shipping service selected
document.getElementById('shipping_service')?.addEventListener('change', function() {
    if (this.value) {
        const serviceData = JSON.parse(this.value);
        const shippingCost = serviceData.cost;
        
        // Update hidden inputs
        document.getElementById('shipping_cost_input').value = shippingCost;
        document.getElementById('shipping_service_input').value = serviceData.service;
        document.getElementById('shipping_etd_input').value = serviceData.etd;
        
        // Update display
        document.getElementById('shipping-cost-display').textContent = `Rp${shippingCost.toLocaleString('id-ID')} (${serviceData.etd})`;
        
        // Update total
        const total = subtotal + shippingCost;
        document.getElementById('total-display').textContent = `Rp${total.toLocaleString('id-ID')}`;
        
        // Enable place order button
        document.getElementById('placeOrderBtn').disabled = false;
    } else {
        resetShipping();
    }
});

function resetShipping() {
    document.getElementById('shipping_cost_input').value = '0';
    document.getElementById('shipping_service_input').value = '';
    document.getElementById('shipping_etd_input').value = '';
    document.getElementById('shipping-cost-display').textContent = 'Select shipping';
    document.getElementById('total-display').textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('placeOrderBtn').disabled = true;
}
</script>
@endsection