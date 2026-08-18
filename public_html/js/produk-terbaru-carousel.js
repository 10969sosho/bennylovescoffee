// Produk Terbaru Carousel Functions
let currentProdukTerbaruSlide = 0;
let produkTerbaruSlides = [];
let produkTerbaruAutoPlay = null;

// Make function globally available
window.moveProdukTerbaruCarousel = function(direction) {
    if (produkTerbaruSlides.length === 0) return;
    
    const track = document.getElementById('produkTerbaruCarouselTrack');
    if (!track) return;
    
    const slidesToShow = getProdukTerbaruSlidesToShow();
    const maxSlide = Math.max(0, produkTerbaruSlides.length - slidesToShow);
    
    currentProdukTerbaruSlide += direction;
    
    if (currentProdukTerbaruSlide > maxSlide) {
        currentProdukTerbaruSlide = 0;
    } else if (currentProdukTerbaruSlide < 0) {
        currentProdukTerbaruSlide = maxSlide;
    }
    
    updateProdukTerbaruCarousel();
}

window.getProdukTerbaruSlidesToShow = function() {
    const width = window.innerWidth;
    if (width <= 480) return 1;
    if (width <= 768) return 2;
    if (width <= 900) return 3;
    if (width <= 1200) return 4;
    return 5;
}

window.updateProdukTerbaruCarousel = function() {
    const track = document.getElementById('produkTerbaruCarouselTrack');
    if (!track) return;
    
    const slideWidth = 320; // 320px + gap
    const translateX = -(currentProdukTerbaruSlide * slideWidth) + "px";
    track.style.transform = `translateX(${translateX})`;
}

window.startProdukTerbaruAutoPlay = function() {
    if (produkTerbaruAutoPlay) {
        clearInterval(produkTerbaruAutoPlay);
    }
    
    if (produkTerbaruSlides.length > getProdukTerbaruSlidesToShow()) {
        produkTerbaruAutoPlay = setInterval(() => {
            moveProdukTerbaruCarousel(1);
        }, 5000);
    }
}

window.stopProdukTerbaruAutoPlay = function() {
    if (produkTerbaruAutoPlay) {
        clearInterval(produkTerbaruAutoPlay);
        produkTerbaruAutoPlay = null;
    }
}

// Drag functionality variables
let startX = 0;
let startY = 0;
let currentX = 0;
let currentY = 0;
let isDragging = false;
let dragThreshold = 10;

// Touch event handlers
function handleProdukTerbaruTouchStart(e) {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    isDragging = false;
    stopProdukTerbaruAutoPlay();
    
    const track = document.getElementById('produkTerbaruCarouselTrack');
    if (track) {
        track.style.cursor = 'grabbing';
        track.style.transition = 'none';
        track.style.scrollBehavior = 'auto';
    }
    
    console.log('Touch start:', { startX, startY });
}

function handleProdukTerbaruTouchMove(e) {
    if (!startX || !startY) return;
    
    currentX = e.touches[0].clientX;
    currentY = e.touches[0].clientY;
    
    const diffX = Math.abs(currentX - startX);
    const diffY = Math.abs(currentY - startY);
    
    // Only trigger drag for horizontal movement
    if (diffX > dragThreshold && diffX > diffY) {
        isDragging = true;
        e.preventDefault();
        
        // Visual feedback during drag
        const track = document.getElementById('produkTerbaruCarouselTrack');
        if (track) {
            track.style.cursor = 'grabbing';
        }
    }
    
    console.log('Touch move:', { currentX, currentY, diffX, diffY, isDragging });
}

function handleProdukTerbaruTouchEnd(e) {
    if (!isDragging) {
        startX = 0;
        startY = 0;
        startProdukTerbaruAutoPlay();
        return;
    }
    
    const diffX = currentX - startX;
    const diffY = currentY - startY;
    
    console.log('Touch end:', { diffX, diffY, isDragging });
    
    // Only process horizontal swipes
    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 30) {
        if (diffX > 0) {
            moveProdukTerbaruCarousel(-1); // Swipe right, go to previous
        } else {
            moveProdukTerbaruCarousel(1); // Swipe left, go to next
        }
    }
    
    // Reset cursor and transition
    const track = document.getElementById('produkTerbaruCarouselTrack');
    if (track) {
        track.style.cursor = 'grab';
        track.style.transition = 'transform 0.5s ease';
        track.style.scrollBehavior = 'smooth';
    }
    
    startX = 0;
    startY = 0;
    isDragging = false;
    startProdukTerbaruAutoPlay();
}

// Mouse event handlers
function handleProdukTerbaruMouseDown(e) {
    startX = e.clientX;
    startY = e.clientY;
    isDragging = false;
    stopProdukTerbaruAutoPlay();
}

function handleProdukTerbaruMouseMove(e) {
    if (!startX || !startY) return;
    
    currentX = e.clientX;
    currentY = e.clientY;
    
    const diffX = Math.abs(currentX - startX);
    const diffY = Math.abs(currentY - startY);
    
    if (diffX > dragThreshold || diffY > dragThreshold) {
        isDragging = true;
    }
}

function handleProdukTerbaruMouseUp(e) {
    if (!isDragging) {
        startX = 0;
        startY = 0;
        startProdukTerbaruAutoPlay();
        return;
    }
    
    const diffX = currentX - startX;
    const diffY = currentY - startY;
    
    if (Math.abs(diffX) > Math.abs(diffY)) {
        if (diffX > 50) {
            moveProdukTerbaruCarousel(-1); // Drag right, go to previous
        } else if (diffX < -50) {
            moveProdukTerbaruCarousel(1); // Drag left, go to next
        }
    }
    
    startX = 0;
    startY = 0;
    isDragging = false;
    startProdukTerbaruAutoPlay();
}

// Initialize produk terbaru carousel when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    produkTerbaruSlides = document.querySelectorAll('.produk-terbaru-item');
    
    console.log('Produk Terbaru carousel initialized:', {
        slides: produkTerbaruSlides.length,
        slidesToShow: getProdukTerbaruSlidesToShow()
    });
    
    if (produkTerbaruSlides.length > 0) {
        const produkTerbaruCarousel = document.querySelector('.produk-terbaru-carousel-container');
        const produkTerbaruTrack = document.getElementById('produkTerbaruCarouselTrack');
        
        if (produkTerbaruCarousel && produkTerbaruTrack) {
            // Start auto-play
            startProdukTerbaruAutoPlay();
            
            // Pause auto-play on hover
            produkTerbaruCarousel.addEventListener('mouseenter', () => {
                stopProdukTerbaruAutoPlay();
            });
            
            // Resume auto-play on mouse leave
            produkTerbaruCarousel.addEventListener('mouseleave', () => {
                startProdukTerbaruAutoPlay();
            });
            
            // Add touch events for mobile - Enhanced
            produkTerbaruTrack.addEventListener('touchstart', handleProdukTerbaruTouchStart, { passive: false });
            produkTerbaruTrack.addEventListener('touchmove', handleProdukTerbaruTouchMove, { passive: false });
            produkTerbaruTrack.addEventListener('touchend', handleProdukTerbaruTouchEnd, { passive: false });
            
            // Add mouse events for desktop
            produkTerbaruTrack.addEventListener('mousedown', handleProdukTerbaruMouseDown);
            produkTerbaruTrack.addEventListener('mousemove', handleProdukTerbaruMouseMove);
            produkTerbaruTrack.addEventListener('mouseup', handleProdukTerbaruMouseUp);
            produkTerbaruTrack.addEventListener('mouseleave', handleProdukTerbaruMouseUp);
            
            // Add wheel events for desktop scroll
            produkTerbaruTrack.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY > 0) {
                    moveProdukTerbaruCarousel(1); // Scroll down = next
                } else {
                    moveProdukTerbaruCarousel(-1); // Scroll up = previous
                }
            }, { passive: false });
        }
    }
    
    // Handle window resize
    window.addEventListener('resize', () => {
        updateProdukTerbaruCarousel();
    });
});
