<div id="preloader" style="display: none;">
    <div class="preloader-logo">
        <img src="<?php echo CFG_APP_URL; ?>/assets/images/logos/logo_avib2b_white.png" alt="Loading..." />
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.getElementById('preloader');
    
    // Show preloader
    function showPreloader() {
        preloader.style.display = 'flex';
    }
    
    // Hide preloader
    function hidePreloader() {
        preloader.style.display = 'none';
    }
    
    // Hide preloader when page is fully loaded
    window.addEventListener('load', hidePreloader);
    
    // Show preloader when navigating away
    window.addEventListener('beforeunload', showPreloader);
});
</script>
