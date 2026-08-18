@extends('layouts.app')

@section('title', 'Bennylovescoffee - Premium Coffee Equipment')

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
<!-- Hero Section -->
<section id="home" class="hero">
    <!-- Banner Section -->
    <div class="banner-section">
        @if($banners->count() > 0)
            @if($banners->count() > 1)
                <!-- Banner Carousel -->
                <div class="banner-carousel-container">
                    <button class="banner-carousel-btn prev-btn" onclick="moveBannerCarousel(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="banner-carousel-wrapper">
                        <div class="banner-carousel-track" id="bannerCarouselTrack">
                            @foreach($banners as $banner)
                                <div class="banner-slide">
                                    <div class="banner-image-16-9">
                                        @if($banner->image)
                                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                                 alt="{{ $banner->title }}" 
                                                 class="banner-image">
                                        @else
                                            <div class="banner-placeholder-16-9">
                                                <i class="fas fa-coffee"></i>
                                                <p>Banner Image</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="banner-carousel-btn next-btn" onclick="moveBannerCarousel(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <!-- Banner Indicators -->
                    <div class="banner-indicators">
                        @foreach($banners as $index => $banner)
                            <button class="banner-indicator {{ $index === 0 ? 'active' : '' }}" 
                                    onclick="goToBannerSlide({{ $index }})"></button>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Single Banner -->
                @foreach($banners as $banner)
                    <div class="banner-image-16-9">
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="banner-image">
                        @else
                            <div class="banner-placeholder-16-9">
                                <i class="fas fa-coffee"></i>
                                <p>Banner Image</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        @else
            <!-- Default Banner Placeholder -->
            <div class="banner-image-16-9">
                <div class="banner-placeholder-16-9">
                    <i class="fas fa-coffee"></i>
                    <p>Banner Image 16:9</p>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Popular Categories Section -->
<section class="popular-categories">
    <div class="container">
        <!-- Desktop Carousel -->
        <h2>Kategori Terpopuler</h2>
        <div class="category-carousel-container">
            <button class="category-carousel-btn prev-btn" onclick="moveCategoryCarousel(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="category-carousel-wrapper">
                <div class="category-carousel-track" id="categoryCarouselTrack">
                    @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-item">
                        <div class="category-icon">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="category-image">
                            @else
                                <i class="fas fa-coffee"></i>
                            @endif
                        </div>
                        <span>{{ $category->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            <button class="category-carousel-btn next-btn" onclick="moveCategoryCarousel(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Mobile Scroll -->
        <div class="categories-header">
            <h2>Kategori Terpopuler</h2>
            <a href="{{ route('products.index') }}" class="see-all-link">Lihat Semua</a>
        </div>
        <div class="mobile-categories-scroll">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="mobile-category-item">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="mobile-category-image">
                @else
                    <div class="mobile-category-placeholder">
                        <i class="fas fa-coffee"></i>
                    </div>
                @endif
                <span class="mobile-category-name">{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<script>
// Initialize category carousel variables
window.categoryCurrentSlide = 0;
window.categoryCarouselTrack = null;

// Define moveCategoryCarousel function immediately
window.moveCategoryCarousel = function(direction) {
    if (!window.categoryCarouselTrack) {
        window.categoryCarouselTrack = document.getElementById('categoryCarouselTrack');
    }
    if (!window.categoryCarouselTrack) return;
    
    const items = document.querySelectorAll('.category-carousel-track .category-item');
    if (items.length === 0) return;
    
    const itemWidth = items[0].offsetWidth + 32;
    const itemsPerView = 6;
    const maxSlide = Math.max(0, items.length - itemsPerView);
    
    if (items.length <= itemsPerView) return;
    
    window.categoryCurrentSlide += direction;
    
    if (window.categoryCurrentSlide < 0) {
        window.categoryCurrentSlide = maxSlide;
    } else if (window.categoryCurrentSlide > maxSlide) {
        window.categoryCurrentSlide = 0;
    }
    
    const translateX = -window.categoryCurrentSlide * itemWidth;
    window.categoryCarouselTrack.style.transform = `translateX(${translateX}px)`;
};

// Initialize on DOM load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        window.categoryCarouselTrack = document.getElementById('categoryCarouselTrack');
    });
} else {
    window.categoryCarouselTrack = document.getElementById('categoryCarouselTrack');
}
</script>

<!-- Flash Sale Section -->
<!-- DEBUG: Flash Sales count: {{ $flashSales->count() }} -->
<!-- DEBUG: Flash Sales data: {{ $flashSales->toJson() }} -->
@if($flashSales->count() > 0)
<section class="flash-sale">
    <div class="container">
        <div class="flash-sale-header">
            <h2>🔥 FLASH SALE</h2>
        </div>
        
        <!-- Flash Sale Info Cards -->
        @foreach($flashSales as $flashSale)
        <div class="flash-sale-info-card">
            <div class="flash-sale-info-content">
                <div class="flash-sale-info-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="flash-sale-info-details">
                    <h3 class="flash-sale-info-title">{{ $flashSale->title }}</h3>
                    <p class="flash-sale-info-description">{{ $flashSale->description }}</p>
                    <div class="flash-sale-info-timer">
                        <i class="fas fa-clock"></i>
                        <span id="flashSaleInfoTimer{{ $flashSale->id }}">{{ $flashSale->end_time->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Individual flash sale info timer
            (function() {
                const endTime{{ $flashSale->id }} = new Date('{{ $flashSale->end_time->toISOString() }}').getTime();
                const timerElement{{ $flashSale->id }} = document.getElementById('flashSaleInfoTimer{{ $flashSale->id }}');
                
                function updateTimer{{ $flashSale->id }}() {
                    const now = new Date().getTime();
                    const distance = endTime{{ $flashSale->id }} - now;
                    
                    if (distance < 0) {
                        timerElement{{ $flashSale->id }}.innerHTML = 'Flash sale berakhir';
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    
                    if (days > 0) {
                        timerElement{{ $flashSale->id }}.innerHTML = `${days}d ${hours}h ${minutes}m tersisa`;
                    } else if (hours > 0) {
                        timerElement{{ $flashSale->id }}.innerHTML = `${hours}h ${minutes}m tersisa`;
                    } else {
                        timerElement{{ $flashSale->id }}.innerHTML = `${minutes}m tersisa`;
                    }
                }
                
                updateTimer{{ $flashSale->id }}();
                setInterval(updateTimer{{ $flashSale->id }}, 60000); // Update every minute
            })();
        </script>
        @endforeach
        <div class="flash-sale-carousel-container">
            <!-- Prev button removed for mobile drag only -->
            <div class="flash-sale-carousel-wrapper">
                <div class="flash-sale-carousel-track" id="flashSaleCarouselTrack">
                    @foreach($flashSales as $flashSale)
                        @foreach($flashSale->items->take(4) as $item)
                        <div class="flash-sale-item">
                            <div class="flash-sale-card">
                                <div class="flash-sale-badge">-{{ $item->getDiscountPercentage() }}%</div>
                                <div class="flash-sale-image">
                                    @if($item->product->main_photo)
                                        <img src="{{ asset('storage/' . $item->product->main_photo) }}" alt="{{ $item->product->name }}" class="flash-sale-product-image">
                                    @else
                                        <div class="flash-sale-placeholder"></div>
                                    @endif
                                </div>
                                <div class="flash-sale-info">
                                    <h3 class="flash-sale-product-name">{{ $item->product->name }}</h3>
                                    <div class="flash-sale-price">
                                        <span class="old-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                        <span class="new-price">Rp {{ number_format($item->flash_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flash-sale-stock">
                                        <span class="stock-label">Tersisa: {{ $item->getRemainingStock() }} pcs</span>
                                    </div>
                                    <div class="flash-sale-rating">
                                        <div class="stars">★★★★★</div>
                                        <span class="sold-count">{{ $item->product->sold_count }} terjual</span>
                                    </div>
                                    @auth
                                    <form action="{{ route('cart.add', $item->product->slug) }}" method="POST" class="flash-sale-form">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="flash-sale-btn-add">
                                            <i class="fas fa-plus"></i>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('login') }}" class="flash-sale-btn-add">
                                        <i class="fas fa-plus"></i>
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            <!-- Next button removed for mobile drag only -->
        </div>
    </div>
</section>
@else
    <!-- DEBUG: No active flash sales found -->
    <!-- DEBUG: Flash Sales count: {{ $flashSales->count() }} -->
@endif

<!-- Brands Section -->
@if($brands->count() > 0)
<section class="brands-section">
    <div class="home-products-container">
        <!-- Desktop Brand Header -->
        <div class="brands-header">
            <h2>🏆 Brand Terpercaya</h2>
            <p class="brands-subtitle">Temukan produk berkualitas dari brand-brand terbaik</p>
        </div>
        
        <!-- Desktop Carousel -->
        <div class="brands-carousel-container">
            <button class="brands-btn prev-btn" onclick="moveBrandsCarousel(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="brands-carousel-wrapper">
                <div class="brands-carousel-track" id="brandsCarouselTrack">
                    @foreach($brands as $brand)
                        <div class="brand-item">
                            <a href="{{ route('products.index', ['brand' => $brand->id]) }}" class="brand-card">
                                <div class="brand-image">
                                    @if($brand->image)
                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="brand-logo">
                                    @else
                                        <div class="brand-placeholder">
                                            <i class="fas fa-award"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="brand-info">
                                    <h3 class="brand-name">{{ $brand->name }}</h3>
                                    @if($brand->description)
                                        <p class="brand-description">{{ Str::limit($brand->description, 50) }}</p>
                                    @endif
                                    <div class="brand-products-count">
                                        <i class="fas fa-box"></i>
                                        {{ $brand->products->count() }} Produk
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <button class="brands-btn next-btn" onclick="moveBrandsCarousel(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Mobile Scroll -->
        <div class="brand-pilihan-header">
            <h2>🏆 Brand Terpercaya</h2>
        </div>
        <div class="brand-pilihan-scroll">
            @foreach($brands as $brand)
            <a href="{{ route('products.index', ['brand' => $brand->id]) }}" class="mobile-brand-item">
                @if($brand->image)
                    <img src="{{ asset('storage/' . $brand->image) }}" 
                         alt="{{ $brand->name }}" 
                         class="mobile-brand-image">
                @else
                    <div class="mobile-brand-placeholder">
                        <i class="fas fa-award"></i>
                    </div>
                @endif
                <span class="mobile-brand-name">{{ $brand->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
<section class="featured-products-section">
    <div class="home-products-container">
        <!-- Desktop Header -->
        <div class="featured-products-header">
            <h2>Produk Unggulan</h2>
            <a href="{{ route('products.featured') }}" class="view-all-btn">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Desktop Grid -->
        <div class="home-products-grid">
            @if($featuredProducts->count() > 0)
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            @else
                <div class="no-featured-products">
                    <p>Belum ada produk unggulan yang tersedia.</p>
                </div>
            @endif
        </div>
        
        <!-- Mobile Carousel -->
        <div class="mobile-featured-carousel-container">
            <div class="mobile-featured-header">
                <h2>Produk Unggulan</h2>
            </div>
            <div class="mobile-featured-carousel-track">
                @if($featuredProducts->count() > 0)
                    @foreach($featuredProducts as $product)
                    <div class="mobile-featured-item">
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
                                    
                                    <div class="product-badge featured-badge">
                                        <i class="fas fa-star"></i> UNGGULAN
                                    </div>
                                </div>
                                
                                <div class="product-content">
                                    <h3 class="product-title">{{ $product->name }}</h3>
                                    <div class="product-rating">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $product->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="rating-number">({{ number_format($product->rating, 1) }})</span>
                                        </div>
                                        <span class="sold-count">{{ $product->sold_count ?? 0 }} terjual</span>
                                    </div>
                                    <div class="product-price">
                                        @if($product->old_price && $product->old_price > $product->price)
                                            <span class="old-price">Rp {{ number_format($product->old_price, 0, ',', '.') }}</span>
                                        @endif
                                        <span class="new-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-featured-products">
                        <p>Belum ada produk unggulan yang tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>


<!-- Banner Section 2 -->
<section class="banner-section-2">
    <div class="container">
        @if($bannersSection2->count() > 0)
        @if($bannersSection2->count() > 1)
            <!-- Banner Carousel for Section 2 -->
            <div class="banner-carousel-container">
                <button class="banner-carousel-btn prev-btn" onclick="moveBannerSection2Carousel(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="banner-carousel-wrapper">
                    <div class="banner-carousel-track" id="bannerSection2CarouselTrack">
                        @foreach($bannersSection2 as $banner)
                            <div class="banner-slide">
                                <div class="banner-image-16-9">
                                    @if($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}" 
                                             alt="{{ $banner->title }}" 
                                             class="banner-image">
                                    @else
                                        <div class="banner-placeholder-16-9">
                                            <i class="fas fa-coffee"></i>
                                            <p>Banner Image</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="banner-carousel-btn next-btn" onclick="moveBannerSection2Carousel(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <!-- Banner Indicators for Section 2 -->
                <div class="banner-indicators">
                    @foreach($bannersSection2 as $index => $banner)
                        <button class="banner-indicator {{ $index === 0 ? 'active' : '' }}" 
                                onclick="goToBannerSection2Slide({{ $index }})"></button>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Single Banner for Section 2 -->
            @foreach($bannersSection2 as $banner)
                <div class="banner-image-16-9">
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" 
                             alt="{{ $banner->title }}" 
                             class="banner-image">
                    @else
                        <div class="banner-placeholder-16-9">
                            <i class="fas fa-coffee"></i>
                            <p>Banner Image</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    @else
        <!-- Default Banner Placeholder for Section 2 -->
        <div class="banner-image-16-9">
            <div class="banner-placeholder-16-9">
                <i class="fas fa-coffee"></i>
                <p>Banner Section 2</p>
            </div>
        </div>
    @endif
    </div>
</section>

<!-- Produk Terbaru Section -->
<section class="produk-terbaru">
    <div class="container">
        <h2>Produk Terbaru</h2>
        <div class="produk-terbaru-carousel-container">
            <button class="produk-terbaru-btn prev-btn" onclick="moveProdukTerbaruCarousel(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="produk-terbaru-carousel-wrapper">
                <div class="produk-terbaru-carousel-track" id="produkTerbaruCarouselTrack">
                    @foreach($latestProducts as $product)
                    <div class="produk-terbaru-item">
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
                                    
                                    <div class="product-badge new-badge">
                                        <i class="fas fa-certificate"></i> NEW
                                    </div>
                                    
                                    @if($product->discount_price || $product->discount_percentage)
                                    <div class="product-badge sale-badge" style="right: 10px; left: auto;">
                                        -{{ $product->getDiscountPercentage() }}%
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
                    </div>
                    @endforeach
                </div>
            </div>
            <button class="produk-terbaru-btn next-btn" onclick="moveProdukTerbaruCarousel(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Articles Section -->
@if($articles->count() > 0)
<section class="articles-section">
    <div class="container">
        <div class="articles-header">
            <h2>Artikel</h2>
            <a href="{{ route('articles.index') }}" class="view-all-btn">
                <span>Lihat Semua</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="articles-grid">
            @foreach($articles->take(3) as $article)
                <div class="article-card" data-testid="article-{{ $article->id }}">
                    <div class="article-card-inner">
                        <a href="{{ route('articles.show', $article->id) }}" class="article-link">
                            <div class="article-image-wrapper">
                                @if($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                         alt="{{ $article->title }}" 
                                         class="article-image"
                                         loading="lazy">
                                @else
                                    <div class="article-placeholder">
                                        <i class="fas fa-newspaper"></i>
                                        <span>No Image</span>
                                    </div>
                                @endif
                                
                                <div class="article-overlay">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                            
                            <div class="article-content">
                                <h3 class="article-title">{{ $article->title }}</h3>
                                
                                <div class="article-meta">
                                    <div class="article-excerpt">
                                        {{ Str::limit($article->excerpt ?? Str::limit(strip_tags($article->content), 80), 80) }}
                                    </div>
                                    
                                    <div class="article-stats">
                                        <div class="article-date">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ $article->published_date ? \Carbon\Carbon::parse($article->published_date)->format('d M Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="article-views">
                                            <i class="fas fa-eye"></i>
                                            <span>{{ $article->views }} views</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <div class="article-actions" style="display: none;">
                            <a href="{{ route('articles.show', $article->id) }}" class="read-more-btn">
                                <i class="fas fa-arrow-right"></i>
                                <span>Baca Selengkapnya</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif



<script src="{{ asset('js/banner-carousel.js') }}"></script>
<script src="{{ asset('js/featured-products-carousel.js') }}"></script>
<script src="{{ asset('js/produk-terbaru-carousel.js') }}"></script>

<script>
// Fallback function for produk terbaru carousel
if (typeof moveProdukTerbaruCarousel === 'undefined') {
    function moveProdukTerbaruCarousel(direction) {
        console.log('Fallback moveProdukTerbaruCarousel called with direction:', direction);
        // Basic fallback implementation
        const track = document.getElementById('produkTerbaruCarouselTrack');
        if (!track) return;
        
        const currentTransform = track.style.transform || 'translateX(0px)';
        const currentX = parseInt(currentTransform.replace('translateX(', '').replace('px)', '')) || 0;
        const slideWidth = 320;
        const newX = currentX - (direction * slideWidth);
        
        track.style.transform = `translateX(${newX}px)`;
    }
}
</script>

<script>
// Flash Sale Timer Countdown
function startFlashSaleTimer() {
    // Set timer to 24 hours from now (or you can set a specific end time)
    const endTime = new Date().getTime() + (24 * 60 * 60 * 1000); // 24 hours from now
    
    function updateTimer() {
        const now = new Date().getTime();
        const timeLeft = endTime - now;
        
        if (timeLeft > 0) {
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        } else {
            // Timer expired
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
            
            // Optionally hide the flash sale section or show expired message
            const flashSaleSection = document.querySelector('.flash-sale');
            if (flashSaleSection) {
                flashSaleSection.innerHTML = '<div class="container text-center"><h2>🔥 Flash Sale Telah Berakhir</h2><p>Terima kasih atas partisipasi Anda!</p></div>';
            }
        }
    }
    
    // Update timer immediately
    updateTimer();
    
    // Update timer every second
    setInterval(updateTimer, 1000);
}

// Article Card Click Handler
function handleArticleClick(event) {
    const articleCard = event.currentTarget;
    const articleLink = articleCard.querySelector('.article-link');
    
    if (articleLink) {
        // Prevent default link behavior
        event.preventDefault();
        
        // Navigate to article detail
        window.location.href = articleLink.href;
    }
}

// Start timer when page loads
document.addEventListener('DOMContentLoaded', function() {
    startFlashSaleTimer();
    
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
    
    // Add click handlers to article cards
    const articleCards = document.querySelectorAll('.article-card');
    articleCards.forEach(card => {
        card.addEventListener('click', handleArticleClick);
    });
});
</script>

<style>
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

/* Brands Section */
.brands-section {
    padding: 60px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.brands-header {
    text-align: center;
    margin-bottom: 40px;
}

.brands-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.brands-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 500;
    margin: 0;
}

/* Brands Carousel */
.brands-carousel-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 60px;
}

.brands-carousel-wrapper {
    overflow: hidden;
    border-radius: 16px;
}

.brands-carousel-track {
    display: flex;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    gap: 20px;
}

.brand-item {
    flex: 0 0 calc(25% - 15px);
    min-width: 280px;
}

.brand-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-height: 200px;
    height: 100%;
}

.brand-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    border-color: #3498db;
    text-decoration: none;
    color: inherit;
}

.brand-image {
    width: 80px;
    height: 80px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.brand-card:hover .brand-image {
    transform: scale(1.1);
    border-color: #3498db;
}

.brand-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border-radius: 8px;
}

.brand-placeholder {
    font-size: 2rem;
    color: #6c757d;
}

.brand-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.brand-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    line-height: 1.3;
}

.brand-description {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 12px;
    line-height: 1.4;
}

.brand-products-count {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #3498db;
    font-weight: 500;
    background: rgba(52, 152, 219, 0.1);
    padding: 6px 12px;
    border-radius: 20px;
    border: 1px solid rgba(52, 152, 219, 0.2);
}

.brand-products-count i {
    font-size: 0.8rem;
}

/* Brands Carousel Buttons */
.brands-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid #dee2e6;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.brands-btn:hover {
    background: #3498db;
    border-color: #3498db;
    color: white;
    transform: translateY(-50%) scale(1.1);
}

.brands-btn.prev-btn {
    left: 10px;
}

.brands-btn.next-btn {
    right: 10px;
}

.brands-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: translateY(-50%);
}

.brands-btn:disabled:hover {
    background: rgba(255, 255, 255, 0.9);
    border-color: #dee2e6;
    color: inherit;
    transform: translateY(-50%);
}

/* Responsive Brands */
@media (max-width: 1024px) {
    .brand-item {
        flex: 0 0 calc(33.333% - 14px);
    }
    
    .brands-carousel-container {
        padding: 0 50px;
    }
}

@media (max-width: 768px) {
    .brands-section {
        padding: 40px 0;
    }
    
    .brands-header h2 {
        font-size: 2rem;
    }
    
    .brand-item {
        flex: 0 0 calc(50% - 10px);
        min-width: 250px;
    }
    
    .brands-carousel-container {
        padding: 0 40px;
    }
    
    .brands-btn {
        width: 40px;
        height: 40px;
    }
    
    .brand-card {
        padding: 20px;
        min-height: 180px;
    }
    
    .brand-image {
        width: 60px;
        height: 60px;
    }
    
    .brand-logo {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 480px) {
    .brand-item {
        flex: 0 0 100%;
        min-width: 100%;
    }
    
    .brands-carousel-container {
        padding: 0 30px;
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

/* Flash Sale Carousel JavaScript */
</style>

<script>
// Flash Sale Carousel
let currentFlashSaleSlide = 0;
const flashSaleTrack = document.getElementById('flashSaleCarouselTrack');
const flashSaleItems = document.querySelectorAll('.flash-sale-item, .flash-sale-header-item');
const totalSlides = flashSaleItems.length;

function moveFlashSaleCarousel(direction) {
    if (!flashSaleTrack) return;
    
    const itemsPerView = window.innerWidth <= 768 ? 1 : (window.innerWidth <= 1024 ? 2 : 4);
    const maxSlide = Math.max(0, totalSlides - itemsPerView);
    
    currentFlashSaleSlide += direction;
    
    if (currentFlashSaleSlide < 0) {
        currentFlashSaleSlide = 0;
    } else if (currentFlashSaleSlide > maxSlide) {
        currentFlashSaleSlide = maxSlide;
    }
    
    const translateX = -(currentFlashSaleSlide * (100 / itemsPerView));
    flashSaleTrack.style.transform = `translateX(${translateX}%)`;
    
    // Navigation buttons removed - using horizontal drag only
}

// Initialize carousel
document.addEventListener('DOMContentLoaded', function() {
    if (flashSaleTrack && flashSaleItems.length > 0) {
        const itemsPerView = window.innerWidth <= 768 ? 1 : (window.innerWidth <= 1024 ? 2 : 4);
        const maxSlide = Math.max(0, totalSlides - itemsPerView);
        
        // Navigation buttons removed - using horizontal drag only
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (flashSaleTrack && flashSaleItems.length > 0) {
        const itemsPerView = window.innerWidth <= 768 ? 1 : (window.innerWidth <= 1024 ? 2 : 4);
        const maxSlide = Math.max(0, totalSlides - itemsPerView);
        
        if (currentFlashSaleSlide > maxSlide) {
            currentFlashSaleSlide = maxSlide;
        }
        
        const translateX = -(currentFlashSaleSlide * (100 / itemsPerView));
        flashSaleTrack.style.transform = `translateX(${translateX}%)`;
        
        // Navigation buttons removed - using horizontal drag only
    }
});

/* Brands Carousel JavaScript */
let currentBrandsSlide = 0;
const brandsTrack = document.getElementById('brandsCarouselTrack');
const brandsItems = document.querySelectorAll('.brand-item');
const totalBrandsSlides = brandsItems.length;

function moveBrandsCarousel(direction) {
    if (!brandsTrack) return;
    
    const itemsPerView = window.innerWidth <= 480 ? 1 : (window.innerWidth <= 768 ? 2 : (window.innerWidth <= 1024 ? 3 : 4));
    const maxSlide = Math.max(0, totalBrandsSlides - itemsPerView);
    
    currentBrandsSlide += direction;
    
    if (currentBrandsSlide < 0) {
        currentBrandsSlide = 0;
    } else if (currentBrandsSlide > maxSlide) {
        currentBrandsSlide = maxSlide;
    }
    
    const translateX = -(currentBrandsSlide * (100 / itemsPerView));
    brandsTrack.style.transform = `translateX(${translateX}%)`;
    
    // Update button states
    const prevBtn = document.querySelector('.brands-btn.prev-btn');
    const nextBtn = document.querySelector('.brands-btn.next-btn');
    
    if (prevBtn) prevBtn.disabled = currentBrandsSlide === 0;
    if (nextBtn) nextBtn.disabled = currentBrandsSlide >= maxSlide;
}

// Initialize brands carousel
document.addEventListener('DOMContentLoaded', function() {
    if (brandsTrack && brandsItems.length > 0) {
        const itemsPerView = window.innerWidth <= 480 ? 1 : (window.innerWidth <= 768 ? 2 : (window.innerWidth <= 1024 ? 3 : 4));
        const maxSlide = Math.max(0, totalBrandsSlides - itemsPerView);
        
        // Update button states
        const prevBtn = document.querySelector('.brands-btn.prev-btn');
        const nextBtn = document.querySelector('.brands-btn.next-btn');
        
        if (prevBtn) prevBtn.disabled = true;
        if (nextBtn) nextBtn.disabled = maxSlide <= 0;
    }
});

// Handle window resize for brands carousel
window.addEventListener('resize', function() {
    if (brandsTrack && brandsItems.length > 0) {
        const itemsPerView = window.innerWidth <= 480 ? 1 : (window.innerWidth <= 768 ? 2 : (window.innerWidth <= 1024 ? 3 : 4));
        const maxSlide = Math.max(0, totalBrandsSlides - itemsPerView);
        
        if (currentBrandsSlide > maxSlide) {
            currentBrandsSlide = maxSlide;
        }
        
        const translateX = -(currentBrandsSlide * (100 / itemsPerView));
        brandsTrack.style.transform = `translateX(${translateX}%)`;
        
        // Update button states
        const prevBtn = document.querySelector('.brands-btn.prev-btn');
        const nextBtn = document.querySelector('.brands-btn.next-btn');
        
        if (prevBtn) prevBtn.disabled = currentBrandsSlide === 0;
        if (nextBtn) nextBtn.disabled = currentBrandsSlide >= maxSlide;
    }
});
</script>
@endsection