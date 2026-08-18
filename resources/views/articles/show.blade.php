@extends('layouts.app')

@section('title', $article->title . ' - Bennylovescoffee')

@section('content')
<div class="article-show-page">
    <div class="container">
        <!-- Article Header -->
        <div class="article-header">
            <div class="article-meta-info">
                <div class="article-date">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                </div>
                <div class="article-views">
                    <i class="fas fa-eye"></i>
                    <span>{{ $article->views }} views</span>
                </div>
                <div class="article-reading-time">
                    <i class="fas fa-clock"></i>
                    <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read</span>
                </div>
            </div>
            
            <h1 class="article-title">{{ $article->title }}</h1>
            
            @if($article->excerpt)
                <p class="article-excerpt">{{ $article->excerpt }}</p>
            @endif
        </div>

        <!-- Article Featured Image -->
        @if($article->featured_image)
            <div class="article-featured-image">
                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                     alt="{{ $article->title }}" 
                     class="featured-image">
            </div>
        @endif

        <!-- Article Content -->
        <div class="article-content">
            <div class="article-body">
                {!! $article->content !!}
            </div>
        </div>

        <!-- Article Tags -->
        <div class="article-tags">
            <h3>Tags</h3>
            <div class="tags-list">
                @php
                    $tags = ['Coffee', 'Brewing', 'Equipment', 'Tips', 'Guide', 'Review'];
                @endphp
                @foreach($tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
        </div>

        <!-- Recommended Products -->
        <div class="recommended-products">
            <div class="section-header">
                <h2>Produk Rekomendasi</h2>
                <p>Produk yang mungkin Anda sukai berdasarkan artikel ini</p>
            </div>
            
            <div class="products-grid">
                @php
                    // Get real recommended products from database
                    $recommendedProducts = \App\Models\Product::where('is_active', true)
                        ->with(['variants', 'brand'])
                        ->inRandomOrder()
                        ->take(4)
                        ->get();
                @endphp
                
                @foreach($recommendedProducts as $product)
                    <div class="product-card" data-testid="recommended-product-{{ $product->id }}">
                        <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                            <div class="product-image-container">
                                @if($product->main_photo)
                                    <img src="{{ asset('storage/' . $product->main_photo) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         loading="lazy">
                                @elseif($product->foto)
                                    <img src="{{ asset('storage/' . $product->foto) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         loading="lazy">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         loading="lazy">
                                @else
                                    <div class="product-placeholder">
                                        <i class="fas fa-coffee"></i>
                                    </div>
                                @endif
                                
                                @if($product->discount_price || $product->discount_percentage)
                                    <div class="product-badge">
                                        <span>-{{ $product->getDiscountPercentage() }}%</span>
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
                                    <span class="sold-count">{{ number_format($product->sold_count ?? 0) }} terjual</span>
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
                                
                                @auth
                                    <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <button type="submit" class="add-to-cart-btn">
                                            <i class="fas fa-plus"></i>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="add-to-cart-btn">
                                        <i class="fas fa-plus"></i>
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                @endauth
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Related Articles -->
        <div class="related-articles">
            <div class="section-header">
                <h2>Artikel Terkait</h2>
                <p>Artikel lain yang mungkin menarik untuk Anda</p>
            </div>
            
            <div class="articles-grid">
                @foreach($relatedArticles as $relatedArticle)
                    <div class="article-card" data-testid="related-article-{{ $relatedArticle->id }}">
                        <div class="article-card-inner">
                            <a href="{{ route('articles.show', $relatedArticle->id) }}" class="article-link">
                                <div class="article-image-wrapper">
                                    @if($relatedArticle->featured_image)
                                        <img src="{{ asset('storage/' . $relatedArticle->featured_image) }}" 
                                             alt="{{ $relatedArticle->title }}" 
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
                                    <h3 class="related-article-title">{{ $relatedArticle->title }}</h3>
                                    
                                    <div class="article-meta">
                                        <div class="article-excerpt">
                                            {{ Str::limit($relatedArticle->excerpt ?? Str::limit(strip_tags($relatedArticle->content), 80), 80) }}
                                        </div>
                                        
                                        <div class="article-stats">
                                            <div class="article-date">
                                                <i class="fas fa-calendar"></i>
                                                <span>{{ $relatedArticle->published_at ? $relatedArticle->published_at->format('d M Y') : $relatedArticle->created_at->format('d M Y') }}</span>
                                            </div>
                                            <div class="article-views">
                                                <i class="fas fa-eye"></i>
                                                <span>{{ $relatedArticle->views }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="article-actions">
                                <a href="{{ route('articles.show', $relatedArticle->id) }}" class="read-more-btn">
                                    <i class="fas fa-arrow-right"></i>
                                    <span>Baca Selengkapnya</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
/* Article Show Page Styles */
.article-show-page {
    padding: 40px 0;
    background: #f8f9fa;
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    box-sizing: border-box;
}

/* Article Header */
.article-header {
    text-align: left;
    margin-bottom: 3rem;
    padding: 2rem 0;
}

.article-meta-info {
    display: flex;
    justify-content: flex-start;
    gap: 2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.article-date,
.article-views,
.article-reading-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #636e72;
    font-size: 14px;
    font-weight: 500;
}

.article-date i,
.article-views i,
.article-reading-time i {
    color: #ff6b35;
    font-size: 12px;
}

.article-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0 0 1rem 0;
    line-height: 1.2;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.article-excerpt {
    font-size: 1.4rem;
    color: #636e72;
    margin: 0;
    font-weight: 300;
    max-width: 900px;
    text-align: left;
    line-height: 1.6;
}

/* Featured Image */
.article-featured-image {
    margin-bottom: 3rem;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.featured-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    object-position: center;
}

/* Article Content */
.article-content {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.article-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2d3436;
}

.article-body h1,
.article-body h2,
.article-body h3,
.article-body h4,
.article-body h5,
.article-body h6 {
    color: #2d3436;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.article-body h1 { font-size: 2.5rem; }
.article-body h2 { font-size: 2rem; }
.article-body h3 { font-size: 1.5rem; }

.article-body p {
    margin-bottom: 1.5rem;
}

.article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 1.5rem 0;
}

.article-body blockquote {
    border-left: 4px solid #ff6b35;
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #636e72;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0 10px 10px 0;
}

.article-body ul,
.article-body ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.article-body li {
    margin-bottom: 0.5rem;
}

/* Article Tags */
.article-tags {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.article-tags h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0 0 1.5rem 0;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.tag {
    background: linear-gradient(135deg, #ff6b35, #e55a2b);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.tag:hover {
    background: linear-gradient(135deg, #e55a2b, #d44a1a);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
    color: white;
    text-decoration: none;
}

/* Section Headers */
.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0 0 0.5rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.section-header p {
    color: #636e72;
    font-size: 1.1rem;
    margin: 0;
}

/* Recommended Products */
.recommended-products {
    margin-bottom: 4rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin-top: 2rem;
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
    flex: 1;
}

.product-image-wrapper {
    position: relative;
    height: 200px;
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
    font-size: 1rem;
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
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    font-weight: 500;
}

.new-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: #ff6b35;
    letter-spacing: -0.5px;
}

.product-rating {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
}

.stars {
    color: #ffc107;
    display: flex;
    gap: 2px;
}

.stars i {
    font-size: 10px;
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
    padding: 12px 16px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
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

/* Related Articles */
.related-articles {
    margin-bottom: 4rem;
}

.articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 2rem;
}

.article-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.article-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: #ff6b35;
}

.article-card-inner {
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.article-link {
    text-decoration: none;
    color: inherit;
    display: block;
    flex: 1;
}

.article-image-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.article-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.4s ease;
}

.article-card:hover .article-image {
    transform: scale(1.1);
}

.article-placeholder {
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

.article-placeholder span {
    font-size: 0.9rem;
    margin-top: 0.5rem;
    opacity: 0.8;
}

.article-overlay {
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

.article-card:hover .article-overlay {
    opacity: 1;
}

.article-overlay i {
    color: white;
    font-size: 2rem;
}

.article-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* .article-title conflict removed - using the one at line 309 */

/* Related Article Title - smaller size */
.related-article-title {
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

.article-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: auto;
}

.article-excerpt {
    color: #636e72;
    font-size: 14px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.article-stats {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: #636e72;
}

.article-date,
.article-views {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.article-date i,
.article-views i {
    font-size: 10px;
}

.article-actions {
    padding: 1.5rem;
    padding-top: 0;
}

.read-more-btn {
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

.read-more-btn:hover {
    background: linear-gradient(135deg, #e55a2b, #d44a1a);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
    color: white;
    text-decoration: none;
}

.read-more-btn:active {
    transform: translateY(0);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .articles-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .article-show-page {
        padding: 20px 0;
    }
    
    .container {
        padding: 0 1rem;
    }
    
    .article-header {
        margin-bottom: 2rem;
        padding: 1rem 0;
    }
    
    .article-title {
        font-size: 1.8rem;
        line-height: 1.3;
        margin-bottom: 0.8rem;
    }
    
    .article-excerpt {
        font-size: 1.1rem;
        line-height: 1.5;
    }
    
    .article-meta-info {
        flex-direction: column;
        gap: 0.8rem;
        margin-bottom: 1rem;
    }
    
    .article-date,
    .article-views,
    .article-reading-time {
        font-size: 13px;
        gap: 0.4rem;
    }
    
    .article-featured-image {
        margin-bottom: 2rem;
        border-radius: 15px;
    }
    
    .featured-image {
        height: 250px;
    }
    
    .article-content {
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: 15px;
    }
    
    .article-body {
        font-size: 1rem;
        line-height: 1.6;
    }
    
    .article-body h1 { font-size: 1.8rem; }
    .article-body h2 { font-size: 1.5rem; }
    .article-body h3 { font-size: 1.3rem; }
    
    .article-tags {
        padding: 1.2rem;
        margin-bottom: 2rem;
        border-radius: 15px;
    }
    
    .article-tags h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
    
    .tag {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 15px;
    }
    
    .section-header h2 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }
    
    .section-header p {
        font-size: 1rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.4rem;
        margin-top: 1.5rem;
        padding: 0 0.2rem;
    }
    
    /* Product Card - Match with /products page exactly */
    .product-card {
        background: linear-gradient(135deg, #ffffff, #f8f8f8);
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #000000;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        border-color: #333333;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #f8f8f8, #ffffff);
    }
    
    .product-image-container {
        position: relative;
        height: 200px;
        width: 100%;
        background: #f8f8f8;
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1/1;
        overflow: hidden;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(135deg, #333, #555);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        object-fit: cover;
    }
    
    .product-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #e74c3c;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .product-content {
        padding: 12px;
        background: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .product-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3436;
        margin-bottom: 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.85rem;
    }
    
    .stars {
        color: #ffc107;
        display: flex;
        gap: 2px;
    }
    
    .stars i {
        font-size: 0.8rem;
    }
    
    .rating-number {
        color: #666;
        margin-left: 4px;
        font-size: 0.75rem;
    }
    
    .sold-count {
        color: #636e72;
        font-size: 0.75rem;
    }
    
    .product-price {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .old-price {
        font-size: 0.75rem;
        color: #999;
        text-decoration: line-through;
    }
    
    .new-price {
        font-size: 1rem;
        font-weight: 700;
        color: #e74c3c;
    }
    
    .add-to-cart-btn {
        width: 100%;
        background: #000000;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        margin-top: 8px;
    }
    
    .add-to-cart-btn:hover {
        background: #374151;
        transform: translateY(-1px);
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .article-card {
        border-radius: 15px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .article-image-wrapper {
        height: 180px;
        border-radius: 15px 15px 0 0;
        overflow: hidden;
    }
    
    .article-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .article-card:hover .article-image {
        transform: scale(1.05);
    }
    
    .article-content {
        padding: 1.2rem;
        background: white;
        border-radius: 0 0 15px 15px;
    }
    
    .related-article-title {
        font-size: 1rem;
        line-height: 1.3;
        margin-bottom: 0.8rem;
        color: #2d3436;
        font-weight: 600;
    }
    
    .article-excerpt {
        font-size: 0.9rem;
        line-height: 1.4;
        color: #636e72;
        margin-bottom: 1rem;
    }
    
    .article-stats {
        font-size: 11px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .article-date,
    .article-views {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        color: #636e72;
    }
    
    .article-date i,
    .article-views i {
        font-size: 10px;
        color: #ff6b35;
    }
    
    /* Mobile Navigation Fix */
    body {
        padding-top: 50px !important;
    }
    
    .nav-container {
        padding: 0.5rem 1rem !important;
    }
    
    .mobile-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #dee2e6;
        z-index: 1000;
    }
    
    /* Mobile Footer Fix */
    .footer {
        margin-bottom: 60px !important;
    }
}

@media (max-width: 480px) {
    .article-show-page {
        padding: 15px 0;
    }
    
    .container {
        padding: 0 0.8rem;
    }
    
    .article-header {
        margin-bottom: 1.5rem;
        padding: 0.8rem 0;
    }
    
    .article-title {
        font-size: 1.5rem;
        line-height: 1.3;
        margin-bottom: 0.6rem;
    }
    
    .article-excerpt {
        font-size: 1rem;
        line-height: 1.4;
    }
    
    .article-meta-info {
        gap: 0.6rem;
        margin-bottom: 0.8rem;
    }
    
    .article-date,
    .article-views,
    .article-reading-time {
        font-size: 12px;
        gap: 0.3rem;
    }
    
    .article-featured-image {
        margin-bottom: 1.5rem;
        border-radius: 12px;
    }
    
    .featured-image {
        height: 200px;
    }
    
    .article-content {
        padding: 1.2rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
    }
    
    .article-body {
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .article-body h1 { font-size: 1.5rem; }
    .article-body h2 { font-size: 1.3rem; }
    .article-body h3 { font-size: 1.1rem; }
    
    .article-tags {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
    }
    
    .article-tags h3 {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
    }
    
    .tag {
        padding: 5px 10px;
        font-size: 11px;
        border-radius: 12px;
    }
    
    .section-header h2 {
        font-size: 1.5rem;
        margin-bottom: 0.4rem;
    }
    
    .section-header p {
        font-size: 0.9rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.3rem;
        margin-top: 1.2rem;
        padding: 0 0.1rem;
    }
    
    /* Product Card - Match with /products page exactly for mobile */
    .product-card {
        background: linear-gradient(135deg, #ffffff, #f8f8f8);
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #000000;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .product-card:hover {
        transform: translateY(-3px);
        border-color: #333333;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #f8f8f8, #ffffff);
    }
    
    .product-image-container {
        position: relative;
        height: 160px;
        width: 100%;
        background: #f8f8f8;
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1/1;
        overflow: hidden;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.03);
    }
    
    .product-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(135deg, #333, #555);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        object-fit: cover;
    }
    
    .product-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #e74c3c;
        color: white;
        padding: 3px 6px;
        border-radius: 8px;
        font-size: 0.6rem;
        font-weight: 600;
        z-index: 2;
    }
    
    .product-content {
        padding: 10px;
        background: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .product-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2d3436;
        margin-bottom: 6px;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 0.7rem;
    }
    
    .stars {
        color: #ffc107;
        display: flex;
        gap: 1px;
    }
    
    .stars i {
        font-size: 0.65rem;
    }
    
    .rating-number {
        color: #666;
        margin-left: 3px;
        font-size: 0.6rem;
    }
    
    .sold-count {
        color: #636e72;
        font-size: 0.6rem;
    }
    
    .product-price {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }
    
    .old-price {
        font-size: 0.65rem;
        color: #999;
        text-decoration: line-through;
    }
    
    .new-price {
        font-size: 0.85rem;
        font-weight: 700;
        color: #e74c3c;
    }
    
    .add-to-cart-btn {
        width: 100%;
        background: #000000;
        color: white;
        border: none;
        padding: 6px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        margin-top: 6px;
    }
    
    .add-to-cart-btn:hover {
        background: #374151;
        transform: translateY(-1px);
    }
    
    .articles-grid {
        grid-template-columns: 1fr;
        gap: 0.8rem;
        margin-top: 1.2rem;
    }
    
    .article-card {
        border-radius: 12px;
        margin-bottom: 0.8rem;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .article-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    
    .article-image-wrapper {
        height: 160px;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
    }
    
    .article-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .article-card:hover .article-image {
        transform: scale(1.03);
    }
    
    .article-content {
        padding: 1rem;
        background: white;
        border-radius: 0 0 12px 12px;
    }
    
    .related-article-title {
        font-size: 0.9rem;
        line-height: 1.2;
        margin-bottom: 0.6rem;
        color: #2d3436;
        font-weight: 600;
    }
    
    .article-excerpt {
        font-size: 0.8rem;
        line-height: 1.3;
        color: #636e72;
        margin-bottom: 0.8rem;
    }
    
    .article-stats {
        font-size: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .article-date,
    .article-views {
        display: flex;
        align-items: center;
        gap: 0.2rem;
        color: #636e72;
    }
    
    .article-date i,
    .article-views i {
        font-size: 9px;
        color: #ff6b35;
    }
    
    /* Mobile Navigation Fix */
    body {
        padding-top: 50px !important;
    }
    
    .nav-container {
        padding: 0.5rem 1rem !important;
    }
    
    .mobile-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #dee2e6;
        z-index: 1000;
    }
    
    /* Mobile Footer Fix */
    .footer {
        margin-bottom: 60px !important;
    }
}
</style>
@endsection
