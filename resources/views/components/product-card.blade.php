@props(['product'])

<article class="product-card" data-testid="product-card-{{ $product->id }}">
    <a href="{{ route('products.show', $product->slug) }}" class="product-link">
        <div class="product-image-container">
            @if($product->main_photo)
                <img src="{{ asset('storage/' . $product->main_photo) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" decoding="async">
            @elseif($product->foto)
                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" decoding="async">
            @elseif($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" decoding="async">
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
            
            
            @if($product->brand)
            <div class="product-brand">
                <i class="fas fa-award"></i>
                {{ $product->brand->name }}
            </div>
            @endif
            
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
</article>