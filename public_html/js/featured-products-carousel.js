// Featured Products Carousel Functions
let currentFeaturedProductsSlide = 0;
let featuredProductsSlides = [];
let featuredProductsAutoPlay = null;

function moveFeaturedProductsCarousel(direction) {
    if (featuredProductsSlides.length === 0) return;
    
    const track = document.getElementById('featuredProductsCarouselTrack');
    if (!track) return;
    
    const slidesToShow = getSlidesToShow();
    const maxSlide = Math.max(0, featuredProductsSlides.length - slidesToShow);
    
    currentFeaturedProductsSlide += direction;
    
    if (currentFeaturedProductsSlide > maxSlide) {
        currentFeaturedProductsSlide = 0;
    } else if (currentFeaturedProductsSlide < 0) {
        currentFeaturedProductsSlide = maxSlide;
    }
    
    updateFeaturedProductsCarousel();
}

function getSlidesToShow() {
    const width = window.innerWidth;
    if (width <= 480) return 1;
    if (width <= 768) return 2;
    if (width <= 900) return 3;
    if (width <= 1200) return 4;
    return 5;
}

function updateFeaturedProductsCarousel() {
    const track = document.getElementById('featuredProductsCarouselTrack');
    if (!track) return;
    
    const slideWidth = 305; // 280px + 25px gap
    const translateX = -(currentFeaturedProductsSlide * slideWidth) + "px";
    track.style.transform = `translateX(${translateX})`;
}

function startFeaturedProductsAutoPlay() {
    if (featuredProductsAutoPlay) {
        clearInterval(featuredProductsAutoPlay);
    }
    
    if (featuredProductsSlides.length > getSlidesToShow()) {
        featuredProductsAutoPlay = setInterval(() => {
            moveFeaturedProductsCarousel(1);
        }, 4000);
    }
}

function stopFeaturedProductsAutoPlay() {
    if (featuredProductsAutoPlay) {
        clearInterval(featuredProductsAutoPlay);
        featuredProductsAutoPlay = null;
    }
}

// Initialize featured products carousel when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    featuredProductsSlides = document.querySelectorAll('.featured-product-item');
    
    console.log('Featured products carousel initialized:', {
        slides: featuredProductsSlides.length,
        slidesToShow: getSlidesToShow()
    });
    
    if (featuredProductsSlides.length > 0) {
        const featuredProductsCarousel = document.querySelector('.featured-products-carousel-container');
        
        if (featuredProductsCarousel) {
            // Start auto-play
            startFeaturedProductsAutoPlay();
            
            // Pause auto-play on hover
            featuredProductsCarousel.addEventListener('mouseenter', () => {
                stopFeaturedProductsAutoPlay();
            });
            
            // Resume auto-play on mouse leave
            featuredProductsCarousel.addEventListener('mouseleave', () => {
                startFeaturedProductsAutoPlay();
            });
        }
    }
    
    // Handle window resize
    window.addEventListener('resize', () => {
        updateFeaturedProductsCarousel();
    });
});
