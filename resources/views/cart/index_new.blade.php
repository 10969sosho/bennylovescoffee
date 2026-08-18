@extends('layouts.app')

@section('title', 'Shopping Cart - Bennylovescoffee')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Keranjang - NEW LAYOUT TEST - {{ time() }}</h1>
    
    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <!-- Cart Header -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label for="select-all" class="text-sm font-medium text-gray-700">Pilih Semua ({{ $cartItems->count() }})</label>
                    </div>
                    <button type="button" class="text-green-600 text-sm font-medium hover:text-green-700">
                        Hapus
                    </button>
                </div>
            </div>
            
            <!-- Cart Items List -->
            <div class="space-y-4">
                @foreach($cartItems as $cartItem)
                <div class="cart-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <!-- Checkbox -->
                        <div class="flex-shrink-0">
                            <input type="checkbox" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </div>
                        
                        <!-- Product Image -->
                        <div class="product-image flex-shrink-0">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                                @if($cartItem->product->main_photo)
                                    <img src="{{ asset('storage/' . $cartItem->product->main_photo) }}" 
                                         alt="{{ $cartItem->product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-coffee text-gray-400 text-xl"></i>
                                @endif
                                
                                @if($cartItem->product->discount_percentage > 0)
                                <div class="absolute top-0 left-0 bg-red-500 text-white text-xs px-1 py-0.5 rounded-br">
                                    {{ $cartItem->product->discount_percentage }}%
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <!-- Seller Info -->
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-gray-600">Bennylovescoffee</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i>
                                    Official
                                </span>
                            </div>
                            
                            <!-- Product Name -->
                            <h3 class="text-sm font-medium mb-1 text-gray-900 line-clamp-2 leading-tight">{{ $cartItem->getItemName() }}</h3>
                            
                            <!-- Price -->
                            <div class="mb-1">
                                @if($cartItem->variant_id)
                                    <div class="text-sm font-bold text-red-600">Rp {{ number_format($cartItem->variant->price, 0, ',', '.') }}</div>
                                @else
                                    @if($cartItem->product->discount_percentage > 0)
                                        <div class="text-xs text-gray-500 line-through">Rp {{ number_format($cartItem->product->price, 0, ',', '.') }}</div>
                                        <div class="text-sm font-bold text-red-600">Rp {{ number_format($cartItem->product->getDiscountedPrice(), 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-sm font-bold text-red-600">Rp {{ number_format($cartItem->product->price, 0, ',', '.') }}</div>
                                    @endif
                                @endif
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center gap-1">
                                <div class="stars text-yellow-400 text-xs">★★★★★</div>
                                <span class="text-gray-600 text-xs">{{ $cartItem->product->sold_count }} terjual</span>
                            </div>
                        </div>
                        
                        <!-- Quantity and Actions -->
                        <div class="flex items-center gap-4">
                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="decreaseQuantity({{ $cartItem->id }})" class="w-6 h-6 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 transition">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity-{{ $cartItem->id }}" value="{{ $cartItem->quantity }}" min="1" max="{{ $cartItem->variant_id ? $cartItem->variant->quantity : $cartItem->product->stock }}" 
                                       class="w-8 text-center border border-gray-300 rounded px-1 py-1 text-xs" readonly>
                                <button type="button" onclick="increaseQuantity({{ $cartItem->id }})" class="w-6 h-6 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 transition">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            
                            <!-- Total Price -->
                            <div class="text-right min-w-0">
                                <div class="text-xs text-gray-600">Total</div>
                                <div class="font-bold text-sm">Rp {{ number_format($cartItem->getTotalPrice(), 0, ',', '.') }}</div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-1">
                                <button type="button" class="text-gray-400 hover:text-red-500 p-1 transition">
                                    <i class="fas fa-heart text-sm"></i>
                                </button>
                                <form action="{{ route('cart.remove', $cartItem) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 p-1 transition">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-4">
                <h2 class="text-xl font-semibold mb-4">Ringkasan belanja</h2>
                
                <!-- Total -->
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-medium">Total</span>
                    <span class="text-xl font-bold text-red-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <!-- Promo Section -->
                <div class="mb-4">
                    <button class="w-full bg-green-50 border border-green-200 text-green-700 py-3 px-4 rounded-lg flex items-center justify-between hover:bg-green-100 transition">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tag text-yellow-500"></i>
                            <span class="text-sm font-medium">Makin hemat pakai promo</span>
                        </div>
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </div>
                
                <!-- Checkout Button -->
                <a href="{{ route('orders.checkout') }}" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg text-center font-medium hover:bg-green-700 transition block">
                    Beli ({{ $cartItems->count() }})
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-12">
        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-2xl font-semibold mb-4">Your cart is empty</h2>
        <p class="text-gray-600 mb-6">Add some products to get started!</p>
        <a href="{{ route('products.index') }}" class="add-to-cart inline-block">
            Continue Shopping
        </a>
    </div>
    @endif
</div>

<script>
// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function increaseQuantity(cartItemId) {
    const quantityInput = document.getElementById('quantity-' + cartItemId);
    const maxStock = parseInt(quantityInput.getAttribute('max'));
    
    let currentValue = parseInt(quantityInput.value);
    if (currentValue < maxStock) {
        const newValue = currentValue + 1;
        quantityInput.value = newValue;
        
        // Update via AJAX
        updateCartQuantity(cartItemId, newValue);
    }
}

function decreaseQuantity(cartItemId) {
    const quantityInput = document.getElementById('quantity-' + cartItemId);
    
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        const newValue = currentValue - 1;
        quantityInput.value = newValue;
        
        // Update via AJAX
        updateCartQuantity(cartItemId, newValue);
    }
}

// Update cart quantity via AJAX
function updateCartQuantity(cartItemId, newQuantity) {
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
        if (data.success) {
            // Update total price display
            updateTotalPrice();
        } else {
            console.error('Failed to update cart:', data.message);
            // Revert quantity on failure
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        // Revert quantity on error
        location.reload();
    });
}

// Update total price display
function updateTotalPrice() {
    // Calculate new total from all cart items
    let newTotal = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
        const quantity = parseInt(item.querySelector('input[name="quantity"]').value);
        const priceText = item.querySelector('.font-bold.text-sm').textContent;
        const price = parseInt(priceText.replace(/[^\d]/g, ''));
        newTotal += quantity * price;
    });
    
    // Update total display
    const totalElement = document.querySelector('.text-xl.font-bold.text-red-600');
    if (totalElement) {
        totalElement.textContent = 'Rp ' + newTotal.toLocaleString('id-ID');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page loaded with variant support');
});
</script>
@endsection

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 768px) {
    .cart-item .flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .cart-item .flex > div:last-child {
        align-self: stretch;
    }
}
</style>
