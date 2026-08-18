@extends('layouts.app')

@section('title', 'Artikel - Bennylovescoffee')

@section('content')
<div class="articles-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Artikel</h1>
        </div>

        <!-- Results Info -->
        <div class="results-info">
            <p>Menampilkan {{ $articles->count() }} dari {{ $articles->total() }} artikel</p>
        </div>

        <!-- Articles Grid -->
        @if($articles->count() > 0)
            <div class="articles-grid">
                @foreach($articles as $article)
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
                                            {{ Str::limit($article->excerpt ?? Str::limit(strip_tags($article->content), 120), 120) }}
                                        </div>
                                        
                                        <div class="article-stats">
                                            <div class="article-date">
                                                <i class="fas fa-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($article->published_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="article-views">
                                                <i class="fas fa-eye"></i>
                                                <span>{{ $article->views }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="article-actions">
                                <a href="{{ route('articles.show', $article->id) }}" class="read-more-btn">
                                    <i class="fas fa-arrow-right"></i>
                                    <span>Baca Selengkapnya</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    <p>Menampilkan {{ $articles->firstItem() ?? 0 }} sampai {{ $articles->lastItem() ?? 0 }} dari {{ $articles->total() }} artikel</p>
                </div>
                <div class="pagination-controls">
                    {{ $articles->links('pagination.custom') }}
                </div>
            </div>
        @else
            <div class="no-articles">
                <div class="no-articles-content">
                    <i class="fas fa-newspaper"></i>
                    <h3>Tidak ada artikel ditemukan</h3>
                    <p>Coba ubah pencarian Anda</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Articles Page Styles */
.articles-page {
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

/* Articles Grid */
.articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-bottom: 3rem;
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
}

/* Ensure mobile grid overrides desktop */
@media (max-width: 768px) {
    .articles-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
        padding: 0 8px !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
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

.article-title {
    font-size: 1.2rem;
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
    gap: 1rem;
    margin-top: auto;
}

.article-excerpt {
    color: #636e72;
    font-size: 14px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 3;
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

/* No Articles */
.no-articles {
    text-align: center;
    padding: 4rem 2rem;
}

.no-articles-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-articles-content i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-articles-content h3 {
    font-size: 1.5rem;
    color: #2d3436;
    margin-bottom: 0.5rem;
}

.no-articles-content p {
    color: #636e72;
    margin-bottom: 2rem;
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
    .articles-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 0 8px; /* Reduced padding for mobile */
    }
    
    .page-header {
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .articles-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
        padding: 0 8px !important; /* Ensure proper spacing */
        display: grid !important;
    }
    
    .article-card {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .article-image-wrapper {
        height: 140px; /* Compact height for 2-column layout */
    }
    
    .article-content {
        padding: 12px;
    }
    
    .article-title {
        font-size: 13px;
        line-height: 1.3;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .article-excerpt {
        font-size: 11px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 8px;
    }
    
    .article-stats {
        font-size: 10px;
        margin-bottom: 8px;
    }
    
    .read-more-btn {
        padding: 8px 12px;
        font-size: 11px;
        border-radius: 6px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 4px; /* Minimal padding for small screens */
    }
    
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .articles-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
        padding: 0 4px !important; /* Minimal padding */
        display: grid !important;
    }
    
    .article-card {
        margin-bottom: 0.8rem;
    }
    
    .article-image-wrapper {
        height: 120px;
    }
    
    .article-content {
        padding: 10px;
    }
    
    .article-title {
        font-size: 12px;
        line-height: 1.2;
        margin-bottom: 6px;
    }
    
    .article-excerpt {
        font-size: 10px;
        line-height: 1.2;
        margin-bottom: 6px;
    }
    
    .article-stats {
        font-size: 9px;
        margin-bottom: 6px;
    }
    
    .read-more-btn {
        padding: 6px 10px;
        font-size: 10px;
    }
    
    .article-actions {
        padding: 1rem;
        padding-top: 0;
    }
    
    .read-more-btn {
        padding: 10px 14px;
        font-size: 12px;
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
