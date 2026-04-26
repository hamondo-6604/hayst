{{-- Back to Top Button --}}
<button id="backToTop" 
        onclick="scrollToTop()" 
        class="fixed bottom-8 right-8 w-12 h-12 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center opacity-0 invisible translate-y-2 z-50">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="18 15 12 9 6 15"></polyline>
  </svg>
</button>

<script>
  // Back to top functionality
  window.addEventListener('scroll', function() {
    const backToTopButton = document.getElementById('backToTop');
    if (window.pageYOffset > 300) {
      backToTopButton.classList.remove('opacity-0', 'invisible', 'translate-y-2');
      backToTopButton.classList.add('opacity-100', 'visible', 'translate-y-0');
    } else {
      backToTopButton.classList.add('opacity-0', 'invisible', 'translate-y-2');
      backToTopButton.classList.remove('opacity-100', 'visible', 'translate-y-0');
    }
  });

  function scrollToTop() {
    // Cancel any existing scroll animation
    if (window.scrollAnimation) {
      cancelAnimationFrame(window.scrollAnimation);
    }
    
    const startPosition = window.pageYOffset;
    const startTime = performance.now();
    const duration = 800; // 800ms for smooth animation
    
    function animateScroll(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      
      // Easing function for smooth acceleration and deceleration
      const easeInOutCubic = progress < 0.5
        ? 4 * progress * progress * progress
        : 1 - Math.pow(-2 * progress + 2, 3) / 2;
      
      const currentPosition = startPosition * (1 - easeInOutCubic);
      
      window.scrollTo(0, currentPosition);
      
      if (progress < 1) {
        window.scrollAnimation = requestAnimationFrame(animateScroll);
      } else {
        window.scrollAnimation = null;
      }
    }
    
    window.scrollAnimation = requestAnimationFrame(animateScroll);
  }
</script>
