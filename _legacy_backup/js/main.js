/**
 * main.js - Site-wide logic for Hugo.gal
 * Manages modular header/footer loading and active link detection.
 */

document.addEventListener('DOMContentLoaded', () => {
    const scripts = document.getElementsByTagName('script');
    const currentScript = scripts[scripts.length - 1];
    
    // Detect base URL relative to this script or current page
    // For simplicity, we assume pages use a data attribute 'data-depth'
    const body = document.body;
    const depth = parseInt(body.getAttribute('data-depth') || '0');
    const baseUrl = '../'.repeat(depth);
    const activeNav = body.getAttribute('data-nav') || '';

    // Load Header
    const headerContainer = document.getElementById('header-container');
    if (headerContainer) {
        fetch(`${baseUrl}parts/header.html`)
            .then(response => response.text())
            .then(data => {
                headerContainer.innerHTML = data.replace(/{{BASE_URL}}/g, baseUrl);
                
                // Set active class
                if (activeNav) {
                    const activeLink = headerContainer.querySelector(`[data-nav="${activeNav}"]`);
                    if (activeLink) activeLink.classList.add('active');
                }
            })
            .catch(err => console.error('Error loading header:', err));
    }

    // Load Footer
    const footerContainer = document.getElementById('footer-container');
    if (footerContainer) {
        fetch(`${baseUrl}parts/footer.html`)
            .then(response => response.text())
            .then(data => {
                footerContainer.innerHTML = data;
            })
            .catch(err => console.error('Error loading footer:', err));
    }

    // Image responsiveness / accessibility check (optional polyfill/helper)
    document.querySelectorAll('img').forEach(img => {
        if (!img.getAttribute('alt')) {
            console.warn('Image missing alt text:', img.src);
        }
    });
});
