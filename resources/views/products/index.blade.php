@extends('layouts.app')

@section('title', 'Products - Bennylovescoffee')

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

<!-- Products Page Content -->
<main class="products-page">
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 style="color: #ffffff !important;">All Products</h1>
            <p style="color: #cccccc !important;">Discover our complete collection of premium coffee equipment</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-container">
                <!-- Search Box -->
                <div class="filter-group search-group">
                    <input 
                        type="text" 
                        class="filter-search-input" 
                        placeholder="Search products..." 
                        value="{{ request('search') }}"
                        onkeypress="if(event.key === 'Enter') searchProducts(this.value)"
                    >
                    <button class="search-icon-btn" onclick="searchProducts(document.querySelector('.filter-search-input').value)">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="filter-group">
                    <label>Category</label>
                    <select class="filter-select" onchange="filterByCategory(this.value)">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                
                <button class="filter-clear-btn" onclick="clearFilters()">
                    <i class="fas fa-times"></i>
                    Clear Filters
                </button>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-grid-section">
        <div class="container">
            @if(request('search') || request('category'))
            <div class="filter-info-compact">
                <span class="filter-tag">
                    @if(request('search'))
                        <i class="fas fa-search"></i> "{{ request('search') }}"
                    @endif
                    @if(request('category'))
                        @php
                            $selectedCategory = $categories->firstWhere('id', request('category'));
                        @endphp
                        @if($selectedCategory)
                            @if(request('search')) • @endif
                            <i class="fas fa-tag"></i> {{ $selectedCategory->name }}
                        @endif
                    @endif
                </span>
                <span class="results-count">{{ $products->total() }} products</span>
            </div>
            @endif

            @if($products->count() > 0)
            <div class="all-products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                        <div class="product-image-container">
                            @if($product->main_photo)
                                <img src="{{ asset('storage/' . $product->main_photo) }}" alt="{{ $product->name }}" class="product-image">
                            @elseif($product->foto)
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->name }}" class="product-image">
                            @elseif($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-placeholder">
                                    <i class="fas fa-coffee"></i>
                                </div>
                            @endif
                            
                            @if($product->discount_price || $product->discount_percentage)
                            <div class="product-badge sale-badge">
                                -{{ $product->getDiscountPercentage() }}%
                            </div>
                            @endif
                            
                            @if($product->isFeatured())
                            <div class="product-badge featured-badge">
                                <i class="fas fa-star"></i> Featured
                            </div>
                            @endif
                        </div>
                        
                        <div class="product-content">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            
                            
                            <div class="product-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    <span class="rating-number">(5.0)</span>
                                </div>
                                <span class="sold-count">{{ number_format($product->sold_count) }} sold</span>
                            </div>
                            
                            <div class="product-price">
                                @if($product->hasVariants())
                                    <span class="new-price">{{ $product->getPriceRange() }}</span>
                                @else
                                    @if($product->discount_price || $product->discount_percentage)
                                        <span class="old-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="new-price">Rp {{ number_format($product->getDiscountedPrice(), 0, ',', '.') }}</span>
                                    @else
                                        <span class="new-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                @endif
                            </div>
                            
                        </div>
                    </a>
                    
                    @auth
                        @if($product->hasVariants())
                            @if($product->getTotalStock() > 0)
                            <div class="add-to-cart-form">
                                <a href="{{ route('products.show', $product->slug) }}" class="add-to-cart-btn">
                                    <i class="fas fa-plus"></i>
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </div>
                            @else
                            <div class="add-to-cart-form">
                                <button class="add-to-cart-btn disabled" disabled>
                                    <i class="fas fa-times-circle"></i>
                                    Out of Stock
                                </button>
                            </div>
                            @endif
                        @else
                            @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="add-to-cart-btn">
                                    <i class="fas fa-plus"></i>
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                            @else
                            <button class="add-to-cart-btn disabled" disabled>
                                <i class="fas fa-times-circle"></i>
                                Out of Stock
                            </button>
                            @endif
                        @endif
                    @else
                    <div class="add-to-cart-form">
                        <a href="{{ route('login') }}" class="add-to-cart-btn">
                            <i class="fas fa-plus"></i>
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </div>
                    @endauth
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination">
                    <!-- Previous Button -->
                    @if($products->onFirstPage())
                        <button class="pagination-btn prev-btn" disabled>
                            <i class="fas fa-chevron-left"></i>
                            Previous
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="pagination-btn prev-btn">
                            <i class="fas fa-chevron-left"></i>
                            Previous
                        </a>
                    @endif
                    
                    <!-- Page Numbers -->
                    <div class="pagination-numbers">
                        @php
                            $start = max(1, $products->currentPage() - 2);
                            $end = min($products->lastPage(), $products->currentPage() + 2);
                        @endphp
                        
                        @if($start > 1)
                            <a href="{{ $products->url(1) }}" class="pagination-number">1</a>
                            @if($start > 2)
                                <span class="pagination-ellipsis">...</span>
                            @endif
                        @endif
                        
                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $products->currentPage())
                                <span class="pagination-number active">{{ $i }}</span>
                            @else
                                <a href="{{ $products->url($i) }}" class="pagination-number">{{ $i }}</a>
                            @endif
                        @endfor
                        
                        @if($end < $products->lastPage())
                            @if($end < $products->lastPage() - 1)
                                <span class="pagination-ellipsis">...</span>
                            @endif
                            <a href="{{ $products->url($products->lastPage()) }}" class="pagination-number">{{ $products->lastPage() }}</a>
                        @endif
                    </div>
                    
                    <!-- Next Button -->
                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="pagination-btn next-btn">
                            Next
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="pagination-btn next-btn" disabled>
                            Next
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
                
                <div class="pagination-info">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
            </div>
            @endif
            @else
            <div class="no-products">
                <div class="no-products-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>No products found</h3>
                <p>Try adjusting your filters or search terms.</p>
                <button onclick="clearFilters()" class="btn-primary">
                    <i class="fas fa-redo"></i>
                    Clear Filters
                </button>
            </div>
            @endif
        </div>
    </section>
</main>

<style>
/* Notification Styles */
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

/* Products Page Styles */
.products-page {
    margin: 0;
    padding: 0;
}

.page-header {
    background: linear-gradient(135deg, #000000, #1a1a1a);
    padding: 60px 0;
    text-align: center;
    margin: 0;
}

.page-header h1 {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #ffffff !important;
}

.page-header p {
    font-size: 1.2rem;
    color: #cccccc !important;
}

/* Filter Section */
.filter-section {
    background: #f8f8f8;
    padding: 30px 0;
    border-bottom: 2px solid #e0e0e0;
}

.filter-container {
    display: flex;
    gap: 1rem;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 200px;
}

.filter-group.search-group {
    flex: 1;
    min-width: 300px;
    position: relative;
}

.filter-group label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #333;
}

.filter-select {
    padding: 10px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:hover,
.filter-select:focus {
    border-color: #d4af37;
    outline: none;
}

.filter-search-input {
    width: 100%;
    padding: 10px 45px 10px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.filter-search-input:focus {
    border-color: #d4af37;
    outline: none;
}

.search-icon-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px 10px;
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

.search-icon-btn:hover {
    color: #d4af37;
}

.filter-clear-btn {
    background: #ff4757;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    align-self: flex-end;
}

.filter-clear-btn:hover {
    background: #ee3344;
    transform: translateY(-2px);
}

/* Filter Info Compact */
.filter-info-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    margin-bottom: 24px;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border-left: 3px solid #d4af37;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.filter-tag {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
    font-weight: 600;
    font-size: 0.95rem;
}

.filter-tag i {
    color: #d4af37;
    font-size: 0.9rem;
}

.results-count {
    color: #666;
    font-size: 0.85rem;
    background: #f0f0f0;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Products Grid Section */
.products-grid-section {
    padding: 60px 0;
    background: #ffffff;
}

/* All Products Grid - 5x5 Layout with Tight Spacing */
.all-products-grid {
    display: grid;
    grid-template-columns: repeat(5, 220px);
    justify-content: center;
    gap: 20px;
    padding: 2rem 0;
    width: auto;
    margin: 0 auto;
}

/* Product Card */
.product-card {
    width: 220px;
    min-width: 220px;
    max-width: 220px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.product-link {
    text-decoration: none;
    color: inherit;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-image-container {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #f8f8f8;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.1);
}

.product-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
    color: #999;
    font-size: 3rem;
}

.product-badge {
    position: absolute;
    top: 10px;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: bold;
    color: white;
    z-index: 10;
}

.sale-badge {
    right: 10px;
    background: linear-gradient(135deg, #ff4757, #ff6b7a);
}

.featured-badge {
    left: 10px;
    background: linear-gradient(135deg, #d4af37, #f0d872);
    display: flex;
    align-items: center;
    gap: 4px;
}

.product-content {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.product-title {
    font-size: 15px;
    font-weight: 600;
    color: #000;
    margin: 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 39px;
}


.product-rating {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.75rem;
}

.stars {
    display: flex;
    align-items: center;
    gap: 2px;
    color: #ffc107;
}

.stars i {
    font-size: 0.85rem;
}

.rating-number {
    margin-left: 4px;
    color: #666;
    font-size: 0.75rem;
}

.sold-count {
    color: #999;
    font-size: 0.75rem;
}

.product-price {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-top: auto;
}

.old-price {
    font-size: 0.85rem;
    color: #999;
    text-decoration: line-through;
}

.new-price {
    font-size: 1.1rem;
    font-weight: bold;
    color: #ff4757;
}


.add-to-cart-form {
    padding: 0 16px 16px 16px;
}

.add-to-cart-btn {
    width: 100%;
    background: linear-gradient(135deg, #000000, #333333);
    color: #ffffff;
    border: none;
    padding: 10px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    font-size: 0.9rem;
}

.add-to-cart-btn:hover:not(.disabled) {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    transform: translateY(-2px);
}

.add-to-cart-btn.disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

/* No Products */
.no-products {
    text-align: center;
    padding: 80px 20px;
}

.no-products-icon {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 20px;
}

.no-products h3 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 10px;
}

.no-products p {
    color: #666;
    margin-bottom: 30px;
}

.btn-primary {
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pagination-btn {
    background: linear-gradient(135deg, #000000, #333333);
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.pagination-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #d4af37, #f0d872);
    color: #000;
    transform: translateY(-2px);
}

.pagination-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.5;
}

.pagination-numbers {
    display: flex;
    gap: 5px;
}

.pagination-number {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ddd;
    border-radius: 8px;
    background: white;
    color: #333;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.pagination-number:hover {
    border-color: #d4af37;
    color: #d4af37;
}

.pagination-number.active {
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
    border-color: #000000;
}

.pagination-ellipsis {
    display: flex;
    align-items: center;
    padding: 0 5px;
    color: #999;
}

.pagination-info {
    color: #666;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .all-products-grid {
        grid-template-columns: repeat(4, 220px);
    }
    
    .alert {
        right: 20px;
        min-width: 280px;
    }
}

@media (max-width: 1000px) {
    .all-products-grid {
        grid-template-columns: repeat(3, 220px);
    }
    
    .filter-container {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
        min-width: auto;
    }
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2rem;
    }
    
    /* Products Grid - Wider cards, less margin */
    .all-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 1rem 8px; /* Reduced padding */
        margin: 0;
        width: 100%;
        max-width: 100%;
    }
    
    .alert {
        top: 80px;
        right: 10px;
        left: 10px;
        min-width: auto;
        max-width: none;
    }
    
    .filter-info-compact {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        margin: 0 8px 24px 8px; /* Reduced margin */
    }
    
    .product-card {
        width: 100%;
        min-width: auto;
        max-width: none;
    }
    
    .product-image-container {
        height: 180px; /* Slightly taller */
    }
    
    .product-content {
        padding: 14px;
    }
    
    .product-title {
        font-size: 14px;
        line-height: 1.4;
    }
    
    .pagination {
        flex-wrap: wrap;
        gap: 8px;
        margin: 0 8px; /* Reduced margin */
    }
    
    .pagination-btn {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    /* Container adjustments for mobile */
    .products-grid-section {
        padding: 40px 0;
    }
    
    .container {
        padding: 0 8px; /* Minimal padding */
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .all-products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        padding: 0.5rem 4px; /* Even less padding */
    }
    
    .product-card {
        width: 100%;
        min-width: auto;
        max-width: none;
    }
    
    .product-image-container {
        height: 160px;
    }
    
    .product-content {
        padding: 12px;
    }
    
    .product-title {
        font-size: 13px;
        line-height: 1.3;
    }
    
    .pagination-number {
        min-width: 35px;
        height: 35px;
    }
    
    .container {
        padding: 0 4px; /* Minimal padding for small screens */
    }
    
    .filter-info-compact {
        margin: 0 4px 20px 4px;
    }
}
</style>

<script>
function searchProducts(searchTerm) {
    const url = new URL(window.location);
    if (searchTerm && searchTerm.trim()) {
        url.searchParams.set('search', searchTerm.trim());
    } else {
        url.searchParams.delete('search');
    }
    window.location = url;
}

function filterByCategory(categoryId) {
    const url = new URL(window.location);
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }
    url.searchParams.delete('page'); // Reset to page 1 when filtering
    window.location = url;
}


function clearFilters() {
    window.location = '{{ route("products.index") }}';
}

// Auto-hide notification
document.addEventListener('DOMContentLoaded', function() {
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
@endsection