<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Bennylovescoffee Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/admin-featured-products.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #2d2d2d 100%);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            overflow: hidden;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        /* Sidebar Group Styling */
        .sidebar-group {
            margin-bottom: 2rem;
        }
        
        .sidebar-group-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            padding: 0 1.5rem;
        }
        
        .sidebar-group-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.6);
            margin-left: 0.5rem;
        }
        
        .sidebar-group-icon {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Sidebar Navigation Links */
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 1.5rem;
            border-radius: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .sidebar-nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar-nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .sidebar-nav-link.active::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #3b82f6;
            border-radius: 0 2px 2px 0;
        }
        
        .sidebar-nav-icon {
            font-size: 1rem;
            margin-right: 0.75rem;
            transition: transform 0.3s ease;
        }
        
        .sidebar-nav-link:hover .sidebar-nav-icon {
            transform: scale(1.1);
        }
        
        /* Sales Group Specific Styling */
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) {
            border-left: 3px solid #10b981;
            margin-left: -1.5rem;
            padding-left: 1.5rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-group-header {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-group-icon {
            color: #10b981;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-group-title {
            color: #10b981;
            font-weight: 700;
        }
        
        /* Sales Group Navigation Links */
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-nav-link {
            margin-left: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-nav-link:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border-left: 3px solid #10b981;
            transform: translateX(4px);
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-nav-link.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-left: 3px solid #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Sales")) .sidebar-nav-link.active::before {
            background: #ffffff;
        }
        
        /* Products Group Specific Styling */
        .sidebar-group:has(.sidebar-group-title:contains("Products")) {
            border-left: 3px solid #3b82f6;
            margin-left: -1.5rem;
            padding-left: 1.5rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-group-header {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-group-icon {
            color: #3b82f6;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-group-title {
            color: #3b82f6;
            font-weight: 700;
        }
        
        /* Products Group Navigation Links */
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-nav-link {
            margin-left: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-nav-link:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
            border-left: 3px solid #3b82f6;
            transform: translateX(4px);
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-left: 3px solid #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Products")) .sidebar-nav-link.active::before {
            background: #ffffff;
        }
        
        /* Overview Group Specific Styling */
        .sidebar-group:has(.sidebar-group-title:contains("Overview")) {
            border-left: 3px solid #8b5cf6;
            margin-left: -1.5rem;
            padding-left: 1.5rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Overview")) .sidebar-group-header {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(139, 92, 246, 0.05) 100%);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Overview")) .sidebar-group-icon {
            color: #8b5cf6;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Overview")) .sidebar-group-title {
            color: #8b5cf6;
            font-weight: 700;
        }
        
        /* Content Group Specific Styling */
        .sidebar-group:has(.sidebar-group-title:contains("Content")) {
            border-left: 3px solid #f59e0b;
            margin-left: -1.5rem;
            padding-left: 1.5rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Content")) .sidebar-group-header {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Content")) .sidebar-group-icon {
            color: #f59e0b;
        }
        
        .sidebar-group:has(.sidebar-group-title:contains("Content")) .sidebar-group-title {
            color: #f59e0b;
            font-weight: 700;
        }
        
        /* Sidebar Scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Main Content Area */
        .admin-main-content {
            margin-left: 16rem;
            min-height: 100vh;
            background: #f8fafc;
        }
        
        /* Touch-friendly horizontal table scrolling */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }

        /* Mobile/Tablet/Landscape-phone elements (matches sidebar breakpoint) */
        .mobile-only {
            display: none;
        }

        @media (max-width: 1024px), (max-height: 500px) and (orientation: landscape) {
            .mobile-only {
                display: inline-flex;
            }
        }

        /* Dark backdrop behind mobile sidebar */
        #sidebarBackdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 999;
            display: none;
        }

        #sidebarBackdrop.show {
            display: block;
        }

        .admin-main-content h1,
        .admin-main-content h2,
        .admin-main-content h3 {
            text-align: left;
        }
        
        .admin-main-content .text-left {
            text-align: left !important;
        }
        
        /* Force left alignment for all content */
        .admin-main-content * {
            text-align: left;
        }

        /* Ensure form controls enforce explicit LTR and native cursor handling */
        input, textarea, select {
            direction: ltr !important;
            text-align: left !important;
            unicode-bidi: normal !important;
            writing-mode: horizontal-tb !important;
        }
        
        .admin-main-content .text-center {
            text-align: center !important;
        }
        
        .admin-main-content .text-right {
            text-align: right !important;
        }
        
        /* Specific content alignment */
        .admin-main-content h1,
        .admin-main-content h2,
        .admin-main-content h3,
        .admin-main-content h4,
        .admin-main-content h5,
        .admin-main-content h6 {
            text-align: left !important;
        }
        
        .admin-main-content p,
        .admin-main-content div,
        .admin-main-content span {
            text-align: left !important;
        }
        
        /* Sidebar Header */
        .sidebar-header {
            flex-shrink: 0;
            padding: 1.5rem;
        }
        
        /* Sidebar Content Container */
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 0 1.5rem;
            margin-bottom: 1rem;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            flex-shrink: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            margin-top: auto;
        }
        
        .sidebar-footer-content {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
            backdrop-filter: blur(10px);
        }
        
        /* Ensure content is always above footer */
        .sidebar-content {
            min-height: 0;
        }
        
        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Responsive Sidebar for Mobile, Tablet, and Landscape Phones */
        @media (max-width: 1024px), (max-height: 500px) and (orientation: landscape) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-main-content {
                margin-left: 0;
            }
        }
        .admin-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        @media (max-width: 640px) {
            .admin-card {
                border-radius: 12px;
            }
        }
        .admin-btn {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }
        .admin-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .futuristic-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .futuristic-input:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 1);
        }
        .photo-upload-area {
            border: 2px dashed #1a1a1a;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .photo-upload-area:hover {
            border-color: #2d2d2d;
            background: rgba(0, 0, 0, 0.05);
        }
        .photo-preview {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .photo-preview:hover {
            transform: scale(1.05);
        }
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border: 2px solid transparent !important;
            border-radius: 12px !important;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
        }
        .select2-container--default .select2-selection--single:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 46px !important;
            padding-left: 16px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .discount-calculator {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 12px;
            padding: 16px;
        }
        .price-display {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
        }
        
        /* Featured Products Specific Styles */
        .featured-products-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: calc(100vh - 200px);
        }
        .featured-product-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .featured-product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        .featured-product-image {
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .featured-product-image:hover {
            transform: scale(1.05);
        }
        .featured-status-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .featured-status-badge.inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .featured-search-input {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .featured-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
            background: rgba(255, 255, 255, 1);
        }
        .featured-action-btn {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .featured-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .featured-action-btn.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        .featured-action-btn.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .featured-empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        .featured-empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }
        .featured-sortable-item {
            cursor: move;
            transition: all 0.3s ease;
        }
        .featured-sortable-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }
        .featured-sortable-item.sorting {
            opacity: 0.5;
            transform: scale(0.95);
        }
        .featured-bulk-actions {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .featured-product-select {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid transparent;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .featured-product-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="admin-sidebar text-white">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="flex items-center justify-between space-x-3">
                    <div class="flex items-center space-x-3 min-w-0">
                        <img src="{{ asset('images/icon.png') }}" alt="Bennylovescoffee Icon" class="w-8 h-8 object-contain flex-shrink-0">
                        <h1 class="text-xl font-bold truncate">Bennylovescoffee Admin</h1>
                    </div>
                    <button type="button" id="sidebarClose" class="mobile-only items-center justify-center w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 text-white" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Sidebar Content -->
            <div class="sidebar-content">
                <nav>
                    <!-- Dashboard Group -->
                    <div class="sidebar-group">
                        <div class="sidebar-group-header">
                            <i class="fas fa-tachometer-alt sidebar-group-icon"></i>
                            <span class="sidebar-group-title">Overview</span>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt sidebar-nav-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- Products Group -->
                    <div class="sidebar-group">
                        <div class="sidebar-group-header">
                            <i class="fas fa-box sidebar-group-icon"></i>
                            <span class="sidebar-group-title">Products</span>
                        </div>
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags sidebar-nav-icon"></i>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('admin.brands.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <i class="fas fa-award sidebar-nav-icon"></i>
                            <span>Brands</span>
                        </a>
                        <a href="{{ route('admin.products') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                            <i class="fas fa-box sidebar-nav-icon"></i>
                            <span>Products</span>
                        </a>
                        <a href="{{ route('admin.featured-products.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.featured-products.*') ? 'active' : '' }}">
                            <i class="fas fa-star sidebar-nav-icon"></i>
                            <span>Featured Products</span>
                        </a>
                        <a href="{{ route('admin.flash-sales.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.flash-sales.*') ? 'active' : '' }}">
                            <i class="fas fa-bolt sidebar-nav-icon"></i>
                            <span>Flash Sales</span>
                        </a>
                    </div>

                    <!-- Sales Group -->
                    <div class="sidebar-group">
                        <div class="sidebar-group-header">
                            <i class="fas fa-chart-line sidebar-group-icon"></i>
                            <span class="sidebar-group-title">Sales</span>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart sidebar-nav-icon"></i>
                            <span>Orders</span>
                        </a>
                        <a href="{{ route('admin.clients.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                            <i class="fas fa-users sidebar-nav-icon"></i>
                            <span>Clients</span>
                        </a>
                        <a href="{{ route('admin.chat.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                            <i class="fas fa-comments sidebar-nav-icon"></i>
                            <span>Chat</span>
                        </a>
                    </div>

                    <!-- Content Group -->
                    <div class="sidebar-group">
                        <div class="sidebar-group-header">
                            <i class="fas fa-newspaper sidebar-group-icon"></i>
                            <span class="sidebar-group-title">Content</span>
                        </div>
                        <a href="{{ route('admin.articles.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                            <i class="fas fa-newspaper sidebar-nav-icon"></i>
                            <span>Articles</span>
                        </a>
                        <a href="{{ route('admin.banners.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                            <i class="fas fa-image sidebar-nav-icon"></i>
                            <span>Main Banners</span>
                        </a>
                        <a href="{{ route('admin.banner-section2.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.banner-section2.*') ? 'active' : '' }}">
                            <i class="fas fa-images sidebar-nav-icon"></i>
                            <span>Promo Banners</span>
                        </a>
                    </div>
                    
                    <!-- Settings Group -->
                    <div class="sidebar-group">
                        <div class="sidebar-group-header">
                            <i class="fas fa-cog sidebar-group-icon"></i>
                            <span class="sidebar-group-title">Settings</span>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                            <i class="fas fa-user-cog sidebar-nav-icon"></i>
                            <span>Profile & Password</span>
                        </a>
                    </div>
                </nav>
            </div>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="sidebar-footer-content">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="text-xs text-gray-300">Super Admin</div>
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar backdrop -->
        <div id="sidebarBackdrop"></div>

        <!-- Main Content -->
        <div class="admin-main-content flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex flex-wrap items-center justify-between gap-3 px-3 sm:px-4 py-2.5 sm:py-3">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <button type="button" id="sidebarToggle" class="sidebar-toggle mobile-only items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 flex-shrink-0" aria-label="Open menu">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="text-left min-w-0">
                            <h2 class="text-lg sm:text-2xl font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
                            @if(isset($page_subtitle))
                            <p class="text-sm text-gray-600 mt-1 truncate">@yield('page-subtitle', '')</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                        <a href="{{ route('home') }}" target="_blank" class="admin-btn text-white px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base whitespace-nowrap">
                            <i class="fas fa-external-link-alt sm:mr-2"></i><span class="hidden sm:inline">View Store</span><span class="sm:hidden">Store</span>
                        </a>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-2 sm:p-3 md:p-4">
                <div class="w-full px-1 sm:px-2">
                    <div class="text-left">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Ensure sidebar content is properly sized
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.admin-sidebar');
            const sidebarContent = document.querySelector('.sidebar-content');
            const sidebarFooter = document.querySelector('.sidebar-footer');
            
            // Calculate available height for content
            function adjustSidebarContent() {
                if (sidebar && sidebarContent && sidebarFooter) {
                    const sidebarHeight = sidebar.offsetHeight;
                    const headerHeight = document.querySelector('.sidebar-header').offsetHeight;
                    const footerHeight = sidebarFooter.offsetHeight;
                    const availableHeight = sidebarHeight - headerHeight - footerHeight;
                    
                    sidebarContent.style.maxHeight = availableHeight + 'px';
                }
            }
            
            // Initial adjustment
            adjustSidebarContent();
            
            // Adjust on window resize
            window.addEventListener('resize', adjustSidebarContent);
        });
        
        // Mobile sidebar toggle
        function setSidebarOpen(open) {
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar) return;
            sidebar.classList.toggle('open', open);
            if (backdrop) backdrop.classList.toggle('show', open);
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            setSidebarOpen(!sidebar.classList.contains('open'));
        }

        document.addEventListener('click', function(event) {
            if (event.target.closest('#sidebarToggle')) {
                event.stopPropagation();
                toggleSidebar();
            } else if (event.target.closest('#sidebarBackdrop') || event.target.closest('#sidebarClose')) {
                setSidebarOpen(false);
            }
        });
        
        // Check if viewport is in drawer mode (mobile, tablet, or phone landscape)
        function isDrawerMode() {
            return window.innerWidth <= 1024 || (window.innerHeight <= 500 && window.innerWidth > window.innerHeight);
        }

        // Close sidebar when clicking outside on mobile / drawer mode
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.admin-sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            
            if (isDrawerMode()) {
                if (!sidebar.contains(event.target) && !sidebarToggle?.contains(event.target)) {
                    setSidebarOpen(false);
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (!isDrawerMode()) {
                setSidebarOpen(false);
            }
        });
    </script>
    
</body>
</html>
