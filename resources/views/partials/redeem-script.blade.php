{{-- Partial Script untuk Logika Redeem Aman (reCAPTCHA + Hard Limit + Spam Check + Geolocation + Desktop Check) --}}
<script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
<div id="recaptcha-container"></div>

<script>
    var recaptchaWidgetId; // Gunakan var agar tidak error jika redeclare
    var isRecaptchaProcessing = false;

    // Flag Error Permanen untuk mencegah spam alert
    let hasRecaptchaErrorOccurred = false;

    // Callback global yang dipanggil saat script Google reCAPTCHA selesai dimuat
    window.onRecaptchaLoad = function() {
        const container = document.getElementById('recaptcha-container');
        if (container) {
            // Render hanya jika belum ada widget ID
            if (typeof recaptchaWidgetId === 'undefined') {
                try {
                    recaptchaWidgetId = grecaptcha.render('recaptcha-container', {
                        'sitekey': '{{ config('services.recaptcha.site_key') }}',
                        'size': 'invisible', // KEMBALIKAN KE INVISIBLE
                        // 'badge': 'bottomright', // Badge tidak dipakai di mode normal
                        'callback': function(token) {
                            hasRecaptchaErrorOccurred = false; // Reset error flag on success
                            isRecaptchaProcessing = false;
                            window.isRedeemGlobalProcessing = false;
                            if (window.currentRedeemCallback) {
                                window.currentRedeemCallback(token);
                            }
                            // Reset segera agar siap dipakai lagi
                            grecaptcha.reset(recaptchaWidgetId);
                        },
                        'error-callback': function() {
                            window.isRedeemGlobalProcessing = false;
                            
                            // Fail Open: Jika reCAPTCHA error (misal 400 Bad Request atau koneksi putus),
                            // TETAP izinkan user masuk (Redirect tanpa token).
                            
                            // Reset state tombol dulu
                            if (typeof resetButtonState === 'function') resetButtonState();
                            
                            // Lanjut redirect (tanpa token)
                            if (window.currentErrorCallback) {
                                // Panggil doRedirect lewat callback yang sudah disiapkan
                                window.currentErrorCallback(); 
                            }
                        }
                    });
                } catch (e) {
                    // Silent fail
                }
            }
        }
    };

    // Global Processing Flag untuk mencegah multiple execution / loop
    window.isRedeemGlobalProcessing = false;

    // Fungsi helper untuk menampilkan alert desktop (perlu disesuaikan jika modal desktop ada di layout utama)
    function showDesktopAlert() {
        if (typeof openDesktopModal === 'function') {
            openDesktopModal();
        } else {
            alert('Fitur ini hanya tersedia di perangkat mobile.');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Prevent Multiple Listeners
        if (window.redeemListenerAttached) return;
        window.redeemListenerAttached = true;

        // Intercept Redeem Buttons
        // Selector '[data-redeem-btn]' harus ada di setiap tombol redeem di view lain!
        // Jika view lain pakai onclick="handleRedeemClick", kita harus sesuaikan.
        // Tapi pendekatan event listener ini lebih modern dan bersih.
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-redeem-btn]');
            if (!btn) return;

            // Stop immediate execution if global processing is active
            if (window.isRedeemGlobalProcessing) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Blocked by global processing flag');
                return;
            }

            e.preventDefault();
            e.stopPropagation(); 
            e.stopImmediatePropagation(); 

            // Set global flag
            window.isRedeemGlobalProcessing = true;
            
            // Auto reset flag after 2 seconds (safety valve)
            setTimeout(() => { 
                window.isRedeemGlobalProcessing = false; 
            }, 2000);

            const originalUrl = btn.href;

            // 1. Check Desktop (Allow only Mobile & Tablet)
            // Logic:
            // - Android/iPhone/iPod/BlackBerry/IEMobile/Opera Mini -> Mobile (Allow)
            // - iPad/MacIntel with Touch -> Tablet (Allow)
            // - Macintosh without Touch -> Desktop (Block)
            // - Windows/Linux -> Desktop (Block)
            
            const ua = navigator.userAgent;
            const isTouchDevice = (navigator.maxTouchPoints && navigator.maxTouchPoints > 0);
            
            // Check for standard mobile User Agents
            const isStandardMobile = /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
            
            // Check for iPad (Old & New iPadOS)
            // New iPadOS sends "Macintosh" as UA, but has touch points.
            const isIPad = /iPad/i.test(ua) || (ua.includes('Macintosh') && isTouchDevice);

            const isMobileOrTablet = isStandardMobile || isIPad || window.innerWidth <= 768;

            if (!isMobileOrTablet) {
                showDesktopAlert();
                window.isRedeemGlobalProcessing = false; // Reset flag
                return;
            }

            // 2. Get Location & ReCAPTCHA
            // Save original content
            if (!btn.hasAttribute('data-original-text')) {
                btn.setAttribute('data-original-text', btn.innerHTML);
            }
            btn.innerHTML = '<span class="animate-spin inline-block w-3 h-3 border-2 border-white rounded-full border-t-transparent"></span> Processing...';
            btn.style.pointerEvents = 'none';

            // Hard Limit untuk mencegah banjir tab
            let lastRedirectTime = 0;

            // Helper untuk reset tombol otomatis jika macet
            window.resetButtonState = function() {
                 if (btn.hasAttribute('data-original-text')) {
                     btn.innerHTML = btn.getAttribute('data-original-text');
                 }
                 btn.style.pointerEvents = '';
                 // delete btn.dataset.processing; // Optional
            }

            const doRedirect = (token, lat, lng) => {
                 // CEK WAKTU: Jika redirect terakhir kurang dari 3 detik lalu, STOP.
                 const now = Date.now();
                 if (now - lastRedirectTime < 3000) {
                     console.warn('Redirect diblokir: Terlalu cepat (Spam/Loop protection)');
                     resetButtonState();
                     return;
                 }
                 lastRedirectTime = now;

                 const separator = originalUrl.includes('?') ? '&' : '?';
                 let newUrl = `${originalUrl}${separator}`;
                 if (token) newUrl += `g_recaptcha_response=${token}`;
                 if (lat && lng) {
                     newUrl += `&lat=${lat}&long=${lng}`;
                 }
                 
                 // Detect iOS (iPhone/iPad) & Mac to fix popup blocker issues
                 // iPadOS 13+ often pretends to be Macintosh, so we include that too.
                 const isApple = /iPad|iPhone|iPod|Macintosh/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
                 
                 // On Apple devices (Safari/WebKit), window.open inside async callbacks is often blocked.
                 // We use window.location.href to ensure redirect happens.
                 if (isApple) {
                     window.location.href = newUrl;
                 } else {
                     // For Android/Windows, try opening in new tab
                     window.open(newUrl, '_blank');
                 }
                 
                 // Reset button
                 resetButtonState();
                 
                 // Reset Global Flag after short delay
                 setTimeout(() => {
                     window.isRedeemGlobalProcessing = false;
                 }, 500);
            };

            // Main Execution Flow
            const runRedeemFlow = (lat, lng) => {
                // LOGIKA PRODUCTION:
                // Cek interval klik. Jika user klik santai (> 5 detik), anggap aman (bypass reCAPTCHA).
                // Jika spamming (< 5 detik), baru panggil reCAPTCHA.
                
                const lastClickTime = sessionStorage.getItem('lastRedeemClickTime');
                const now = Date.now();
                const isSpamming = lastClickTime && (now - parseInt(lastClickTime) < 5000); // 5 detik threshold
                sessionStorage.setItem('lastRedeemClickTime', now);

                // Set callbacks for this specific click
                window.currentRedeemCallback = function(token) {
                    doRedirect(token, lat, lng);
                };
                window.currentErrorCallback = function() {
                    // Fail Open: Tetap redirect meskipun error
                    doRedirect('', lat, lng);
                };

                // Bypass jika tidak spamming (User Santai)
                if (!isSpamming) {
                    doRedirect('', lat, lng);
                    return;
                }

                // Jika spamming, panggil reCAPTCHA
                if (typeof grecaptcha !== 'undefined' && typeof recaptchaWidgetId !== 'undefined') {
                    try {
                        grecaptcha.reset(recaptchaWidgetId);
                        grecaptcha.execute(recaptchaWidgetId);
                    } catch(err) {
                        // Fail Open jika error teknis
                        doRedirect('', lat, lng);
                    }
                } else {
                    // Jika reCAPTCHA belum siap, jangan langsung bypass (karena user ingin strict)
                    // Coba tunggu sebentar
                    console.log('reCAPTCHA not ready, waiting...');
                    btn.innerHTML = '<span class="animate-spin inline-block w-3 h-3 border-2 border-white rounded-full border-t-transparent"></span> Processing...';
                    
                    setTimeout(() => {
                        if (typeof grecaptcha !== 'undefined' && typeof recaptchaWidgetId !== 'undefined') {
                            try {
                                grecaptcha.reset(recaptchaWidgetId);
                                grecaptcha.execute(recaptchaWidgetId);
                            } catch(err) { doRedirect('', lat, lng); }
                        } else {
                            // Give up, fail open
                            doRedirect('', lat, lng);
                        }
                    }, 1500);
                }
            };

            // Safety Valve: Apapun yang terjadi, tombol harus kembali aktif dalam 5 detik
            setTimeout(() => {
                resetButtonState();
                window.isRedeemGlobalProcessing = false;
            }, 5000);

            // Get Location first
            if (navigator.geolocation) {
                 navigator.geolocation.getCurrentPosition(
                     (position) => {
                         runRedeemFlow(position.coords.latitude, position.coords.longitude);
                     },
                     (error) => {
                         console.warn('Location denied or error:', error);
                         runRedeemFlow(null, null);
                     }
                 );
            } else {
                 runRedeemFlow(null, null);
            }
        });
    });

    // Fallback function for views that still use onclick="handleRedeemClick(...)"
    // This bridges the gap for old buttons to use the new flow
    function handleRedeemClick(redeemUrl, merchantId = null, keywordId = null) {
        // Create a fake link element to trigger the event listener
        const link = document.createElement('a');
        link.href = redeemUrl;
        link.setAttribute('data-redeem-btn', 'true');
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        setTimeout(() => link.remove(), 100);
    }
</script>