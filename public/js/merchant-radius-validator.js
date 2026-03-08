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
            
            btn.disabled = true;
            btn.classList.remove('bg-gradient-to-r', 'from-orange-500', 'to-red-500', 'hover:from-orange-600', 'hover:to-red-600');
            btn.classList.add('bg-gray-400', 'cursor-not-allowed');
            btn.innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i>Harus ke Lokasi';
            btn.title = merchantValidator.getErrorMessage() || 'Anda harus berada dalam radius yang ditentukan';
            
            // Prevent any click events (tidak menampilkan pesan error)
            btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
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
 * Show modal ketika user di luar radius
 */
function showLocationErrorModal(message) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4';
    
    const locations = merchantValidator.getLocationsSortedByDistance();
    const nearestLocation = locations[0];
    
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full">
            <button onclick="this.closest('.fixed').remove()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div class="p-6 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-3xl text-white"></i>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-3">Lokasi Terlalu Jauh! 🚫</h3>

                <div class="text-sm text-gray-600 mb-6 space-y-2">
                    <p class="font-medium text-red-600">${message}</p>
                    ${nearestLocation ? `
                        <p class="text-xs text-gray-500">
                            Lokasi terdekat berjarak <strong>${merchantValidator.getFormattedDistance(nearestLocation.distance)}</strong>
                        </p>
                    ` : ''}
                </div>

                <div class="space-y-3">
                    ${merchantValidator.getPrimaryGmapsLink() ? `
                        <a href="${merchantValidator.getPrimaryGmapsLink()}"
                           target="_blank"
                           class="w-full inline-block px-6 py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition-all shadow-lg">
                            <i class="fas fa-map-marker-alt mr-2"></i>Lihat Lokasi Merchant
                        </a>
                    ` : ''}
                    <button onclick="this.closest('.fixed').remove()"
                            class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-700 transition-all shadow-lg">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    
    // Auto remove after 8 seconds
    setTimeout(() => {
        if (modal.parentNode) {
            modal.remove();
        }
    }, 8000);
}
