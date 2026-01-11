<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchants';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'daerah',
        'nama_merchant',
        'kategori',
        'logo_merchant',
        'link_blanjapoin',
        'nama_pic',
        'wa_pic',
        'email_pic',
        'ktp_pic',
        'detail_daerah',
        'link_gmap',
        'link_gmaps',
        'radius',
        'is_active',
        'link_status',
        'start_date',
        'end_date',
        'diamond',
        'created_by',
    ];

    protected $casts = [
        'link_gmaps' => 'array',
    ];

    /**
     * Set link_gmaps attribute with proper JSON encoding (no escaped slashes)
     */
    public function setLinkGmapsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['link_gmaps'] = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } elseif (is_string($value)) {
            $this->attributes['link_gmaps'] = $value;
        } else {
            $this->attributes['link_gmaps'] = null;
        }
    }

    // Jangan cast lat dan long, biarkan sebagai string/decimal dari database
    // Ini mempertahankan format asli yang diinput user

    public function keywords()
    {
        return $this->hasMany(Keyword::class, 'merchant_key', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get all Google Maps links with their radius
     * Format: [{"link": "url", "radius": 500}, ...]
     */
    public function getGmapsLocations()
    {
        // Return link_gmaps jika ada
        if ($this->link_gmaps && is_array($this->link_gmaps) && count($this->link_gmaps) > 0) {
            return $this->link_gmaps;
        }

        return [];
    }

    /**
     * Tambah Google Maps location baru
     */
    public function addGmapsLocation($link, $radius = null)
    {
        $locations = $this->getGmapsLocations();
        
        $locations[] = [
            'link' => $link,
            'radius' => $radius
        ];

        $this->link_gmaps = $locations;
        $this->save();
    }

    /**
     * Update Google Maps location berdasarkan index
     */
    public function updateGmapsLocation($index, $link, $radius = null)
    {
        $locations = $this->getGmapsLocations();
        
        if (isset($locations[$index])) {
            $locations[$index] = [
                'link' => $link,
                'radius' => $radius
            ];
            
            $this->link_gmaps = $locations;
            $this->save();
        }
    }

    /**
     * Hapus Google Maps location berdasarkan index
     */
    public function removeGmapsLocation($index)
    {
        $locations = $this->getGmapsLocations();
        
        if (isset($locations[$index])) {
            unset($locations[$index]);
            $locations = array_values($locations); // Re-index array
            
            $this->link_gmaps = count($locations) > 0 ? $locations : null;
            $this->save();
        }
    }

    /**
     * Check if user is within radius of any merchant location
     * Mengembalikan array dengan info lokasi yang match
     */
    public function isUserWithinAnyRadius($userLat, $userLng)
    {
        $locations = $this->getGmapsLocations();
        $withinRadius = false;
        $closestDistance = null;
        $matchedLocation = null;

        foreach ($locations as $index => $location) {
            if (!isset($location['radius']) || $location['radius'] === null) {
                // Tidak ada validasi radius untuk lokasi ini
                $withinRadius = true;
                if (!$matchedLocation) {
                    $matchedLocation = array_merge($location, ['index' => $index, 'distance' => null]);
                }
                continue;
            }

            // Extract coordinates dari gmap link
            $coords = $this->extractCoordinatesFromGmapsLink($location['link']);
            if (!$coords) {
                // Jika tidak bisa extract, allow
                $withinRadius = true;
                if (!$matchedLocation) {
                    $matchedLocation = array_merge($location, ['index' => $index, 'distance' => null]);
                }
                continue;
            }

            // Hitung distance
            $distance = $this->calculateDistance($userLat, $userLng, $coords['lat'], $coords['lng']);

            // Check if within radius
            if ($distance <= $location['radius']) {
                $withinRadius = true;
                if (!$matchedLocation || $distance < $closestDistance) {
                    $closestDistance = $distance;
                    $matchedLocation = array_merge($location, [
                        'index' => $index,
                        'distance' => $distance,
                        'radius' => $location['radius']
                    ]);
                }
            }
        }

        return [
            'isWithinRadius' => $withinRadius,
            'matchedLocation' => $matchedLocation
        ];
    }

    /**
     * Extract coordinates dari Google Maps link
     */
    private function extractCoordinatesFromGmapsLink($gmapLink)
    {
        if (!$gmapLink) return null;

        // Pattern 1: https://www.google.com/maps?q=lat,lng
        if (preg_match('/[?&]q=([-\d.]+),([-\d.]+)/', $gmapLink, $match)) {
            return ['lat' => (float)$match[1], 'lng' => (float)$match[2]];
        }

        // Pattern 2: https://www.google.com/maps/@lat,lng,zoom
        if (preg_match('/@([-\d.]+),([-\d.]+),/', $gmapLink, $match)) {
            return ['lat' => (float)$match[1], 'lng' => (float)$match[2]];
        }

        // Pattern 3: https://maps.google.com/?q=lat,lng
        if (preg_match('/[?&]q=([-\d.]+),([-\d.]+)/', $gmapLink, $match)) {
            return ['lat' => (float)$match[1], 'lng' => (float)$match[2]];
        }

        // Pattern 4: https://www.google.com/maps/place/@lat,lng
        if (preg_match('/place\/@?([-\d.]+),([-\d.]+)/', $gmapLink, $match)) {
            return ['lat' => (float)$match[1], 'lng' => (float)$match[2]];
        }

        return null;
    }

    /**
     * Calculate distance antara dua koordinat menggunakan Haversine formula (dalam meter)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371000; // Earth's radius in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c; // Distance in meters
    }

    /**
     * Calculate total valid transactions for this merchant
     * Based on click history matching (anti-cheating system)
     */
    public function calculateTotalTrx()
    {
        // Get all keywords for this merchant (active or inactive)
        $keywords = $this->keywords()->get();
        
        $totalTrx = 0;
        
        foreach ($keywords as $keyword) {
            if (!$keyword->keyword_id) {
                continue;
            }
            
            // Get all redemptions for this keyword_id
            $redemptions = \DB::table('tokodigi_tselpoin_redeem')
                ->where('coupon', $keyword->keyword_id)
                ->where('program', 'BLANJAPOIN')
                ->get();
            
            foreach ($redemptions as $redemption) {
                // Find the closest click history entry for this redemption
                // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
                $matchingClick = \DB::table('click_history')
                    ->where('keyword_id', $redemption->coupon)
                    ->where('clicked_at', '<', $redemption->created_date) // Click must be before redeem
                    ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
                    ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}')) ASC") // Closest time difference
                    ->first();
                
                // If a matching click is found and its merchant_id matches this merchant's id
                if ($matchingClick && $matchingClick->merchant_id == $this->id) {
                    $totalTrx++;
                }
            }
        }
        
        return $totalTrx;
    }

}

