/*
document.addEventListener("DOMContentLoaded", function () {
    const triggers = document.querySelectorAll(".lightbox-trigger");
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightbox-image");
    const closeBtn = document.querySelector(".lightbox-close");

    // Function to open the lightbox
    function openLightbox(event) {
        event.preventDefault();
        const targetSrc = event.target.closest(".lightbox-trigger").getAttribute("href");
        if (!targetSrc) return;

        if (!lightbox.classList.contains("active")) {
            lightboxImage.src = targetSrc;
            lightbox.classList.add("active");
            disableScroll(); // Disable scroll
        }
    }

    // Function to close the lightbox
    function closeLightbox() {
        if (lightbox.classList.contains("active")) {
            lightbox.classList.remove("active");
            lightboxImage.src = "";
            enableScroll(); // Re-enable scroll
        }
    }

    // Disable scrolling
    function disableScroll() {
        document.body.style.overflow = "hidden";
        document.body.style.position = "fixed"; // Prevents page jump
        document.body.style.width = "100%";
    }

    // Enable scrolling
    function enableScroll() {
        document.body.style.overflow = "";
        document.body.style.position = "";
        document.body.style.width = "";
    }

    // Event listeners
    triggers.forEach(trigger => trigger.addEventListener("click", openLightbox));
    if (closeBtn) closeBtn.addEventListener("click", closeLightbox);

    // Close on click outside image or press ESC
    document.addEventListener("click", function (event) {
        if (event.target === lightbox) closeLightbox();
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") closeLightbox();
    });
});
*/
document.addEventListener("DOMContentLoaded", function () {
    const triggers = document.querySelectorAll(".lightbox-trigger");
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightbox-image");
    const closeBtn = document.querySelector(".lightbox-close");

    // Gallery navigation elements
    let currentGallery = null;
    let currentIndex = 0;
    let prevBtn, nextBtn;

    // Function to open the lightbox
    function openLightbox(event) {
        event.preventDefault();
        const trigger = event.target.closest(".lightbox-trigger");
        const targetSrc = trigger.getAttribute("href");
        if (!targetSrc) return;

        // Check if this is part of a gallery
        const gallery = trigger.closest('.gallery-grid');
        
        if (!lightbox.classList.contains("active")) {
            lightboxImage.src = targetSrc;
            lightbox.classList.add("active");
            
            // Setup gallery navigation if in gallery
            if (gallery) {
                setupGalleryNavigation(gallery, trigger);
            } else {
                removeGalleryNavigation();
            }
            
            disableScroll();
        }
    }

    // Setup gallery navigation
    function setupGalleryNavigation(gallery, currentTrigger) {
        currentGallery = Array.from(gallery.querySelectorAll('.lightbox-trigger'));
        currentIndex = currentGallery.indexOf(currentTrigger);
        
        // Create navigation buttons if they don't exist
        if (!prevBtn) {
            prevBtn = document.createElement('button');
            prevBtn.className = 'lightbox-nav lightbox-prev';
            prevBtn.innerHTML = '&#8249;';
            prevBtn.addEventListener('click', showPrevImage);
            lightbox.appendChild(prevBtn);
        }
        
        if (!nextBtn) {
            nextBtn = document.createElement('button');
            nextBtn.className = 'lightbox-nav lightbox-next';
            nextBtn.innerHTML = '&#8250;';
            nextBtn.addEventListener('click', showNextImage);
            lightbox.appendChild(nextBtn);
        }
        
        prevBtn.style.display = 'block';
        nextBtn.style.display = 'block';
        
        updateNavigationVisibility();
    }

    // Remove gallery navigation
    function removeGalleryNavigation() {
        currentGallery = null;
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    }

    // Show previous image
    function showPrevImage() {
        if (currentIndex > 0) {
            currentIndex--;
            lightboxImage.src = currentGallery[currentIndex].getAttribute('href');
            updateNavigationVisibility();
        }
    }

    // Show next image
    function showNextImage() {
        if (currentIndex < currentGallery.length - 1) {
            currentIndex++;
            lightboxImage.src = currentGallery[currentIndex].getAttribute('href');
            updateNavigationVisibility();
        }
    }

    // Update navigation button visibility
    function updateNavigationVisibility() {
        if (prevBtn) prevBtn.style.opacity = currentIndex > 0 ? '1' : '0.3';
        if (nextBtn) nextBtn.style.opacity = currentIndex < currentGallery.length - 1 ? '1' : '0.3';
    }

    // Function to close the lightbox
    function closeLightbox() {
        if (lightbox.classList.contains("active")) {
            lightbox.classList.remove("active");
            lightboxImage.src = "";
            removeGalleryNavigation();
            enableScroll();
        }
    }

    // Disable scrolling
    function disableScroll() {
        document.body.style.overflow = "hidden";
        document.body.style.position = "fixed";
        document.body.style.width = "100%";
    }

    // Enable scrolling
    function enableScroll() {
        document.body.style.overflow = "";
        document.body.style.position = "";
        document.body.style.width = "";
    }

    // Event listeners
    triggers.forEach(trigger => trigger.addEventListener("click", openLightbox));
    if (closeBtn) closeBtn.addEventListener("click", closeLightbox);

    // Close on click outside image or press ESC
    document.addEventListener("click", function (event) {
        if (event.target === lightbox) closeLightbox();
    });
    
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeLightbox();
        } else if (currentGallery && lightbox.classList.contains("active")) {
            if (event.key === "ArrowLeft") showPrevImage();
            if (event.key === "ArrowRight") showNextImage();
        }
    });
});