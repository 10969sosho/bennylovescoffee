@extends('layouts.app')

@section('title', 'Produk Unggulan - Bennylovescoffee')

@section('content')
<div class="featured-products-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Produk Unggulan</h1>
        </div>


        <!-- Results Info -->
        <div class="results-info">
            <p>Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk unggulan</p>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card" data-testid="featured-product-{{ $product->id }}">
                        <div class="product-card-inner">
                            <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                                <div class="product-image-wrapper">
                                    @if($product->main_photo)
                                        <img src="{{ asset('storage/' . $product->main_photo) }}" 
                                             alt="{{ $product->name }}" 
                                             class="product-image"
                                             loading="lazy">
                                    @else
                                        <div class="product-placeholder">
                                            <i class="fas fa-coffee"></i>
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                    
                                    @if($product->discount_percentage > 0)
                                        <div class="product-badge">
                                            <span>-{{ $product->discount_percentage }}%</span>
                                        </div>
                                    @endif
                                    
                                    <div class="product-overlay">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                                
                                <div class="product-content">
                                    <h3 class="product-title">{{ $product->name }}</h3>
                                    
                                    <div class="product-meta">
                                        <div class="product-price">
                                            @if($product->hasVariants())
                                                <span class="new-price">{{ $product->getPriceRange() }}</span>
                                            @else
                                                @if($product->discount_percentage > 0)
                                                    <span class="old-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                    <span class="new-price">Rp {{ number_format($product->getDiscountedPrice(), 0, ',', '.') }}</span>
                                                @else
                                                    <span class="new-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                @endif
                                            @endif
                                        </div>
                                        
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
                                            <span class="sold-count">{{ $product->sold_count }} terjual</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="product-actions">
                                @auth
                                    @if($product->hasVariants())
                                        @if($product->getTotalStock() > 0)
                                            <a href="{{ route('products.show', $product->slug) }}" class="add-to-cart-btn">
                                                <i class="fas fa-plus"></i>
                                                <i class="fas fa-shopping-cart"></i>
                                            </a>
                                        @else
                                            <button type="button" class="add-to-cart-btn disabled" disabled>
                                                <i class="fas fa-times-circle"></i>
                                            </button>
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
                                            <button type="button" class="add-to-cart-btn disabled" disabled>
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="add-to-cart-btn">
                                        <i class="fas fa-plus"></i>
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <p>Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
                </div>
                <div class="pagination-controls">
                    {{ $products->links('pagination.custom') }}
                </div>
            </div>
        @else
            <div class="no-products">
                <div class="no-products-content">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada produk unggulan ditemukan</h3>
                    <p>Coba ubah filter pencarian Anda</p>
                    <a href="{{ route('products.featured') }}" class="reset-filters-btn">
                        Reset Filter
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Featured Products Page Styles */
.featured-products-page {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.container {
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    box-sizing: border-box;
}

/* Page Header */
.page-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0;
    position: relative;
    display: inline-block;
}

.page-header h1::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    right: 0;
    height: 3px;
    background: #2d3436;
    border-radius: 2px;
}

/* Filters Section */
.filters-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.filters-form {
    width: 100%;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-weight: 600;
    color: #2d3436;
    font-size: 14px;
}

.filter-group input,
.filter-group select {
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #ff6b35;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.filter-actions {
    display: flex;
    gap: 1rem;
    align-items: end;
}

.filter-btn,
.clear-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.filter-btn {
    background: linear-gradient(135deg, #ff6b35, #e55a2b);
    color: white;
}

.filter-btn:hover {
    background: linear-gradient(135deg, #e55a2b, #d44a1a);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
}

.clear-btn {
    background: #6c757d;
    color: white;
}

.clear-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Results Info */
.results-info {
    margin-bottom: 2rem;
    padding: 0 1rem;
}

.results-info p {
    color: #636e72;
    font-size: 14px;
    margin: 0;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 2rem;
    margin-bottom: 3rem;
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
}

.product-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.product-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: #ff6b35;
}

.product-card-inner {
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-image-wrapper {
    position: relative;
    height: 250px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.4s ease;
}

.product-card:hover .product-image {
    transform: scale(1.1);
}

.product-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    position: relative;
}

.product-placeholder span {
    font-size: 0.9rem;
    margin-top: 0.5rem;
    opacity: 0.8;
}

.product-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #ff6b35, #e55a2b);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
    z-index: 3;
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-overlay i {
    color: white;
    font-size: 2rem;
}

.product-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0 0 1rem 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.8rem;
}

.product-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: auto;
}

.product-price {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.old-price {
    font-size: 13px;
    color: #999;
    text-decoration: line-through;
    font-weight: 500;
}

.new-price {
    font-size: 1.3rem;
    font-weight: 800;
    color: #ff6b35;
    letter-spacing: -0.5px;
}

.product-rating {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
}

.stars {
    color: #ffc107;
    display: flex;
    gap: 2px;
}

.stars i {
    font-size: 12px;
}

.sold-count {
    color: #636e72;
    font-weight: 500;
}

.product-actions {
    padding: 1.5rem;
    padding-top: 0;
}

.add-to-cart-btn {
    width: 100%;
    background: linear-gradient(135deg, #ff6b35, #e55a2b);
    color: white;
    border: none;
    padding: 14px 20px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #e55a2b, #d44a1a);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
    color: white;
    text-decoration: none;
}

.add-to-cart-btn:active {
    transform: translateY(0);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

/* No Products */
.no-products {
    text-align: center;
    padding: 4rem 2rem;
}

.no-products-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-products-content i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-products-content h3 {
    font-size: 1.5rem;
    color: #2d3436;
    margin-bottom: 0.5rem;
}

.no-products-content p {
    color: #636e72;
    margin-bottom: 2rem;
}

.reset-filters-btn {
    background: linear-gradient(135deg, #ff6b35, #e55a2b);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.reset-filters-btn:hover {
    background: linear-gradient(135deg, #e55a2b, #d44a1a);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    margin-top: 4rem;
    padding: 2rem 0;
}

.pagination-info {
    text-align: center;
}

.pagination-info p {
    color: #636e72;
    font-size: 14px;
    margin: 0;
    font-weight: 500;
}

.pagination-controls {
    display: flex;
    justify-content: center;
}

/* Custom Pagination Styling */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
    align-items: center;
}

.pagination li {
    display: flex;
}

.pagination a,
.pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    min-width: 40px;
    height: 40px;
}

.pagination a {
    color: #636e72;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
}

.pagination a:hover {
    color: #ffffff;
    background: #ff6b35;
    border-color: #ff6b35;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
}

.pagination .active span {
    color: #ffffff;
    background: #ff6b35;
    border: 1px solid #ff6b35;
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
}

.pagination .disabled span {
    color: #adb5bd;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    cursor: not-allowed;
}

/* Previous/Next buttons */
.pagination .page-item:first-child a,
.pagination .page-item:last-child a {
    font-weight: 700;
    padding: 10px 20px;
}

.pagination .page-item:first-child a:hover,
.pagination .page-item:last-child a:hover {
    transform: translateY(-2px) scale(1.05);
}

/* Custom Pagination with Icons */
.pagination-nav {
    display: flex;
    justify-content: center;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
}

.pagination .page-link i {
    font-size: 12px;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    padding: 10px 20px;
    font-weight: 700;
}

.pagination .page-item:first-child .page-link {
    flex-direction: row;
}

.pagination .page-item:last-child .page-link {
    flex-direction: row;
}

/* Responsive */
@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
    }
    
    .page-header {
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .product-image-wrapper {
        height: 200px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .product-image-wrapper {
        height: 180px;
    }
    
    .product-content {
        padding: 1rem;
    }
    
    .product-actions {
        padding: 1rem;
        padding-top: 0;
    }
    
    .pagination-wrapper {
        margin-top: 3rem;
        padding: 1.5rem 0;
    }
    
    .pagination a,
    .pagination span {
        padding: 8px 12px;
        min-width: 35px;
        height: 35px;
        font-size: 13px;
    }
    
    .pagination .page-item:first-child a,
    .pagination .page-item:last-child a {
        padding: 8px 15px;
    }
}
</style>
@endsection
