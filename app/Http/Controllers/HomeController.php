<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Article;
use App\Models\FlashSale;
use App\Models\Banner;
use App\Models\BannerSection2;
use App\Models\FeaturedProduct;
use App\Models\Brand;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Removed auth middleware to allow public access to home page
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get categories
        $categories = Category::where('is_active', true)->get();
        
        // Get featured products from FeaturedProduct table
        $featuredProducts = Product::where('products.is_active', true)
            ->whereHas('featuredProduct', function($query) {
                $query->currentlyFeatured();
            })
            ->with(['featuredProduct', 'variants', 'brand'])
            ->join('featured_products', 'products.id', '=', 'featured_products.product_id')
            ->orderBy('featured_products.sort_order', 'asc')
            ->orderBy('featured_products.created_at', 'desc')
            ->select('products.*')
            ->take(5)
            ->get();
        
        // Get latest products
        $latestProducts = Product::where('is_active', true)
            ->with(['variants', 'brand'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // Get active flash sales with items
        $flashSales = FlashSale::active()
            ->with(['items.product'])
            ->orderBy('end_time', 'asc')
            ->take(2)
            ->get();
        
        // Get published articles
        $articles = Article::where('is_active', true)
            ->orderBy('published_date', 'desc')
            ->take(6)
            ->get();
        
        // Get active banners for hero section
        $banners = Banner::active()
            ->bySection('hero')
            ->ordered()
            ->get();
        
        // Get active banners for section 2 (new table)
        $bannersSection2 = BannerSection2::active()
            ->ordered()
            ->get();
        
        // Get active brands
        $brands = Brand::where('is_active', true)
            ->withCount('products')
            ->orderBy('name', 'asc')
            ->take(6)
            ->get();
        
        return view('home', compact(
            'categories', 
            'featuredProducts', 
            'latestProducts', 
            'flashSales', 
            'articles', 
            'banners',
            'bannersSection2',
            'brands'
        ));
    }
}
