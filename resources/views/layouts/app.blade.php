<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bennylovescoffee - Premium Coffee Equipment')</title>
    
    <!-- PWA & Mobile Meta Tags -->
    <meta name="theme-color" content="#2c1810">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="BennyCoffee">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home-products.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit; gap: 0.5rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="Benny Loves Coffee Logo" style="height: 40px;">
                </a>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
                </li>
            </ul>

            <div class="nav-search">
                <form action="{{ route('products.index') }}" method="GET" class="search-container">
                    <input type="text" name="search" placeholder="Cari produk..." class="search-input" value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <div class="nav-auth">
                @auth
                    <div class="user-dropdown">
                        <button class="user-btn" onclick="toggleUserDropdown()">
                            <div class="user-info">
                                <i class="fas fa-user-circle"></i>
                                <span class="user-name">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </div>
                        </button>
                        <div class="user-dropdown-menu" id="userDropdown">
                            <div class="dropdown-header">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-details">
                                    <div class="user-name">{{ auth()->user()->name }}</div>
                                    <div class="user-email">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-links">
                                @if(auth()->user()->isSuperadmin())
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-link">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Admin Dashboard
                                    </a>
                                @endif
                                <a href="{{ route('orders.index') }}" class="dropdown-link">
                                    <i class="fas fa-shopping-bag"></i>
                                    My Orders
                                </a>
                                <a href="{{ route('messages.index') }}" class="dropdown-link">
                                    <i class="fas fa-comments"></i>
                                    Chat
                                    <span class="chat-badge" id="chatBadge" style="display: none;"></span>
                                </a>
                                <a href="{{ route('cart.index') }}" class="dropdown-link">
                                    <i class="fas fa-shopping-cart"></i>
                                    My Cart
                                    <span class="cart-badge">{{ auth()->user()->carts()->count() }}</span>
                                </a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="dropdown-logout">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="auth-btn login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="auth-btn register-btn">
                            <i class="fas fa-user-plus"></i>
                            Register
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Bennylovescoffee</h3>
                <p style="text-align: justify;">Benny Loves Coffee adalah distributor alat kopi terkemuka di Indonesia, menghadirkan produk premium dari brand internasional seperti Timemore, Kinu, 1Zpresso, dan Mischief. Kami berkomitmen menyediakan layanan terbaik bagi pelanggan retail dan bisnis, memberikan kemudahan, kepercayaan, dan pengalaman berbelanja yang memuaskan untuk setiap kebutuhan kopi Anda.</p>
            </div>
            
            <div class="footer-section" style="text-align: right; margin-left: auto;">
                <h4>Contact</h4>
                <p>bennylovescoffee@gmail.com</p>
                <p>+62 812-3288-940</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Bennylovescoffee. All rights reserved.</p>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    @if(config('whatsapp.enabled', true))
    <div class="whatsapp-float">
        <a href="https://wa.me/{{ config('whatsapp.phone_number', '6281234567890') }}?text={{ urlencode(config('whatsapp.message', 'Halo, saya tertarik dengan produk Bennylovescoffee!')) }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="whatsapp-btn"
           title="Chat dengan kami via WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
    @endif

    <script src="{{ asset('js/script.js') }}?v={{ time() }}&r={{ rand(1000, 9999) }}"></script>
    <script>
        function toggleMobileAccount() {
            const mobileAccountMenu = document.getElementById('mobileAccountMenu');
            const mobileAccountArrow = document.querySelector('.mobile-account-arrow');

            if (!mobileAccountMenu) return;

            if (mobileAccountMenu.classList.contains('show')) {
                mobileAccountMenu.classList.remove('show');
                if (mobileAccountArrow) mobileAccountArrow.style.transform = 'rotate(0deg)';
            } else {
                mobileAccountMenu.classList.add('show');
                if (mobileAccountArrow) mobileAccountArrow.style.transform = 'rotate(180deg)';
            }
        }


        // Fetch unread chat message count
        @auth
        async function fetchUnreadChatCount() {
            try {
                const response = await fetch('{{ route('messages.unreadCount') }}');
                const data = await response.json();
                const badge = document.getElementById('chatBadge');
                const mobileBadge = document.getElementById('mobileChatBadge');
                
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-block';
                    }
                    if (mobileBadge) {
                        mobileBadge.textContent = data.count;
                        mobileBadge.style.display = 'inline-block';
                    }
                } else {
                    if (badge) badge.style.display = 'none';
                    if (mobileBadge) mobileBadge.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        }

        // Fetch on load and every 10 seconds
        fetchUnreadChatCount();
        setInterval(fetchUnreadChatCount, 10000);
        @endauth

        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const userBtn = document.querySelector('.user-btn');
            
            if (dropdown && userBtn && !userBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>

    <!-- Mobile Bottom Bar -->
    <div class="mobile-bottom-bar">
        <a href="{{ route('home') }}" class="bottom-bar-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="bottom-bar-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="/articles" class="bottom-bar-item">
            <i class="fas fa-newspaper"></i>
            <span>Artikel</span>
        </a>
        <a href="{{ route('cart.index') }}" class="bottom-bar-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Keranjang</span>
            @auth
                @if(auth()->user()->carts()->count() > 0)
                    <span class="bottom-bar-badge">{{ auth()->user()->carts()->count() }}</span>
                @endif
            @endauth
        </a>
        <a href="#" class="bottom-bar-item" onclick="toggleMobileAccount()">
            <i class="fas fa-user"></i>
            <span>Akun</span>
        </a>
    </div>

    <!-- Mobile Account Menu -->
    <div class="mobile-account-menu" id="mobileAccountMenu">
        <div class="mobile-account-close">
            <button onclick="toggleMobileAccount()" class="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @auth
            <div class="mobile-account-header">
                <div class="mobile-account-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="mobile-account-info">
                    <div class="mobile-account-name">{{ auth()->user()->name }}</div>
                    <div class="mobile-account-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="mobile-account-links">
                @if(auth()->user()->isSuperadmin())
                    <a href="{{ route('admin.dashboard') }}" class="mobile-account-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Admin Dashboard</span>
                    </a>
                @endif
                <a href="{{ route('orders.index') }}" class="mobile-account-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('messages.index') }}" class="mobile-account-link">
                    <i class="fas fa-comments"></i>
                    <span>Chat</span>
                    <span class="mobile-chat-badge" id="mobileChatBadge" style="display: none;"></span>
                </a>
                <a href="{{ route('cart.index') }}" class="mobile-account-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>My Cart</span>
                    <span class="mobile-cart-badge">{{ auth()->user()->carts()->count() }}</span>
                </a>
            </div>
            <div class="mobile-account-logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        @else
            <div class="mobile-account-auth">
                <a href="{{ route('login') }}" class="mobile-auth-btn login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}" class="mobile-auth-btn register">
                    <i class="fas fa-user-plus"></i>
                    <span>Register</span>
                </a>
            </div>
        @endauth
    </div>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" style="display: none; position: fixed; top: 12px; left: 16px; right: 16px; max-width: 440px; margin: 0 auto; background: #2c1810; color: #ffffff; padding: 12px 16px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.25); z-index: 99999; align-items: center; justify-content: space-between; gap: 12px; font-family: inherit;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/icon-192.png') }}" alt="Bennylovescoffee" style="width: 40px; height: 40px; border-radius: 8px; flex-shrink: 0;">
            <div>
                <div style="font-weight: 700; font-size: 0.9rem; line-height: 1.2;">Install Bennylovescoffee</div>
                <div style="font-size: 0.75rem; color: #d1c7bd;">Buka lebih cepat & mudah di HP</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
            <button id="pwa-install-btn" style="background: #f59e0b; color: #1a1a1a; border: none; padding: 6px 14px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; cursor: pointer;">Install</button>
            <button id="pwa-close-btn" style="background: transparent; color: #ffffff; border: none; font-size: 1.2rem; line-height: 1; cursor: pointer; padding: 4px 6px;">&times;</button>
        </div>
    </div>

    <!-- PWA Script -->
    <script src="{{ asset('js/pwa.js') }}"></script>
</body>
</html>