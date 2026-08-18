// Banner Carousel Functions
let currentBannerSlide = 0;
let bannerSlides = [];
let bannerIndicators = [];
let bannerAutoPlay = null;

// Touch/Drag variables
let startX = 0;
let startY = 0;
let currentX = 0;
let currentY = 0;
let isDragging = false;
let dragThreshold = 50; // Minimum drag distance to trigger slide change

function moveBannerCarousel(direction) {
    if (bannerSlides.length === 0) return;
    
    console.log('Moving banner carousel:', { direction, currentSlide: currentBannerSlide, totalSlides: bannerSlides.length });
    
    currentBannerSlide += direction;
    
    if (currentBannerSlide >= bannerSlides.length) {
        currentBannerSlide = 0;
    } else if (currentBannerSlide < 0) {
        currentBannerSlide = bannerSlides.length - 1;
    }
    
    console.log('New slide index:', currentBannerSlide);
    updateBannerCarousel();
}

function goToBannerSlide(slideIndex) {
    currentBannerSlide = slideIndex;
    updateBannerCarousel();
}

function updateBannerCarousel() {
    const track = document.getElementById('bannerCarouselTrack');
    if (!track) {
        console.error('Banner carousel track not found!');
        return;
    }
    
    const slideWidth = 100; // 100% per slide
    const translateX = "-" + (currentBannerSlide * slideWidth) + "%";
    track.style.transform = `translateX(${translateX})`;
    
    console.log('Updating banner carousel:', {
        currentSlide: currentBannerSlide,
        translateX: translateX,
        track: track
    });
    
    // Update indicators
    bannerIndicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentBannerSlide);
    });
}

function startBannerAutoPlay() {
    if (bannerAutoPlay) {
        clearInterval(bannerAutoPlay);
    }
    
    if (bannerSlides.length > 1) {
        bannerAutoPlay = setInterval(() => {
            moveBannerCarousel(1);
        }, 5000);
    }
}

function stopBannerAutoPlay() {
    if (bannerAutoPlay) {
        clearInterval(bannerAutoPlay);
        bannerAutoPlay = null;
    }
}

// Touch/Drag event handlers
function handleTouchStart(e) {
    if (bannerSlides.length <= 1) return;
    
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    isDragging = true;
    
    // Stop auto-play during drag
    stopBannerAutoPlay();
    
    console.log('Touch start:', { startX, startY });
}

function handleTouchMove(e) {
    if (!isDragging || bannerSlides.length <= 1) return;
    
    currentX = e.touches[0].clientX;
    currentY = e.touches[0].clientY;
    
    const deltaX = currentX - startX;
    const deltaY = currentY - startY;
    
    // Prevent vertical scrolling if horizontal drag is significant
    if (Math.abs(deltaX) > Math.abs(deltaY)) {
        e.preventDefault();
    }
    
    console.log('Touch move:', { deltaX, deltaY });
}

function handleTouchEnd(e) {
    if (!isDragging || bannerSlides.length <= 1) return;
    
    const deltaX = currentX - startX;
    const deltaY = currentY - startY;
    
    console.log('Touch end:', { deltaX, deltaY });
    
    // Check if horizontal drag is significant and more than vertical
    if (Math.abs(deltaX) > dragThreshold && Math.abs(deltaX) > Math.abs(deltaY)) {
        if (deltaX > 0) {
            // Drag right - go to previous slide
            moveBannerCarousel(-1);
        } else {
            // Drag left - go to next slide
            moveBannerCarousel(1);
        }
    }
    
    isDragging = false;
    
    // Resume auto-play after drag
    setTimeout(() => {
        startBannerAutoPlay();
    }, 1000);
}

// Mouse drag support for desktop
function handleMouseDown(e) {
    if (bannerSlides.length <= 1) return;
    
    startX = e.clientX;
    startY = e.clientY;
    isDragging = true;
    
    // Stop auto-play during drag
    stopBannerAutoPlay();
    
    console.log('Mouse down:', { startX, startY });
}

function handleMouseMove(e) {
    if (!isDragging || bannerSlides.length <= 1) return;
    
    currentX = e.clientX;
    currentY = e.clientY;
    
    const deltaX = currentX - startX;
    const deltaY = currentY - startY;
    
    console.log('Mouse move:', { deltaX, deltaY });
}

function handleMouseUp(e) {
    if (!isDragging || bannerSlides.length <= 1) return;
    
    const deltaX = currentX - startX;
    const deltaY = currentY - startY;
    
    console.log('Mouse up:', { deltaX, deltaY });
    
    // Check if horizontal drag is significant and more than vertical
    if (Math.abs(deltaX) > dragThreshold && Math.abs(deltaX) > Math.abs(deltaY)) {
        if (deltaX > 0) {
            // Drag right - go to previous slide
            moveBannerCarousel(-1);
        } else {
            // Drag left - go to next slide
            moveBannerCarousel(1);
        }
    }
    
    isDragging = false;
    
    // Resume auto-play after drag
    setTimeout(() => {
        startBannerAutoPlay();
    }, 1000);
}

// Initialize banner carousel when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get banner elements
    bannerSlides = document.querySelectorAll('.banner-slide');
    bannerIndicators = document.querySelectorAll('.banner-indicator');
    
    console.log('Banner carousel initialized:', {
        slides: bannerSlides.length,
        indicators: bannerIndicators.length
    });
    
    // Only initialize if there are multiple slides
    if (bannerSlides.length > 1) {
        const bannerCarousel = document.querySelector('.banner-carousel-container');
        
        if (bannerCarousel) {
            console.log('Starting banner auto-play');
            // Start auto-play
            startBannerAutoPlay();
            
            // Pause auto-play on hover
            bannerCarousel.addEventListener('mouseenter', () => {
                console.log('Banner hover - pausing auto-play');
                stopBannerAutoPlay();
            });
            
            // Resume auto-play on mouse leave
            bannerCarousel.addEventListener('mouseleave', () => {
                console.log('Banner leave - resuming auto-play');
                startBannerAutoPlay();
            });
            
            // Add touch events for mobile drag/swipe
            bannerCarousel.addEventListener('touchstart', handleTouchStart, { passive: false });
            bannerCarousel.addEventListener('touchmove', handleTouchMove, { passive: false });
            bannerCarousel.addEventListener('touchend', handleTouchEnd, { passive: false });
            
            // Add mouse events for desktop drag
            bannerCarousel.addEventListener('mousedown', handleMouseDown);
            bannerCarousel.addEventListener('mousemove', handleMouseMove);
            bannerCarousel.addEventListener('mouseup', handleMouseUp);
            bannerCarousel.addEventListener('mouseleave', () => {
                isDragging = false;
            });
        }
    } else {
        console.log('Single banner or no banners - no carousel needed');
    }
});

// Banner Section 2 Carousel Functions
let currentBannerSection2Slide = 0;
let bannerSection2Slides = [];
let bannerSection2Indicators = [];
let bannerSection2AutoPlay = null;

// Touch/Drag variables for Banner Section 2
let startX2 = 0;
let startY2 = 0;
let currentX2 = 0;
let currentY2 = 0;
let isDragging2 = false;
let dragThreshold2 = 50; // Minimum drag distance to trigger slide change

function moveBannerSection2Carousel(direction) {
    if (bannerSection2Slides.length === 0) return;
    
    console.log('Moving banner section 2 carousel:', { direction, currentSlide: currentBannerSection2Slide, totalSlides: bannerSection2Slides.length });
    
    currentBannerSection2Slide += direction;
    
    if (currentBannerSection2Slide >= bannerSection2Slides.length) {
        currentBannerSection2Slide = 0;
    } else if (currentBannerSection2Slide < 0) {
        currentBannerSection2Slide = bannerSection2Slides.length - 1;
    }
    
    console.log('New section 2 slide index:', currentBannerSection2Slide);
    updateBannerSection2Carousel();
}

function goToBannerSection2Slide(slideIndex) {
    currentBannerSection2Slide = slideIndex;
    updateBannerSection2Carousel();
}

function updateBannerSection2Carousel() {
    const track = document.getElementById('bannerSection2CarouselTrack');
    if (!track) {
        console.error('Banner section 2 carousel track not found!');
        return;
    }
    
    const slideWidth = 100; // 100% per slide
    const translateX = "-" + (currentBannerSection2Slide * slideWidth) + "%";
    track.style.transform = `translateX(${translateX})`;
    
    console.log('Updating banner section 2 carousel:', {
        currentSlide: currentBannerSection2Slide,
        translateX: translateX,
        track: track
    });
    
    // Update indicators
    bannerSection2Indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentBannerSection2Slide);
    });
}

function startBannerSection2AutoPlay() {
    if (bannerSection2AutoPlay) {
        clearInterval(bannerSection2AutoPlay);
    }
    
    if (bannerSection2Slides.length > 1) {
        bannerSection2AutoPlay = setInterval(() => {
            moveBannerSection2Carousel(1);
        }, 5000);
    }
}

function stopBannerSection2AutoPlay() {
    if (bannerSection2AutoPlay) {
        clearInterval(bannerSection2AutoPlay);
        bannerSection2AutoPlay = null;
    }
}

// Touch/Drag event handlers for Banner Section 2
function handleTouchStart2(e) {
    if (bannerSection2Slides.length <= 1) return;
    
    startX2 = e.touches[0].clientX;
    startY2 = e.touches[0].clientY;
    isDragging2 = true;
    
    // Stop auto-play during drag
    stopBannerSection2AutoPlay();
    
    console.log('Touch start section 2:', { startX2, startY2 });
}

function handleTouchMove2(e) {
    if (!isDragging2 || bannerSection2Slides.length <= 1) return;
    
    currentX2 = e.touches[0].clientX;
    currentY2 = e.touches[0].clientY;
    
    const deltaX = currentX2 - startX2;
    const deltaY = currentY2 - startY2;
    
    // Prevent vertical scrolling if horizontal drag is significant
    if (Math.abs(deltaX) > Math.abs(deltaY)) {
        e.preventDefault();
    }
    
    console.log('Touch move section 2:', { deltaX, deltaY });
}

function handleTouchEnd2(e) {
    if (!isDragging2 || bannerSection2Slides.length <= 1) return;
    
    const deltaX = currentX2 - startX2;
    const deltaY = currentY2 - startY2;
    
    console.log('Touch end section 2:', { deltaX, deltaY });
    
    // Check if horizontal drag is significant and more than vertical
    if (Math.abs(deltaX) > dragThreshold2 && Math.abs(deltaX) > Math.abs(deltaY)) {
        if (deltaX > 0) {
            // Drag right - go to previous slide
            moveBannerSection2Carousel(-1);
        } else {
            // Drag left - go to next slide
            moveBannerSection2Carousel(1);
        }
    }
    
    isDragging2 = false;
    
    // Resume auto-play after drag
    setTimeout(() => {
        startBannerSection2AutoPlay();
    }, 1000);
}

// Mouse drag support for desktop - Banner Section 2
function handleMouseDown2(e) {
    if (bannerSection2Slides.length <= 1) return;
    
    startX2 = e.clientX;
    startY2 = e.clientY;
    isDragging2 = true;
    
    // Stop auto-play during drag
    stopBannerSection2AutoPlay();
    
    console.log('Mouse down section 2:', { startX2, startY2 });
}

function handleMouseMove2(e) {
    if (!isDragging2 || bannerSection2Slides.length <= 1) return;
    
    currentX2 = e.clientX;
    currentY2 = e.clientY;
    
    const deltaX = currentX2 - startX2;
    const deltaY = currentY2 - startY2;
    
    console.log('Mouse move section 2:', { deltaX, deltaY });
}

function handleMouseUp2(e) {
    if (!isDragging2 || bannerSection2Slides.length <= 1) return;
    
    const deltaX = currentX2 - startX2;
    const deltaY = currentY2 - startY2;
    
    console.log('Mouse up section 2:', { deltaX, deltaY });
    
    // Check if horizontal drag is significant and more than vertical
    if (Math.abs(deltaX) > dragThreshold2 && Math.abs(deltaX) > Math.abs(deltaY)) {
        if (deltaX > 0) {
            // Drag right - go to previous slide
            moveBannerSection2Carousel(-1);
        } else {
            // Drag left - go to next slide
            moveBannerSection2Carousel(1);
        }
    }
    
    isDragging2 = false;
    
    // Resume auto-play after drag
    setTimeout(() => {
        startBannerSection2AutoPlay();
    }, 1000);
}

// Initialize banner section 2 carousel when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get banner section 2 elements
    bannerSection2Slides = document.querySelectorAll('#bannerSection2CarouselTrack .banner-slide');
    bannerSection2Indicators = document.querySelectorAll('.banner-section-2 .banner-indicator');
    
    console.log('Banner section 2 carousel initialized:', {
        slides: bannerSection2Slides.length,
        indicators: bannerSection2Indicators.length
    });
    
    // Only initialize if there are multiple slides
    if (bannerSection2Slides.length > 1) {
        const bannerSection2Carousel = document.querySelector('.banner-section-2 .banner-carousel-container');
        
        if (bannerSection2Carousel) {
            console.log('Starting banner section 2 auto-play');
            // Start auto-play
            startBannerSection2AutoPlay();
            
            // Pause auto-play on hover
            bannerSection2Carousel.addEventListener('mouseenter', () => {
                console.log('Banner section 2 hover - pausing auto-play');
                stopBannerSection2AutoPlay();
            });
            
            // Resume auto-play on mouse leave
            bannerSection2Carousel.addEventListener('mouseleave', () => {
                console.log('Banner section 2 leave - resuming auto-play');
                startBannerSection2AutoPlay();
            });
            
            // Add touch events for mobile drag/swipe - Banner Section 2
            bannerSection2Carousel.addEventListener('touchstart', handleTouchStart2, { passive: false });
            bannerSection2Carousel.addEventListener('touchmove', handleTouchMove2, { passive: false });
            bannerSection2Carousel.addEventListener('touchend', handleTouchEnd2, { passive: false });
            
            // Add mouse events for desktop drag - Banner Section 2
            bannerSection2Carousel.addEventListener('mousedown', handleMouseDown2);
            bannerSection2Carousel.addEventListener('mousemove', handleMouseMove2);
            bannerSection2Carousel.addEventListener('mouseup', handleMouseUp2);
            bannerSection2Carousel.addEventListener('mouseleave', () => {
                isDragging2 = false;
            });
        }
    } else {
        console.log('Single banner section 2 or no banners - no carousel needed');
    }
});
