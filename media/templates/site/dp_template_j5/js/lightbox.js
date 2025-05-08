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