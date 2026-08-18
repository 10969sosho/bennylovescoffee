// Mobile Navigation Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navMenu.classList.remove('active');
}));

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Active navigation link highlighting
window.addEventListener('scroll', () => {
    let current = '';
    const sections = document.querySelectorAll('section');
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 200)) {
            current = section.getAttribute('id');
        }
    });

    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});

// Cart functionality
let cartCount = 0;
const cartCountElement = document.querySelector('.cart-count');
const addToCartButtons = document.querySelectorAll('.add-to-cart');

addToCartButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        cartCount++;
        cartCountElement.textContent = cartCount;
        
        // Add visual feedback
        button.style.background = 'linear-gradient(135deg, #4CAF50, #45a049)';
        button.textContent = 'Added!';
        
        setTimeout(() => {
            button.style.background = 'linear-gradient(135deg, #d4af37, #b8941f)';
            button.textContent = 'Add to Cart';
        }, 1500);
        
        // Show notification
        showNotification('Product added to cart!');
    });
});

// Flash Sale Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const flashSaleButtons = document.querySelectorAll('.flash-sale-btn-add');
    
    flashSaleButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            console.log('Flash sale button clicked!', button);
            
            // Don't prevent default for form submission
            if (button.tagName === 'BUTTON') {
                // Add visual feedback for flash sale buttons
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                button.style.background = '#4CAF50';
                button.disabled = true;
                
                // Show notification immediately
                showNotification('Flash sale item added to cart!');
                
                // Reset button after form submission
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = '#28a745';
                    button.disabled = false;
                }, 2000);
            }
        });
    });
});

// Notification system
function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: linear-gradient(135deg, #d4af37, #b8941f);
        color: #000;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 10000;
        font-weight: bold;
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Product hover effects
const productCards = document.querySelectorAll('.product-card');
productCards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) scale(1)';
    });
});

// Category hover effects
const categoryItems = document.querySelectorAll('.category-item');
categoryItems.forEach(item => {
    item.addEventListener('mouseenter', () => {
        item.style.transform = 'translateY(-8px) scale(1.05)';
    });
    
    item.addEventListener('mouseleave', () => {
        item.style.transform = 'translateY(0) scale(1)';
    });
});

// Search functionality (placeholder)
const searchIcon = document.querySelector('.fa-search');
searchIcon.addEventListener('click', () => {
    const searchTerm = prompt('Enter your search term:');
    if (searchTerm) {
        showNotification(`Searching for: ${searchTerm}`);
    }
});

// User icon functionality
const userIcon = document.querySelector('.fa-user');
userIcon.addEventListener('click', () => {
    showNotification('Login/Register feature coming soon!');
});

// Cart icon functionality
const cartIcon = document.querySelector('.fa-shopping-cart');
cartIcon.addEventListener('click', () => {
    if (cartCount > 0) {
        showNotification(`You have ${cartCount} item(s) in your cart`);
    } else {
        showNotification('Your cart is empty');
    }
});

// CTA Button functionality
const ctaButton = document.querySelector('.cta-button');
if (ctaButton) {
    ctaButton.addEventListener('click', () => {
        document.querySelector('#products').scrollIntoView({
            behavior: 'smooth'
        });
    });
}

// Carousel functionality
let currentSlideIndex = 0;
const totalSlides = 3; // Number of slides to show at once
let carouselTrack, dots;

// Initialize carousel when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    carouselTrack = document.getElementById('carouselTrack');
    dots = document.querySelectorAll('.dot');
    
    if (carouselTrack && dots.length > 0) {
        updateDots();
    }
});

function moveCarousel(direction) {
    if (!carouselTrack) return;
    
    const items = document.querySelectorAll('.carousel-item');
    if (items.length === 0) return;
    
    const itemWidth = items[0].offsetWidth + 32; // width + gap
    const maxSlide = Math.max(0, items.length - totalSlides);
    
    currentSlideIndex += direction;
    
    if (currentSlideIndex < 0) {
        currentSlideIndex = maxSlide;
    } else if (currentSlideIndex > maxSlide) {
        currentSlideIndex = 0;
    }
    
    const translateX = -currentSlideIndex * itemWidth;
    carouselTrack.style.transform = `translateX(${translateX}px)`;
    
    updateDots();
}

function goToSlide(slide) {
    if (!carouselTrack) return;
    
    const items = document.querySelectorAll('.carousel-item');
    if (items.length === 0) return;
    
    currentSlideIndex = slide - 1;
    const itemWidth = items[0].offsetWidth + 32;
    const translateX = -currentSlideIndex * itemWidth;
    carouselTrack.style.transform = `translateX(${translateX}px)`;
    
    updateDots();
}

function updateDots() {
    if (!dots || dots.length === 0) return;
    
    dots.forEach((dot, index) => {
        dot.classList.remove('active');
        if (index === currentSlideIndex) {
            dot.classList.add('active');
        }
    });
}

// Auto-play carousel
let autoPlayInterval;
function startAutoPlay() {
    autoPlayInterval = setInterval(() => {
        moveCarousel(1);
    }, 5000);
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
}

// Start auto-play when page loads
document.addEventListener('DOMContentLoaded', function() {
    startAutoPlay();
    
    // Pause auto-play on hover
    const carouselContainer = document.querySelector('.carousel-container');
    if (carouselContainer) {
        carouselContainer.addEventListener('mouseenter', stopAutoPlay);
        carouselContainer.addEventListener('mouseleave', startAutoPlay);
    }
    
    // Initialize all carousels
    initializeAllCarousels();
    
    // Start flash sale timer
    startFlashSaleTimer();
});

// Flash Sale Timer
function startFlashSaleTimer() {
    let hours = 2;
    let minutes = 26;
    let seconds = 27;
    
    const timer = setInterval(() => {
        seconds--;
        
        if (seconds < 0) {
            seconds = 59;
            minutes--;
        }
        
        if (minutes < 0) {
            minutes = 59;
            hours--;
        }
        
        if (hours < 0) {
            hours = 0;
            minutes = 0;
            seconds = 0;
            clearInterval(timer);
        }
        
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        
        if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
        if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
        if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
    }, 1000);
}

// Category Carousel Functions - Define globally first
window.moveCategoryCarousel = function(direction) {
    console.log('moveCategoryCarousel called with direction:', direction);
    if (!window.categoryCarouselTrack) {
        console.log('categoryCarouselTrack not found');
        return;
    }
    
    const items = document.querySelectorAll('.category-carousel-track .category-item');
    console.log('Items found:', items.length);
    if (items.length === 0) return;
    
    const itemWidth = items[0].offsetWidth + 32;
    const itemsPerView = 6;
    const maxSlide = Math.max(0, items.length - itemsPerView);
    
    console.log('itemWidth:', itemWidth, 'maxSlide:', maxSlide);
    
    // If items are less than itemsPerView, don't allow movement
    if (items.length <= itemsPerView) {
        console.log('Items less than itemsPerView, no movement allowed');
        return;
    }
    
    window.categoryCurrentSlide += direction;
    
    if (window.categoryCurrentSlide < 0) {
        window.categoryCurrentSlide = maxSlide;
    } else if (window.categoryCurrentSlide > maxSlide) {
        window.categoryCurrentSlide = 0;
    }
    
    console.log('New slide index:', window.categoryCurrentSlide);
    
    const translateX = -window.categoryCurrentSlide * itemWidth;
    window.categoryCarouselTrack.style.transform = `translateX(${translateX}px)`;
}

// Debug: Check if function is defined
console.log('moveCategoryCarousel defined:', typeof window.moveCategoryCarousel);

// Initialize all carousels
function initializeAllCarousels() {
    // Category Carousel
    window.categoryCurrentSlide = 0;
    window.categoryCarouselTrack = document.getElementById('categoryCarouselTrack');
    
    // Flash Sale Carousel
    window.flashSaleCurrentSlide = 0;
    window.flashSaleCarouselTrack = document.getElementById('flashSaleCarouselTrack');
    
    // Brand Carousel
    window.brandCurrentSlide = 0;
    window.brandCarouselTrack = document.getElementById('brandCarouselTrack');
    
    // Resep Carousel
    window.resepCurrentSlide = 0;
    window.resepCarouselTrack = document.getElementById('resepCarouselTrack');
    
    // Produk Terbaru Carousel - Moved to dedicated file
    
    // Majalah Carousel
    window.majalahCurrentSlide = 0;
    window.majalahCarouselTrack = document.getElementById('majalahCarouselTrack');
}

// Flash Sale Carousel Functions
function moveFlashSaleCarousel(direction) {
    if (!window.flashSaleCarouselTrack) return;
    
    const items = document.querySelectorAll('.flash-sale-carousel-track .flash-sale-item');
    if (items.length === 0) return;
    
    const itemWidth = items[0].offsetWidth + 32;
    const itemsPerView = 4;
    const maxSlide = Math.max(0, items.length - itemsPerView);
    
    // If items are less than itemsPerView, don't allow movement
    if (items.length <= itemsPerView) {
        return;
    }
    
    window.flashSaleCurrentSlide += direction;
    
    if (window.flashSaleCurrentSlide < 0) {
        window.flashSaleCurrentSlide = maxSlide;
    } else if (window.flashSaleCurrentSlide > maxSlide) {
        window.flashSaleCurrentSlide = 0;
    }
    
    const translateX = -window.flashSaleCurrentSlide * itemWidth;
    window.flashSaleCarouselTrack.style.transform = `translateX(${translateX}px)`;
}

// Brand Carousel Functions
function moveBrandCarousel(direction) {
    if (!window.brandCarouselTrack) return;
    
    const rows = document.querySelectorAll('.brand-carousel-track .brand-row');
    if (rows.length === 0) return;
    
    const rowWidth = rows[0].offsetWidth + 16; // width + gap
    const itemsPerView = 2; // 2 columns visible
    const maxSlide = Math.max(0, rows.length - itemsPerView);
    
    // If rows are less than itemsPerView, don't allow movement
    if (rows.length <= itemsPerView) {
        return;
    }
    
    window.brandCurrentSlide += direction;
    
    if (window.brandCurrentSlide < 0) {
        window.brandCurrentSlide = maxSlide;
    } else if (window.brandCurrentSlide > maxSlide) {
        window.brandCurrentSlide = 0;
    }
    
    const translateX = -window.brandCurrentSlide * rowWidth;
    window.brandCarouselTrack.style.transform = `translateX(${translateX}px)`;
}

// Resep Carousel Functions
function moveResepCarousel(direction) {
    if (!window.resepCarouselTrack) return;
    
    const items = document.querySelectorAll('.resep-carousel-track .resep-item');
    if (items.length === 0) return;
    
    const itemWidth = items[0].offsetWidth + 32;
    const itemsPerView = 4;
    const maxSlide = Math.max(0, items.length - itemsPerView);
    
    // If items are less than itemsPerView, don't allow movement
    if (items.length <= itemsPerView) {
        return;
    }
    
    window.resepCurrentSlide += direction;
    
    if (window.resepCurrentSlide < 0) {
        window.resepCurrentSlide = maxSlide;
    } else if (window.resepCurrentSlide > maxSlide) {
        window.resepCurrentSlide = 0;
    }
    
    const translateX = -window.resepCurrentSlide * itemWidth;
    window.resepCarouselTrack.style.transform = `translateX(${translateX}px)`;
}

// Produk Terbaru Carousel Functions - Moved to dedicated file

// Majalah Carousel Functions
function moveMajalahCarousel(direction) {
    if (!window.majalahCarouselTrack) return;
    
    const items = document.querySelectorAll('.majalah-carousel-track .majalah-item');
    if (items.length === 0) return;
    
    const itemWidth = items[0].offsetWidth + 32;
    const itemsPerView = 5;
    const maxSlide = Math.max(0, items.length - itemsPerView);
    
    // If items are less than itemsPerView, don't allow movement
    if (items.length <= itemsPerView) {
        return;
    }
    
    window.majalahCurrentSlide += direction;
    
    if (window.majalahCurrentSlide < 0) {
        window.majalahCurrentSlide = maxSlide;
    } else if (window.majalahCurrentSlide > maxSlide) {
        window.majalahCurrentSlide = 0;
    }
    
    const translateX = -window.majalahCurrentSlide * itemWidth;
    window.majalahCarouselTrack.style.transform = `translateX(${translateX}px)`;
}

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero');
    const rate = scrolled * -0.5;
    
    if (hero) {
        hero.style.transform = `translateY(${rate}px)`;
    }
});

// Loading animation for product images
const productImages = document.querySelectorAll('.product-placeholder');
productImages.forEach((img, index) => {
    img.style.opacity = '0';
    img.style.transform = 'scale(0.8)';
    
    setTimeout(() => {
        img.style.transition = 'all 0.5s ease';
        img.style.opacity = '1';
        img.style.transform = 'scale(1)';
    }, index * 200);
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.querySelectorAll('.category-item, .product-card, .about-text, .about-image').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'all 0.6s ease';
    observer.observe(el);
});

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        // Close mobile menu
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    }
});

// Add to cart animation
function animateAddToCart(button) {
    button.style.transform = 'scale(0.95)';
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 150);
}

// Enhanced add to cart with animation
addToCartButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        animateAddToCart(button);
    });
});

// Product showcase animation
const productItems = document.querySelectorAll('.product-item');
productItems.forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(50px)';
    
    setTimeout(() => {
        item.style.transition = 'all 0.8s ease';
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
    }, 1000 + (index * 200));
});

// Scroll to top functionality
window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const navbar = document.querySelector('.navbar');
    
    if (scrollTop > 100) {
        navbar.style.background = 'rgba(0, 0, 0, 0.98)';
    } else {
        navbar.style.background = 'rgba(0, 0, 0, 0.95)';
    }
});

// Initialize animations on page load
window.addEventListener('load', () => {
    document.body.style.opacity = '1';
    
    // Animate hero text
    const heroText = document.querySelector('.hero-text');
    if (heroText) {
        heroText.style.opacity = '0';
        heroText.style.transform = 'translateY(50px)';
        
        setTimeout(() => {
            heroText.style.transition = 'all 1s ease';
            heroText.style.opacity = '1';
            heroText.style.transform = 'translateY(0)';
        }, 500);
    }
});

// Set initial body opacity
document.body.style.opacity = '0';
document.body.style.transition = 'opacity 0.5s ease';

