document.addEventListener('DOMContentLoaded', function() {
    // Back to Top Button
    const backToTopButton = document.createElement('div');
    backToTopButton.className = 'back-to-top';
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    document.body.appendChild(backToTopButton);

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('visible');
        } else {
            backToTopButton.classList.remove('visible');
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Image Gallery Modal
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <img src="" alt="Gallery Image">
        </div>
    `;
    document.body.appendChild(modal);

    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            const imgSrc = item.querySelector('img').src;
            modal.querySelector('img').src = imgSrc;
            modal.classList.add('active');
        });
    });

    modal.querySelector('.modal-close').addEventListener('click', () => {
        modal.classList.remove('active');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Lazy Loading Images
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));

    // Smooth Scroll for Anchor Links
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

    // Price Animation on Hover
    const priceElement = document.querySelector('.price-animation');
    if (priceElement) {
        priceElement.addEventListener('mouseenter', () => {
            priceElement.style.animation = 'pulse 1s infinite';
        });
        priceElement.addEventListener('mouseleave', () => {
            priceElement.style.animation = 'none';
        });
    }

    // Rating Stars Interaction
    const ratingStars = document.querySelectorAll('.rating-star');
    ratingStars.forEach(star => {
        star.addEventListener('mouseenter', () => {
            star.style.transform = 'scale(1.2)';
        });
        star.addEventListener('mouseleave', () => {
            star.style.transform = 'scale(1)';
        });
    });

    // Mobile Menu Toggle
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });
    }

    // Booking Form Validation
    const bookingForm = document.querySelector('.booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Add your form validation logic here
            const formData = new FormData(bookingForm);
            // Process the form data
            console.log('Form submitted:', Object.fromEntries(formData));
        });
    }

    // Parallax Effect for Hero Section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
        });
    }

    // Loading Animation
    function showLoading() {
        const loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'loading-spinner';
        document.body.appendChild(loadingSpinner);
    }

    function hideLoading() {
        const loadingSpinner = document.querySelector('.loading-spinner');
        if (loadingSpinner) {
            loadingSpinner.remove();
        }
    }

    // Add loading animation to all AJAX requests
    document.addEventListener('ajaxStart', showLoading);
    document.addEventListener('ajaxStop', hideLoading);

    // Share Functionality
    const shareButtons = document.querySelectorAll('.share-button');
    shareButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const dummy = document.createElement('input');
                document.body.appendChild(dummy);
                dummy.value = window.location.href;
                dummy.select();
                document.execCommand('copy');
                document.body.removeChild(dummy);
                alert('Link copied to clipboard!');
            }
        });
    });

    // Add to Wishlist
    const wishlistButton = document.querySelector('.wishlist-button');
    if (wishlistButton) {
        wishlistButton.addEventListener('click', () => {
            wishlistButton.classList.toggle('active');
            // Add your wishlist logic here
        });
    }

    // Initialize any third-party libraries
    // Example: Initialize a date picker
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.date-picker', {
            dateFormat: 'Y-m-d',
            minDate: 'today'
        });
    }
}); 