@extends('layouts.app')

@section('title', $product->name . ' - Bennylovescoffee')

@section('content')
<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success" id="cart-notification">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error" id="cart-notification">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<div class="product-detail">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator">></span>
            <a href="{{ route('products.index') }}">Products</a>
            <span class="separator">></span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="product-content-wrapper">
            <!-- Product Images (Left) -->
            <div class="product-images">
                <div class="main-image">
                    @if($product->main_photo)
                        <img src="{{ asset('storage/' . $product->main_photo) }}" alt="{{ $product->name }}" id="mainImage">
                    @else
                        <div class="product-placeholder-large">
                            <i class="fas fa-coffee"></i>
                            <span>Product Image</span>
                        </div>
                    @endif
                </div>
                
                <!-- All Product Images as Thumbnails -->
                <div class="thumbnail-images">
                    @php
                        $allPhotos = [];
                        if($product->main_photo) {
                            $allPhotos[] = $product->main_photo;
                        }
                        if($product->photos && count($product->photos) > 0) {
                            $allPhotos = array_merge($allPhotos, $product->photos);
                        }
                    @endphp
                    
                    @if(count($allPhotos) > 0)
                        @foreach($allPhotos as $index => $photo)
                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" data-image="{{ asset('storage/' . $photo) }}" onclick="changeMainImage(this.dataset.image, this)">
                            <img src="{{ asset('storage/' . $photo) }}" alt="{{ $product->name }}">
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Product Info (Center) -->
            <div class="product-info">
                <div class="product-header">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->rating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">{{ number_format($product->rating, 1) }} ({{ $product->sold_count }} terjual)</span>
                    </div>
                </div>

                <div class="product-price">
                    @if($product->hasVariants())
                        <div class="price-range">
                            <span class="price-label">Harga mulai dari:</span>
                            <span class="current-price">{{ $product->getPriceRange() }}</span>
                        </div>
                        <div class="variant-info">
                            <span class="variant-count">{{ $product->getActiveVariants()->count() }} Variant Tersedia</span>
                        </div>
                    @else
                        @if($product->discount_percentage > 0)
                            <div class="price-row">
                                <span class="old-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="discount-badge">-{{ $product->discount_percentage }}%</span>
                            </div>
                            <div class="new-price">Rp {{ number_format($product->getDiscountedPrice(), 0, ',', '.') }}</div>
                        @else
                            <div class="current-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                    @endif
                </div>

                <div class="product-description">
                    <h3>Deskripsi Produk</h3>
                    <p>{!! nl2br(e($product->description ?? 'Deskripsi produk tidak tersedia.')) !!}</p>
                </div>

                @if($product->hasVariants())
                <div class="product-variants">
                    <h3>Pilih Variant</h3>
                    <div class="variants-grid">
                        @foreach($product->getActiveVariants() as $variant)
                        <div class="variant-option" data-variant-id="{{ $variant->id }}" data-variant-name="{{ $variant->name }}" data-variant-price="{{ $variant->price }}" data-variant-stock="{{ $variant->quantity }}">
                            <div class="variant-card">
                                <div class="variant-name">{{ $variant->name }}</div>
                                <div class="variant-price">Rp {{ number_format($variant->price, 0, ',', '.') }}</div>
                                <div class="variant-stock">
                                    @if($variant->quantity > 0)
                                        <span class="in-stock">Stok: {{ $variant->quantity }}</span>
                                    @else
                                        <span class="out-of-stock">Stok Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Product Sidebar (Right) -->
            <div class="product-sidebar">
                <div class="sidebar-card">
                    @if($product->hasVariants())
                        <!-- Variant Selection -->
                        <div class="variant-selection">
                            <h3>Pilih Variant</h3>
                            <div class="selected-variant" id="selectedVariant" style="display: none;">
                                <div class="selected-variant-info">
                                    <span class="variant-name" id="selectedVariantName"></span>
                                    <span class="variant-price" id="selectedVariantPrice"></span>
                                </div>
                            </div>
                            <div class="variant-prompt" id="variantPrompt">
                                <p>Silakan pilih variant terlebih dahulu</p>
                            </div>
                        </div>
                    @endif

                    @auth
                        @if($product->hasVariants())
                            <!-- Form untuk produk DENGAN variant -->
                            <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="add-to-cart-form" id="addToCartForm">
                                @csrf
                                <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                                
                                <div class="quantity-section">
                                    <h3>Jumlah</h3>
                                    <div class="quantity-selector">
                                        <div class="quantity-controls">
                                            <button type="button" onclick="decreaseQuantity()" class="quantity-btn">-</button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" class="quantity-input">
                                            <button type="button" onclick="increaseQuantity()" class="quantity-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="add-to-cart-btn" id="addToCartBtn" disabled>
                                    <i class="fas fa-shopping-cart"></i>
                                    <span id="addToCartText">Pilih Variant Terlebih Dahulu</span>
                                </button>
                            </form>
                        @else
                            <!-- Form untuk produk TANPA variant -->
                            <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="add-to-cart-form" id="addToCartForm">
                                @csrf
                                
                                <div class="quantity-section">
                                    <h3>Jumlah</h3>
                                    <div class="quantity-selector">
                                        <div class="quantity-controls">
                                            <button type="button" onclick="decreaseQuantity()" class="quantity-btn">-</button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="quantity-input">
                                            <button type="button" onclick="increaseQuantity()" class="quantity-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="add-to-cart-btn" id="addToCartBtn">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span id="addToCartText">Tambah ke Keranjang</span>
                                </button>
                            </form>
                        @endif
                    @else
                    <div class="login-prompt">
                        <p>Silakan <a href="{{ route('login') }}">login</a> untuk menambahkan produk ke keranjang.</p>
                    </div>
                    @endauth
                </div>

                <div class="sidebar-card">
                    <h3>Spesifikasi</h3>
                    <div class="specs-simple">
                        <div class="spec-item">
                            <span class="spec-label">Kategori:</span>
                            <span class="spec-value">{{ $product->category->name ?? 'Tidak tersedia' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(imageSrc, thumbnail) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Remove active class from all thumbnails
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    
    // Add active class to clicked thumbnail
    thumbnail.classList.add('active');
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.getAttribute('max'));
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Variant Selection
let selectedVariant = null;

function selectVariant(variantElement) {
    // Remove selected class from all variants
    document.querySelectorAll('.variant-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked variant
    variantElement.classList.add('selected');
    
    // Get variant data
    const variantId = variantElement.dataset.variantId;
    const variantName = variantElement.dataset.variantName;
    const variantPrice = variantElement.dataset.variantPrice;
    const variantStock = parseInt(variantElement.dataset.variantStock);
    
    // Store selected variant
    selectedVariant = {
        id: variantId,
        name: variantName,
        price: variantPrice,
        stock: variantStock
    };
    
    // Update sidebar display
    updateSidebarVariant(variantName, variantPrice);
    
    // Enable add to cart button
    enableAddToCart();
}

function updateSidebarVariant(name, price) {
    const selectedVariantDiv = document.getElementById('selectedVariant');
    const variantPrompt = document.getElementById('variantPrompt');
    const variantNameSpan = document.getElementById('selectedVariantName');
    const variantPriceSpan = document.getElementById('selectedVariantPrice');
    
    if (selectedVariantDiv && variantNameSpan && variantPriceSpan) {
        variantNameSpan.textContent = name;
        variantPriceSpan.textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        
        selectedVariantDiv.style.display = 'block';
        if (variantPrompt) variantPrompt.style.display = 'none';
    }
}

function enableAddToCart() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const addToCartText = document.getElementById('addToCartText');
    const selectedVariantId = document.getElementById('selectedVariantId');
    
    if (addToCartBtn && addToCartText && selectedVariantId) {
        addToCartBtn.disabled = false;
        addToCartText.textContent = 'Tambah ke Keranjang';
        selectedVariantId.value = selectedVariant.id;
        
        // Debug logging
        console.log('Variant selected:', selectedVariant);
        console.log('Variant ID set to:', selectedVariantId.value);
    }
}

// Initialize variant selection
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listeners to variant options
    const variantOptions = document.querySelectorAll('.variant-option');
    
    variantOptions.forEach((option, index) => {
        option.addEventListener('click', function() {
            selectVariant(this);
        });
    });
    
    // Add form validation - hanya untuk produk dengan variant
    const addToCartForm = document.getElementById('addToCartForm');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            const variantInput = document.getElementById('selectedVariantId');
            
            // Cek apakah produk memiliki variant (ada input variant_id)
            if (variantInput) {
                console.log('Form submitted, variant_id value:', variantInput.value);
                
                if (!variantInput.value) {
                    e.preventDefault();
                    alert('Pilih variant dulu sebelum tambah ke keranjang 🛒');
                    return false;
                }
                
                console.log('Form validation passed, submitting with variant_id:', variantInput.value);
            } else {
                // Produk tanpa variant - langsung submit
                console.log('Form submitted for product without variant');
            }
        });
    }
    
    // Auto-hide notification
    const notification = document.getElementById('cart-notification');
    if (notification) {
        // Show notification for 4 seconds then fade out
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 4000);
    }
});
</script>

<style>
.product-detail {
    padding: 2rem 0;
    min-height: 60vh;
    margin-top: 2rem;
}

/* Flash Messages */
.alert {
    position: fixed;
    top: 100px;
    right: 30px;
    z-index: 10000;
    padding: 16px 24px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: slideInRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    min-width: 320px;
    max-width: 450px;
    pointer-events: auto;
}

.alert-success {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    color: #ffffff;
    border-left: 4px solid #229954;
}

.alert-error {
    background: linear-gradient(135deg, #e74c3c, #ff6b6b);
    color: #ffffff;
    border-left: 4px solid #c0392b;
}

.alert i {
    font-size: 1.5rem;
}

.alert.fade-out {
    animation: slideOutRight 0.5s ease-out forwards;
}

@keyframes slideInRight {
    0% {
        transform: translateX(120%) scale(0.8);
        opacity: 0;
    }
    100% {
        transform: translateX(0) scale(1);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    0% {
        transform: translateX(0) scale(1);
        opacity: 1;
    }
    100% {
        transform: translateX(120%) scale(0.8);
        opacity: 0;
    }
}

@media (max-width: 768px) {
    .alert {
        top: 80px;
        right: 10px;
        left: 10px;
        min-width: auto;
        max-width: none;
    }
}

.breadcrumb {
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: #e74c3c;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb .separator {
    margin: 0 0.5rem;
    color: #6c757d;
}

.breadcrumb .current {
    color: #6c757d;
}

.product-content-wrapper {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    gap: 3rem;
    align-items: start;
}

.product-images {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.main-image {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.product-placeholder-large {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.product-placeholder-large i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.thumbnail-images {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-top: 1rem;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.thumbnail:hover {
    border-color: #3498db;
    transform: scale(1.05);
}

.thumbnail.active {
    border-color: #e74c3c;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.product-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    text-align: left;
}

.product-header {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.product-title {
    font-size: 2rem;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 1rem;
    justify-content: flex-start;
}

.stars {
    color: #ffc107;
    font-size: 1.1rem;
}

.rating-text {
    color: #6c757d;
    font-size: 0.9rem;
}

.product-price {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.price-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.old-price {
    color: #6c757d;
    text-decoration: line-through;
    font-size: 1.1rem;
}

.discount-badge {
    background: #e74c3c;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.new-price, .current-price {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
}

.product-description {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.product-description h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.2rem;
    text-align: left;
}

.product-description p {
    color: #495057;
    line-height: 1.6;
    text-align: left;
}

.specs-simple {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.specs-grid {
    display: grid;
    gap: 0.75rem;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.spec-item:last-child {
    border-bottom: none;
}

.spec-label {
    font-weight: 500;
    color: #6c757d;
}

.spec-value {
    color: #495057;
}

.product-actions {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.quantity-selector {
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: center;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.25rem;
    background: white;
}

.quantity-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: #f8f9fa;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-weight: bold;
    color: #495057;
}

.quantity-btn:hover {
    background: #e74c3c;
    color: white;
}

.quantity-input {
    width: 50px;
    height: 36px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 1rem;
    font-weight: 500;
    color: #2c3e50;
}

.add-to-cart-btn {
    width: 100%;
    background: #e74c3c;
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.add-to-cart-btn:hover {
    background: #c0392b;
}

.login-prompt {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.login-prompt a {
    color: #e74c3c;
    text-decoration: none;
}

.login-prompt a:hover {
    text-decoration: underline;
}

.product-sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.sidebar-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.sidebar-card h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.shipping-info {
    list-style: none;
    padding: 0;
    margin: 0;
}

.shipping-info li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: #495057;
    border-bottom: 1px solid #f8f9fa;
}

.shipping-info li:last-child {
    border-bottom: none;
}

.shipping-info i {
    color: #e74c3c;
    width: 16px;
}

.related-products {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.related-product {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: border-color 0.3s;
}

.related-product:hover {
    border-color: #e74c3c;
}

.related-product-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.related-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-placeholder {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.related-product-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
    line-height: 1.3;
}

.related-product-info a {
    color: #2c3e50;
    text-decoration: none;
}

.related-product-info a:hover {
    color: #e74c3c;
}

.related-price {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.related-price .old-price {
    font-size: 0.8rem;
    color: #6c757d;
    text-decoration: line-through;
}

.related-price .new-price, .related-price .price {
    font-size: 0.9rem;
    font-weight: 500;
    color: #e74c3c;
}

/* Variant Styling */
.product-variants {
    margin: 2rem 0;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.product-variants h3 {
    margin: 0 0 1rem 0;
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
}

.variants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.variant-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.variant-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.variant-option:hover .variant-card {
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
}

.variant-option.selected .variant-card {
    border-color: #e74c3c;
    background: #fff5f5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.15);
}

.variant-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.variant-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #e74c3c;
    margin-bottom: 0.5rem;
}

.variant-stock {
    font-size: 0.9rem;
}

.variant-stock .in-stock {
    color: #27ae60;
    font-weight: 500;
}

.variant-stock .out-of-stock {
    color: #e74c3c;
    font-weight: 500;
}

/* Sidebar Variant Selection */
.variant-selection {
    margin-bottom: 1.5rem;
}

.variant-selection h3 {
    margin: 0 0 1rem 0;
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
}

.selected-variant {
    background: #e8f5e8;
    border: 2px solid #27ae60;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.selected-variant-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.selected-variant .variant-name {
    font-weight: 600;
    color: #2c3e50;
}

.selected-variant .variant-price {
    font-weight: 700;
    color: #27ae60;
    font-size: 1.1rem;
}

.variant-prompt {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 0.75rem;
    text-align: center;
    color: #856404;
    font-size: 0.9rem;
}

.quantity-section {
    margin-bottom: 1.5rem;
}

.quantity-section h3 {
    margin: 0 0 1rem 0;
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
}

.add-to-cart-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.add-to-cart-btn:disabled:hover {
    background: #6c757d;
    transform: none;
}

/* Price Range Styling */
.price-range {
    margin-bottom: 0.5rem;
}

.price-label {
    font-size: 0.9rem;
    color: #6c757d;
    display: block;
    margin-bottom: 0.25rem;
}

.variant-info {
    margin-top: 0.5rem;
}

.variant-count {
    font-size: 0.9rem;
    color: #3498db;
    font-weight: 500;
}

@media (max-width: 768px) {
    .product-content-wrapper {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .product-title {
        font-size: 1.5rem;
    }
    
    .main-image {
        aspect-ratio: 1 / 1;
        max-height: 400px;
    }
    
    .thumbnail-images {
        justify-content: center;
    }
    
    .variants-grid {
        grid-template-columns: 1fr;
    }
    
    .selected-variant-info {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}
</style>
@endsection