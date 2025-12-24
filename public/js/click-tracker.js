/**
 * BlanjaPoin Click Tracker
 * Track user clicks untuk anti-cheating detection
 */

(function() {
    'use strict';

    // Get CSRF token
    function getCsrfToken() {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        return tokenMeta ? tokenMeta.getAttribute('content') : '';
    }

    /**
     * Track click ke server
     * @param {number} merchantId - ID merchant
     * @param {string|null} keywordId - ID keyword (optional)
     * @returns {Promise}
     */
    window.trackClick = function(merchantId, keywordId = null) {
        return new Promise((resolve, reject) => {
            // Validate input
            if (!merchantId) {
                console.warn('trackClick: merchant_id is required');
                resolve(); // Don't block if missing
                return;
            }

            // Prepare data
            const data = {
                merchant_id: merchantId,
                keyword_id: keywordId
            };

            // Send tracking request
            fetch('/api/track-click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('✓ Click tracked:', {
                        merchant_id: merchantId,
                        keyword_id: keywordId,
                        click_id: result.click_id
                    });
                    resolve(result);
                } else {
                    console.warn('Track click response:', result);
                    resolve(result); // Don't block
                }
            })
            .catch(error => {
                console.error('Error tracking click:', error);
                resolve(); // Don't block user experience on error
            });
        });
    };

    /**
     * Track click dengan redirect otomatis
     * @param {number} merchantId 
     * @param {string|null} keywordId 
     * @param {string} redirectUrl 
     */
    window.trackAndRedirect = function(merchantId, keywordId, redirectUrl) {
        // Track click
        window.trackClick(merchantId, keywordId)
            .finally(() => {
                // Redirect regardless of tracking success
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
    };

    /**
     * Auto-attach tracking to elements with data attributes
     * Usage: <a href="..." data-track-merchant="123" data-track-keyword="ABC">
     */
    function autoAttachTracking() {
        // Find all elements with data-track-merchant
        const trackableElements = document.querySelectorAll('[data-track-merchant]');
        
        trackableElements.forEach(element => {
            // Skip if already attached
            if (element.hasAttribute('data-tracking-attached')) {
                return;
            }

            element.setAttribute('data-tracking-attached', 'true');
            
            element.addEventListener('click', function(event) {
                const merchantId = this.getAttribute('data-track-merchant');
                const keywordId = this.getAttribute('data-track-keyword');
                const targetUrl = this.getAttribute('href') || this.getAttribute('data-track-url');
                const trackOnly = this.hasAttribute('data-track-only');

                // If should redirect after tracking
                if (targetUrl && !trackOnly) {
                    event.preventDefault();
                    window.trackAndRedirect(merchantId, keywordId, targetUrl);
                } else {
                    // Just track, don't prevent default
                    window.trackClick(merchantId, keywordId);
                }
            });
        });
    }

    // Auto-attach on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoAttachTracking);
    } else {
        autoAttachTracking();
    }

    // Re-attach for dynamically loaded content
    window.reattachClickTracking = autoAttachTracking;

    // Expose for debugging
    window.BlanjaPoinTracker = {
        track: window.trackClick,
        trackAndRedirect: window.trackAndRedirect,
        reattach: autoAttachTracking
    };

})();

