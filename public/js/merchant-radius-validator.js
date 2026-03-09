/**
 * Multiple Google Maps Locations Radius Checking
 * Handles radius validation for multiple merchant locations
 */

class MerchantRadiusValidator {
    constructor(merchantData) {
        this.merchantData = merchantData;
        this.userLocation = null;
        this.matchedLocation = null;
        this.gmapsLocations = [];
        
        // Load gmaps locations from merchant data
        this.loadGmapsLocations();
        
        // Set default isWithinRadius based on whether radius validation is needed
        const hasAnyRadiusValidation = this.gmapsLocations.some(loc => loc.radius);
        this.isWithinRadius = !hasAnyRadiusValidation; // false if radius validation needed, true if not needed
    }

    /**
     * Load gmaps locations dari merchant data
     */
    loadGmapsLocations() {
        // Check if merchant has new link_gmaps format (array)
        if (this.merchantData.link_gmaps && Array.isArray(this.merchantData.link_gmaps)) {
            this.gmapsLocations = this.merchantData.link_gmaps;
        } 
        // Fallback to old link_gmap format
        else if (this.merchantData.link_gmap) {
            this.gmapsLocations = [{
                link: this.merchantData.link_gmap,
                radius: this.merchantData.radius || null
            }];
        }
        
        console.log('📍 Loaded gmaps locations:', this.gmapsLocations);
    }

    /**
     * Extract coordinates dari Google Maps link
     */
    extractCoordinatesFromGmapsLink(gmapLink) {
        if (!gmapLink) return null;
        
        // Pattern 1: https://www.google.com/maps?q=lat,lng
        let match = gmapLink.match(/[?&]q=([-\d.]+),([-\d.]+)/);
        if (match) {
            return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
        }
        
        // Pattern 2: https://www.google.com/maps/@lat,lng,zoom
        match = gmapLink.match(/@([-\d.]+),([-\d.]+),/);
        if (match) {
            return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
        }
        
        // Pattern 3: https://maps.google.com/?q=lat,lng
        match = gmapLink.match(/[?&]q=([-\d.]+),([-\d.]+)/);
        if (match) {
            return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
        }
        
        // Pattern 4: https://www.google.com/maps/place/@lat,lng
        match = gmapLink.match(/place\/@?([-\d.]+),([-\d.]+)/);
        if (match) {
            return { lat: parseFloat(match[1]), lng: parseFloat(match[2]) };
        }
        
        return null;
    }

    /**
     * Calculate distance antara dua koordinat menggunakan Haversine formula (dalam meter)
     */
    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth's radius in meters
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; // Distance in meters
    }

    /**
     * Check if user is within radius of any merchant location
     */
    checkIfWithinAnyRadius(userLat, userLng) {
        this.userLocation = { lat: userLat, lng: userLng };
        this.isWithinRadius = false;
        this.matchedLocation = null;
        let closestDistance = null;

        for (let i = 0; i < this.gmapsLocations.length; i++) {
            const location = this.gmapsLocations[i];
            
            // Extract coordinates
            const coords = this.extractCoordinatesFromGmapsLink(location.link);
            if (!coords) {
                console.warn(`⚠️ Cannot extract coordinates from location ${i}:`, location.link);
                // Jika tidak bisa extract koordinat, allow redeem
                this.isWithinRadius = true;
                if (!this.matchedLocation) {
                    this.matchedLocation = {
                        ...location,
                        index: i,
                        distance: null,
                        withinRadius: true
                    };
                }
                continue;
            }

            // Calculate distance
            const distance = this.calculateDistance(userLat, userLng, coords.lat, coords.lng);
            const radius = location.radius || null;

            console.log(`📏 Location ${i} distance: ${Math.round(distance)}m, radius: ${radius}`);

            // If location has no radius, allow redeem
            if (!radius) {
                this.isWithinRadius = true;
                if (!this.matchedLocation) {
                    this.matchedLocation = {
                        ...location,
                        index: i,
                        distance: distance,
                        withinRadius: true
                    };
                }
                continue;
            }

            // Check if within radius
            if (distance <= radius) {
                this.isWithinRadius = true;
                // Keep track of closest matching location
                if (!this.matchedLocation || distance < closestDistance) {
                    closestDistance = distance;
                    this.matchedLocation = {
                        ...location,
                        index: i,
                        distance: distance,
                        withinRadius: true
                    };
                }
            }
        }

        console.log('✅ Radius check result:', {
            isWithinRadius: this.isWithinRadius,
            matchedLocation: this.matchedLocation
        });

        return {
            isWithinRadius: this.isWithinRadius,
            matchedLocation: this.matchedLocation,
            allLocations: this.gmapsLocations.map((loc, i) => {
                const coords = this.extractCoordinatesFromGmapsLink(loc.link);
                if (!coords || !this.userLocation) return null;
                
                const distance = this.calculateDistance(
                    this.userLocation.lat,
                    this.userLocation.lng,
                    coords.lat,
                    coords.lng
                );
                
                return {
                    index: i,
                    link: loc.link,
                    radius: loc.radius,
                    distance: distance,
                    withinRadius: !loc.radius || distance <= loc.radius
                };
            }).filter(Boolean)
        };
    }

    /**
     * Get formatted distance text
     */
    getFormattedDistance(distance) {
        if (!distance) return 'tidak diketahui';
        if (distance < 1000) {
            return Math.round(distance) + ' meter';
        }
        return (distance / 1000).toFixed(2) + ' km';
    }

    /**
     * Get closest location within radius
     */
    getClosestLocationWithinRadius() {
        if (!this.userLocation) return null;

        let closest = null;
        let closestDistance = Infinity;

        for (let i = 0; i < this.gmapsLocations.length; i++) {
            const location = this.gmapsLocations[i];
            const coords = this.extractCoordinatesFromGmapsLink(location.link);
            
            if (!coords) continue;

            const distance = this.calculateDistance(
                this.userLocation.lat,
                this.userLocation.lng,
                coords.lat,
                coords.lng
            );

            if (location.radius && distance <= location.radius && distance < closestDistance) {
                closestDistance = distance;
                closest = {
                    ...location,
                    index: i,
                    distance: distance
                };
            }
        }

        return closest;
    }

    /**
     * Get all locations sorted by distance
     */
    getLocationsSortedByDistance() {
        if (!this.userLocation) return [];

        return this.gmapsLocations.map((location, i) => {
            const coords = this.extractCoordinatesFromGmapsLink(location.link);
            if (!coords) return null;

            const distance = this.calculateDistance(
                this.userLocation.lat,
                this.userLocation.lng,
                coords.lat,
                coords.lng
            );

            return {
                ...location,
                index: i,
                distance: distance,
                withinRadius: !location.radius || distance <= location.radius
            };
        }).filter(Boolean).sort((a, b) => a.distance - b.distance);
    }

    /**
     * Format location info untuk display
     */
    formatLocationInfo(location) {
        if (!location) return 'Tidak ada informasi lokasi';

        let info = `Lokasi ${location.index + 1}`;
        
        if (this.userLocation) {
            info += ` - ${this.getFormattedDistance(location.distance)}`;
        }

        if (location.radius) {
            info += ` (radius: ${location.radius}m)`;
        }

        return info;
    }

    /**
     * Get error message untuk user
     */
    getErrorMessage() {
        if (!this.userLocation) {
            return 'Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif dan izinkan akses lokasi.';
        }

        if (this.isWithinRadius) {
            return null; // No error
        }

        // User di luar semua radius
        const locationsSorted = this.getLocationsSortedByDistance();
        if (locationsSorted.length > 0) {
            const nearest = locationsSorted[0];
            return `Lokasi Anda terlalu jauh. Lokasi terdekat berjarak ${this.getFormattedDistance(nearest.distance)} ` +
                   `dari lokasi merchant (radius maksimal: ${nearest.radius || 'tidak ada batasan'}m)`;
        }

        return 'Anda berada di luar radius lokasi merchant yang diizinkan.';
    }

    /**
     * Get all gmaps links untuk ditampilkan
     */
    getAllGmapsLinks() {
        return this.gmapsLocations.map(location => location.link).filter(Boolean);
    }

    /**
     * Get primary gmaps link (first one with radius, atau first one)
     */
    getPrimaryGmapsLink() {
        // Prioritize location with radius
        const withRadius = this.gmapsLocations.find(loc => loc.radius);
        if (withRadius) return withRadius.link;
        
        // Otherwise return first link
        return this.gmapsLocations[0]?.link || null;
    }
}

// Global instance
let merchantValidator = null;

/**
 * Initialize radius validator dengan merchant data
 */
function initRadiusValidator(merchantData) {
    merchantValidator = new MerchantRadiusValidator(merchantData);
}

/**
 * Check user location dan update UI
 */
async function checkUserLocationAndUpdateUI() {
    if (!merchantValidator) {
        console.error('Radius validator not initialized');
        return;
    }

    // Check if there are any radius validations needed
    const hasAnyRadiusValidation = merchantValidator.gmapsLocations.some(loc => loc.radius);
    
    if (!hasAnyRadiusValidation) {
        console.log('✅ No radius validation needed');
        merchantValidator.isWithinRadius = true;
        updateRedeemButtons();
        return;
    }

    // Check if browser supports geolocation
    if (!navigator.geolocation) {
        console.log('❌ Browser does not support geolocation');
        merchantValidator.isWithinRadius = false;
        updateRedeemButtons();
        return;
    }

    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 30000
            });
        });

        const result = merchantValidator.checkIfWithinAnyRadius(
            position.coords.latitude,
            position.coords.longitude
        );

        updateRedeemButtons();
    } catch (error) {
        console.error('❌ Error getting location:', error);
        merchantValidator.isWithinRadius = false;
        updateRedeemButtons();
        // Tombol tetap dinonaktifkan tanpa menampilkan pesan error
    }
}

/**
 * Update all redeem buttons berdasarkan radius status
 */
function updateRedeemButtons() {
    if (!merchantValidator) return;

    const redeemButtons = document.querySelectorAll('[data-redeem-btn]');
    
    redeemButtons.forEach((btn) => {
        // Skip buttons yang keyword-nya tidak ikut lock longlat (is_lock_longlat = false)
        const lockLonglat = btn.getAttribute('data-lock-longlat');
        if (lockLonglat === '0') {
            // Keyword ini tidak ikut lock radius, selalu enable
            return;
        }

        if (!merchantValidator.isWithinRadius) {
            // Store original href and classes before converting to button
            const originalHref = btn.href || btn.getAttribute('href');
            const originalTarget = btn.target || btn.getAttribute('target');
            
            // Convert <a> to <button> when outside radius
            if (btn.tagName === 'A') {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = btn.className;
                button.setAttribute('data-redeem-btn', '');
                button.setAttribute('data-original-href', originalHref || '');
                button.setAttribute('data-original-target', originalTarget || '');
                
                // Copy all data attributes
                Array.from(btn.attributes).forEach(attr => {
                    if (attr.name.startsWith('data-') && attr.name !== 'data-redeem-btn') {
                        button.setAttribute(attr.name, attr.value);
                    }
                });
                
                btn.parentNode.replaceChild(button, btn);
                btn = button; // Update reference
            }
            
            btn.classList.remove('bg-gradient-to-r', 'from-orange-500', 'to-red-500', 'hover:from-orange-600', 'hover:to-red-600');
            btn.classList.add('bg-gray-400', 'cursor-pointer', 'hover:bg-gray-500');
            btn.innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i>Harus ke Lokasi';
            btn.title = 'Klik untuk melihat lokasi merchant';
            
            // Show location modal when clicked
            btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                showLocationsModal();
                return false;
            };
        } else {
            // Convert back to <a> when within radius
            if (btn.tagName === 'BUTTON' && btn.hasAttribute('data-original-href')) {
                const link = document.createElement('a');
                link.href = btn.getAttribute('data-original-href');
                const originalTarget = btn.getAttribute('data-original-target');
                if (originalTarget) {
                    link.target = originalTarget;
                }
                link.className = btn.className;
                link.setAttribute('data-redeem-btn', '');
                
                // Copy all data attributes except the ones we added
                Array.from(btn.attributes).forEach(attr => {
                    if (attr.name.startsWith('data-') && 
                        attr.name !== 'data-original-href' && 
                        attr.name !== 'data-original-target') {
                        link.setAttribute(attr.name, attr.value);
                    }
                });
                
                btn.parentNode.replaceChild(link, btn);
                btn = link; // Update reference
            }
            
            btn.disabled = false;
            btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btn.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-red-500', 'hover:from-orange-600', 'hover:to-red-600');
            btn.innerHTML = 'Redeem';
            btn.title = '';
            btn.onclick = null; // Remove click handler
        }
    });
}

/**
 * Show modal with all merchant locations when user clicks "Harus ke Lokasi"
 */
function showLocationsModal() {
    if (!merchantValidator) return;

    const locations = merchantValidator.gmapsLocations;
    if (!locations || locations.length === 0) return;

    // Build list sorted by distance if we have user location
    const hasUserLocation = !!merchantValidator.userLocation;
    let sortedLocations = locations.map((loc, i) => {
        let distance = null;
        let withinRadius = false;
        if (hasUserLocation) {
            const coords = merchantValidator.extractCoordinatesFromGmapsLink(loc.link);
            if (coords) {
                distance = merchantValidator.calculateDistance(
                    merchantValidator.userLocation.lat,
                    merchantValidator.userLocation.lng,
                    coords.lat,
                    coords.lng
                );
            }
            withinRadius = !loc.radius || (distance !== null && distance <= loc.radius);
        }
        return { ...loc, index: i, distance, withinRadius };
    });

    if (hasUserLocation) {
        sortedLocations.sort((a, b) => {
            if (a.distance === null) return 1;
            if (b.distance === null) return -1;
            return a.distance - b.distance;
        });
    }

    const locationCards = sortedLocations.map((loc, cardIdx) => {
        const isNearest = cardIdx === 0 && hasUserLocation;
        const distanceText = loc.distance !== null
            ? merchantValidator.getFormattedDistance(loc.distance)
            : null;
        const radiusText = loc.radius ? loc.radius + ' m' : null;

        // Status badge
        let statusBadge = '';
        if (hasUserLocation) {
            if (loc.withinRadius) {
                statusBadge = `<span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Dalam Radius</span>`;
            } else {
                statusBadge = `<span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>Di Luar Radius</span>`;
            }
        }

        const nearestBadge = isNearest
            ? `<span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700">📍 Terdekat</span>`
            : '';

        return `
        <div class="rounded-2xl border ${ (hasUserLocation && loc.withinRadius) ? 'border-green-200 bg-green-50/50' : 'border-gray-200 bg-white' } p-4 shadow-sm">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">Lokasi ${loc.index + 1}</span>
                </div>
                <div class="flex flex-wrap gap-1 justify-end">${nearestBadge}${statusBadge}</div>
            </div>

            <div class="flex flex-wrap gap-3 mb-3 text-xs text-gray-600">
                ${distanceText ? `
                <div class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Jarak: <strong class="text-gray-800">${distanceText}</strong></span>
                </div>` : ''}
                ${radiusText ? `
                <div class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                    <span>Radius: <strong class="text-gray-800">${radiusText}</strong></span>
                </div>` : ''}
            </div>

            <a href="${loc.link}" target="_blank" rel="noopener noreferrer"
               class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-semibold transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                Buka di Google Maps
            </a>
        </div>`;
    }).join('');

    const headerNote = hasUserLocation
        ? `<p class="text-xs text-gray-500 text-center mb-4">Kunjungi salah satu lokasi di bawah ini untuk dapat melakukan redeem.</p>`
        : `<p class="text-xs text-gray-500 text-center mb-4">Aktifkan GPS agar kami bisa mendeteksi lokasi terdekat Anda.</p>`;

    const contentHTML = `<div class="px-4 pb-6 pt-1">${headerNote}<div class="space-y-3">${locationCards}</div></div>`;

    // Use existing openBottomSheet if available on the page
    if (typeof openBottomSheet === 'function') {
        openBottomSheet('📍 Lokasi Merchant', contentHTML);
    } else {
        // Fallback: standalone modal
        const existing = document.getElementById('_locationsModal');
        if (existing) existing.remove();

        const overlay = document.createElement('div');
        overlay.id = '_locationsModal';
        overlay.className = 'fixed inset-0 z-[10000] flex items-end md:items-center justify-center';
        overlay.style.cssText = 'background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);';
        overlay.innerHTML = `
            <div class="bg-white w-full max-w-lg rounded-t-3xl md:rounded-3xl shadow-2xl overflow-hidden" style="max-height:85vh;">
                <div class="bg-gradient-to-r from-orange-50 to-rose-50 px-5 py-4 flex items-center justify-between border-b border-neutral-100">
                    <h3 class="text-lg font-bold text-gray-800">📍 Lokasi Merchant</h3>
                    <button onclick="document.getElementById('_locationsModal').remove()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="overflow-y-auto" style="max-height:calc(85vh - 64px);">${contentHTML}</div>
            </div>`;
        overlay.addEventListener('click', function(e) { if (e.target === overlay) overlay.remove(); });
        document.body.appendChild(overlay);
    }
}
