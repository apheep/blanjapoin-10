<?php

namespace App\Http\Controllers;

use App\Models\ClickHistory;
use App\Models\Merchant;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClickHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $searchKeyword = $request->get('search');
        $merchantId = $request->get('merchant_id');
        $keywordId = $request->get('keyword_id');
        $date = $request->get('date');
        $matchStatus = $request->get('match_status'); // 'matched', 'unmatched', 'all'
        $sortBy = $request->get('sort', 'clicked_at');
        $sortDir = $request->get('dir', 'desc');
        
        // Base query untuk click history dengan relasi
        $query = ClickHistory::with(['merchant', 'keyword'])
            ->select('click_history.*');

        // Apply filters
        if ($searchKeyword) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('ip_address', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('device_id', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('keyword_id', 'like', '%' . $searchKeyword . '%');
            });
        }

        if ($merchantId) {
            $query->where('merchant_id', $merchantId);
        }

        if ($keywordId) {
            $query->where('keyword_id', $keywordId);
        }

        if ($date) {
            $query->whereDate('clicked_at', $date);
        }

        // Apply sorting - All sorting done at query level for all data
        if ($sortBy === 'merchant') {
            $query->join('merchants', 'click_history.merchant_id', '=', 'merchants.id')
                  ->select('click_history.*', 'merchants.nama_merchant') // Include nama_merchant in SELECT for DISTINCT compatibility
                  ->orderBy('merchants.nama_merchant', $sortDir);
        } elseif ($sortBy === 'clicked_at') {
            $query->orderBy('clicked_at', $sortDir);
        } elseif ($sortBy === 'status') {
            // Sort by status: use subquery to check if matched_redeem exists
            // Matched = has redeem with matching keyword_id and created_date > clicked_at
            // This matches the logic in findMatchingRedeem()
            // Note: Not Matched sorting will be done after data is loaded
            $query->selectRaw('click_history.*, 
                CASE WHEN EXISTS (
                    SELECT 1 FROM tokodigi_tselpoin_redeem as tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                ) THEN 1 ELSE 0 END as has_match')
            ->orderBy('has_match', $sortDir)
            ->orderBy('click_history.clicked_at', 'desc'); // Secondary sort
        } else {
            $query->orderBy('clicked_at', 'desc');
        }

        // Calculate total Matched and Unmatched from ALL data (not just current page)
        // Create a separate query for statistics (without pagination)
        $statsQuery = ClickHistory::select('click_history.*');
        
        // Apply same filters to stats query
        if ($searchKeyword) {
            $statsQuery->where(function($q) use ($searchKeyword) {
                $q->where('ip_address', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('device_id', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('keyword_id', 'like', '%' . $searchKeyword . '%');
            });
        }

        if ($merchantId) {
            $statsQuery->where('merchant_id', $merchantId);
        }

        if ($keywordId) {
            $statsQuery->where('keyword_id', $keywordId);
        }

        if ($date) {
            $statsQuery->whereDate('clicked_at', $date);
        }

        // Get all click histories for statistics (without pagination)
        $allClickHistories = $statsQuery->get();
        
        // Calculate matched, unmatched, and not matched counts from all data
        $totalMatched = 0;
        $totalUnmatched = 0;
        $totalNotMatched = 0;
        
        foreach ($allClickHistories as $clickHistory) {
            $matchedRedeem = $this->findMatchingRedeem($clickHistory);
            if ($matchedRedeem) {
                $totalMatched++;
                // Cek apakah ada not matched redeem
                $notMatchedRedeem = $this->findNotMatchedRedeem($clickHistory, $matchedRedeem->msisdn);
                if ($notMatchedRedeem) {
                    $totalNotMatched++;
                }
            } else {
                $totalUnmatched++;
            }
        }

        // Paginate - sorting sudah dilakukan di query level untuk semua data
        // Remove distinct() as it's not needed and causes issues with ORDER BY
        $clickHistories = $query->paginate(20)->appends($request->query());

        // Untuk setiap click history, tentukan apakah Matched atau Not Matched
        // Logika: Untuk MSISDN + keyword yang sama, hanya yang time diff terkecil yang Matched
        foreach ($clickHistories as $clickHistory) {
            $matchedRedeem = $this->findMatchingRedeem($clickHistory);
            $clickHistory->matched_redeem = $matchedRedeem;
            
            // Cari redemption dengan time diff terbesar (Not Matched) untuk MSISDN yang sama
            if ($matchedRedeem) {
                $notMatchedRedeem = $this->findNotMatchedRedeem($clickHistory, $matchedRedeem->msisdn);
                $clickHistory->not_matched_redeem = $notMatchedRedeem;
                
                // Set status_order untuk sorting: Matched=2, Not Matched=1, Unmatched=0
                if ($notMatchedRedeem) {
                    $clickHistory->status_order = 1; // Not Matched
                } else {
                    $clickHistory->status_order = 2; // Matched
                }
            } else {
                $clickHistory->not_matched_redeem = null;
                $clickHistory->status_order = 0; // Unmatched
            }
        }
        
        // Note: Status sorting dengan Not Matched akan dilakukan di view level
        // karena perlu data not_matched_redeem yang baru diketahui setelah loop

        // Get all merchants and keywords for filter dropdowns (termasuk yang inactive)
        $merchants = Merchant::orderBy('nama_merchant')->get();
        $keywords = Keyword::orderBy('keyword_id')->get();

        // If AJAX request, return full page HTML (we'll extract table part in JS)
        // This is simpler than creating separate partials

        return view('click-history.index', [
            'clickHistories' => $clickHistories,
            'merchants' => $merchants,
            'keywords' => $keywords,
            'totalMatched' => $totalMatched,
            'totalUnmatched' => $totalUnmatched,
            'totalNotMatched' => $totalNotMatched,
            'filters' => [
                'search' => $searchKeyword,
                'merchant_id' => $merchantId,
                'keyword_id' => $keywordId,
                'date' => $date,
                'match_status' => $matchStatus,
                'sort' => $sortBy,
                'dir' => $sortDir,
            ]
        ]);
    }

    /**
     * Cari redeem yang paling cocok dengan click history
     * Konsep: Klik dulu → Redeem kemudian
     * Cocokkan keyword, ambil selisih waktu paling dekat
     */
    private function findMatchingRedeem($clickHistory)
    {
        if (!$clickHistory->keyword_id) {
            return null;
        }

        // Cari redeem yang:
        // 1. Keyword ID sama
        // 2. Redeem terjadi SETELAH click (created_date > clicked_at)
        // 3. Ambil yang selisih waktunya paling kecil
        // 4. Optional: IP address atau device_id sama (untuk validasi lebih ketat)
        
        $redeem = DB::table('tokodigi_tselpoin_redeem as tr')
            ->where('tr.coupon', $clickHistory->keyword_id)
            ->where('tr.program', 'BLANJAPOIN')
            ->where('tr.created_date', '>', $clickHistory->clicked_at)
            ->select(
                'tr.created_date',
                'tr.msisdn',
                'tr.keyword_desc',
                'tr.coupon as keyword_id',
                'tr.poin_redeem',
                DB::raw("TIMESTAMPDIFF(SECOND, '{$clickHistory->clicked_at}', tr.created_date) as time_diff_seconds"),
                DB::raw("TIMESTAMPDIFF(MICROSECOND, '{$clickHistory->clicked_at}', tr.created_date) as time_diff_microseconds")
            )
            ->orderBy('time_diff_microseconds', 'asc') // Lebih presisi dengan microsecond
            ->first();

        // Jika ditemukan, tambahkan informasi confidence berdasarkan time difference
        if ($redeem) {
            // Convert time_diff_seconds to human readable
            $redeem->time_diff_human = $this->secondsToHuman($redeem->time_diff_seconds);
            
            // Determine confidence level based on time difference only
            // (No IP/Device check karena tokodigi_tselpoin_redeem tidak punya field tersebut)
            // Note: Ada processing time yang perlu diperhitungkan
            if ($redeem->time_diff_seconds <= 300) {
                $redeem->confidence = 'high'; // ≤5 menit (termasuk processing time)
            } elseif ($redeem->time_diff_seconds <= 900) {
                $redeem->confidence = 'medium'; // ≤15 menit
            } else {
                $redeem->confidence = 'low'; // >15 menit (kemungkinan besar sharelink)
            }
        }

        return $redeem;
    }

    /**
     * Cari redemption dengan time diff terbesar (Not Matched) untuk MSISDN yang sama
     * Digunakan untuk menampilkan redemption yang tidak match karena time diff terlalu lama
     * dari merchant yang berbeda
     */
    private function findNotMatchedRedeem($clickHistory, $msisdn)
    {
        if (!$clickHistory->keyword_id || !$msisdn) {
            return null;
        }

        // Cari semua redeem dengan MSISDN dan keyword_id yang sama
        // Ambil yang time diff paling lama (bukan yang paling kecil)
        // Ini untuk menampilkan redemption yang masuk ke merchant lain karena time diff lebih lama
        $allRedeems = DB::table('tokodigi_tselpoin_redeem as tr')
            ->where('tr.coupon', $clickHistory->keyword_id)
            ->where('tr.program', 'BLANJAPOIN')
            ->where('tr.msisdn', $msisdn)
            ->where('tr.created_date', '>', $clickHistory->clicked_at)
            ->select(
                'tr.created_date',
                'tr.msisdn',
                'tr.keyword_desc',
                'tr.coupon as keyword_id',
                'tr.poin_redeem',
                DB::raw("TIMESTAMPDIFF(SECOND, '{$clickHistory->clicked_at}', tr.created_date) as time_diff_seconds")
            )
            ->orderBy('time_diff_seconds', 'desc') // Paling lama (terbesar)
            ->get();

        // Ambil yang time diff terbesar (selain yang sudah matched)
        $notMatchedRedeem = null;
        foreach ($allRedeems as $redeem) {
            // Cari merchant dari click yang paling sesuai dengan redemption ini (time diff terkecil)
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $redeem->keyword_id)
                ->where('clicked_at', '<', $redeem->created_date)
                ->select(
                    'merchant_id',
                    DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') as time_diff_seconds")
                )
                ->orderBy('time_diff_seconds', 'asc')
                ->first();
            
            // Jika merchant dari click tidak sama dengan merchant click history ini, ini adalah "Not Matched"
            if ($matchingClick && $matchingClick->merchant_id != $clickHistory->merchant_id) {
                $notMatchedRedeem = $redeem;
                break; // Ambil yang pertama (time diff terbesar)
            }
        }

        // Jika ditemukan, tambahkan informasi
        if ($notMatchedRedeem) {
            // Convert time_diff_seconds to human readable
            $notMatchedRedeem->time_diff_human = $this->secondsToHuman($notMatchedRedeem->time_diff_seconds);
            
            // Determine confidence level based on time difference
            if ($notMatchedRedeem->time_diff_seconds <= 300) {
                $notMatchedRedeem->confidence = 'high';
            } elseif ($notMatchedRedeem->time_diff_seconds <= 900) {
                $notMatchedRedeem->confidence = 'medium';
            } else {
                $notMatchedRedeem->confidence = 'low';
            }
            
            // Cari merchant dari click yang paling sesuai dengan redemption ini
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $notMatchedRedeem->keyword_id)
                ->where('clicked_at', '<', $notMatchedRedeem->created_date)
                ->select(
                    'merchant_id',
                    DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$notMatchedRedeem->created_date}') as time_diff_seconds")
                )
                ->orderBy('time_diff_seconds', 'asc')
                ->first();
            
            if ($matchingClick) {
                $merchant = DB::table('merchants')->where('id', $matchingClick->merchant_id)->first();
                $notMatchedRedeem->matched_merchant = $merchant;
            }
        }

        return $notMatchedRedeem;
    }

    /**
     * Convert seconds to human readable format
     */
    private function secondsToHuman($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' detik';
        } else if ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $secs = $seconds % 60;
            return $minutes . ' menit ' . $secs . ' detik';
        } else if ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return $hours . ' jam ' . $minutes . ' menit';
        } else {
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            return $days . ' hari ' . $hours . ' jam';
        }
    }

    /**
     * Match redemption dengan click history untuk determine correct merchant_id
     * Return: redemption dengan merchant_id dari click (selisih waktu paling dekat)
     */
    private function findMatchingClick($redemption)
    {
        if (!$redemption->keyword_id) {
            return null;
        }

        // Cari click yang:
        // 1. Keyword ID sama
        // 2. Click terjadi SEBELUM redeem (clicked_at < created_date)
        // 3. Ambil yang selisih waktunya paling kecil
        
        $click = DB::table('click_history')
            ->where('keyword_id', $redemption->keyword_id)
            ->where('clicked_at', '<', $redemption->created_date)
            ->select(
                'id',
                'merchant_id',
                'keyword_id',
                'ip_address',
                'device_id',
                'clicked_at',
                DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') as time_diff_seconds")
            )
            ->orderBy('time_diff_seconds', 'asc')
            ->first();

        if ($click) {
            // Add merchant info
            $merchant = DB::table('merchants')->where('id', $click->merchant_id)->first();
            $click->merchant = $merchant;
            
            // Add time diff human readable
            $click->time_diff_human = $this->secondsToHuman($click->time_diff_seconds);
            
            // Determine confidence level based on time difference only
            // Note: Ada processing time yang perlu diperhitungkan
            if ($click->time_diff_seconds <= 300) {
                $click->confidence = 'high'; // ≤5 menit (termasuk processing time)
            } elseif ($click->time_diff_seconds <= 900) {
                $click->confidence = 'medium'; // ≤15 menit
            } else {
                $click->confidence = 'low'; // >15 menit (kemungkinan besar sharelink)
            }
        }

        return $click;
    }

    /**
     * Show analytics/statistics page
     */
    public function analytics(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Total clicks vs total redeems
        $totalClicks = ClickHistory::whereBetween('clicked_at', [$startDate, $endDate])->count();
        
        $totalRedeems = DB::table('tokodigi_tselpoin_redeem')
            ->where('program', 'BLANJAPOIN')
            ->whereBetween('created_date', [$startDate, $endDate])
            ->count();

        // Clicks by merchant
        $clicksByMerchant = ClickHistory::with('merchant')
            ->select('merchant_id', DB::raw('COUNT(*) as total_clicks'))
            ->whereBetween('clicked_at', [$startDate, $endDate])
            ->groupBy('merchant_id')
            ->orderBy('total_clicks', 'desc')
            ->limit(10)
            ->get();

        // Most clicked keywords
        $clicksByKeyword = ClickHistory::with('keyword')
            ->select('keyword_id', DB::raw('COUNT(*) as total_clicks'))
            ->whereBetween('clicked_at', [$startDate, $endDate])
            ->groupBy('keyword_id')
            ->orderBy('total_clicks', 'desc')
            ->limit(10)
            ->get();

        return view('click-history.analytics', [
            'totalClicks' => $totalClicks,
            'totalRedeems' => $totalRedeems,
            'clicksByMerchant' => $clicksByMerchant,
            'clicksByKeyword' => $clicksByKeyword,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Show anonymous redeems (redemptions without matching click history)
     * Anonymous = redeem yang tidak ada matching click (termasuk manual insert)
     */
    public function anonymousRedeems(Request $request)
    {
        // Get filter parameters
        $searchKeyword = $request->get('search');
        $keywordId = $request->get('keyword_id');
        $date = $request->get('date');
        $sortBy = $request->get('sort', 'created_date');
        $sortDir = $request->get('dir', 'desc');

        // Get all redemptions (no merchant join needed for anonymous)
        $query = DB::table('tokodigi_tselpoin_redeem as tr')
            ->where('tr.program', 'BLANJAPOIN');

        // Filter: Anonymous = redemptions yang TIDAK punya matching click history
        // Anonymous = tidak ada click history dengan keyword_id yang sama dan clicked_at < created_date
        $query->whereNotExists(function($subquery) {
            $subquery->select(DB::raw(1))
                ->from('click_history')
                ->whereColumn('click_history.keyword_id', 'tr.coupon')
                ->whereColumn('click_history.clicked_at', '<', 'tr.created_date');
        });

        // Apply filters
        if ($searchKeyword) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('tr.msisdn', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('tr.coupon', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('tr.keyword_desc', 'like', '%' . $searchKeyword . '%');
            });
        }

        if ($keywordId) {
            $query->where('tr.coupon', $keywordId);
        }

        if ($date) {
            $query->whereDate('tr.created_date', $date);
        }

        // Apply sorting (only columns from tokodigi_tselpoin_redeem)
        if ($sortBy === 'created_date') {
            $query->orderBy('tr.created_date', $sortDir);
        } elseif ($sortBy === 'poin') {
            $query->orderBy('tr.poin_redeem', $sortDir);
        } elseif ($sortBy === 'coupon') {
            $query->orderBy('tr.coupon', $sortDir);
        } elseif ($sortBy === 'msisdn') {
            $query->orderBy('tr.msisdn', $sortDir);
        } else {
            $query->orderBy('tr.created_date', 'desc');
        }

        // Paginate - select all columns from tokodigi_tselpoin_redeem
        $anonymousRedeems = $query->select('tr.*')
            ->paginate(20)->appends($request->query());

        return view('click-history.anonymous-redeems', [
            'anonymousRedeems' => $anonymousRedeems,
            'filters' => [
                'search' => $searchKeyword,
                'keyword_id' => $keywordId,
                'date' => $date,
                'sort' => $sortBy,
                'dir' => $sortDir,
            ]
        ]);
    }

    /**
     * Track click dari user (dipanggil saat user klik merchant/keyword)
     * Dapat dipanggil dari controller lain atau API endpoint
     */
    public function trackClick(Request $request)
    {
        try {
            $merchantId = $request->input('merchant_id');
            $keywordId = $request->input('keyword_id');

            // Validate
            if (!$merchantId) {
                return response()->json(['error' => 'Merchant ID required'], 400);
            }

            // Get IP and Device ID
            $ipAddress = $this->getClientIP($request);
            $deviceId = $this->getClientID($request);

            // Record click history
            // Use Carbon with Asia/Jakarta timezone for consistency
            $clickHistory = ClickHistory::create([
                'merchant_id' => $merchantId,
                'keyword_id' => $keywordId,
                'ip_address' => $ipAddress,
                'device_id' => $deviceId,
                'clicked_at' => \Carbon\Carbon::now('Asia/Jakarta'),
                'user_agent' => $request->header('User-Agent'),
                'referer' => $request->header('Referer'),
            ]);

            Log::info('Click tracked', [
                'click_id' => $clickHistory->id,
                'merchant_id' => $merchantId,
                'keyword_id' => $keywordId,
                'ip' => $ipAddress,
                'device' => $deviceId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully',
                'click_id' => $clickHistory->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error tracking click: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to track click'], 500);
        }
    }

    /**
     * Static method untuk track click dari controller lain
     * Usage: ClickHistoryController::recordClick($merchantId, $keywordId, $request);
     */
    public static function recordClick($merchantId, $keywordId = null, Request $request)
    {
        try {
            $controller = new self();
            $ipAddress = $controller->getClientIP($request);
            $deviceId = $controller->getClientID($request);

            $clickHistory = ClickHistory::create([
                'merchant_id' => $merchantId,
                'keyword_id' => $keywordId,
                'ip_address' => $ipAddress,
                'device_id' => $deviceId,
                'clicked_at' => \Carbon\Carbon::now('Asia/Jakarta'),
                'user_agent' => $request->header('User-Agent'),
                'referer' => $request->header('Referer'),
            ]);

            Log::info('Click recorded', [
                'click_id' => $clickHistory->id,
                'merchant_id' => $merchantId,
                'keyword_id' => $keywordId,
            ]);

            return $clickHistory;

        } catch (\Exception $e) {
            Log::error('Error recording click: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the client IP address
     */
    protected function getClientIP(Request $request)
    {
        // Try to get from request first (Laravel way)
        $ip = $request->ip();
        
        // If not reliable, use server variables
        if ($ip === '127.0.0.1' || $ip === '::1') {
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                return $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                return $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                return $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                return $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                return $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip ?: 'UNKNOWN';
    }

    /**
     * Show detail komparasi Not Matched - MSISDN yang sama dengan matched dan not matched
     */
    public function notMatchedDetail(Request $request)
    {
        // Get filter parameters
        $searchKeyword = $request->get('search');
        $merchantId = $request->get('merchant_id');
        $date = $request->get('date');

        // Get all click histories yang memiliki matched_redeem dan not_matched_redeem
        $clickHistories = ClickHistory::with(['merchant', 'keyword'])
            ->select('click_history.*')
            ->get();

        // Process untuk mendapatkan data komparasi
        $comparisons = [];
        
        foreach ($clickHistories as $clickHistory) {
            $matchedRedeem = $this->findMatchingRedeem($clickHistory);
            
            if ($matchedRedeem) {
                $notMatchedRedeem = $this->findNotMatchedRedeem($clickHistory, $matchedRedeem->msisdn);
                
                if ($notMatchedRedeem) {
                    // Group by MSISDN + keyword_id
                    $key = $matchedRedeem->msisdn . '_' . $clickHistory->keyword_id;
                    
                    if (!isset($comparisons[$key])) {
                        $comparisons[$key] = [
                            'msisdn' => $matchedRedeem->msisdn,
                            'keyword_id' => $clickHistory->keyword_id,
                            'keyword_desc' => $matchedRedeem->keyword_desc ?? $clickHistory->keyword_id,
                            'matched' => [],
                            'not_matched' => []
                        ];
                    }
                    
                    // Add matched redemption
                    $comparisons[$key]['matched'][] = [
                        'click_history' => $clickHistory,
                        'redeem' => $matchedRedeem,
                        'merchant' => $clickHistory->merchant
                    ];
                    
                    // Add not matched redemption
                    $comparisons[$key]['not_matched'][] = [
                        'click_history' => $clickHistory,
                        'redeem' => $notMatchedRedeem,
                        'merchant' => $notMatchedRedeem->matched_merchant ?? null
                    ];
                }
            }
        }

        // Apply filters to comparisons
        if ($searchKeyword) {
            $comparisons = array_filter($comparisons, function($comparison) use ($searchKeyword) {
                return stripos($comparison['msisdn'], $searchKeyword) !== false 
                    || stripos($comparison['keyword_id'], $searchKeyword) !== false
                    || stripos($comparison['keyword_desc'], $searchKeyword) !== false;
            });
        }

        if ($merchantId) {
            $comparisons = array_filter($comparisons, function($comparison) use ($merchantId) {
                // Check if any matched or not_matched has this merchant_id
                foreach ($comparison['matched'] as $matched) {
                    if ($matched['click_history']->merchant_id == $merchantId) {
                        return true;
                    }
                }
                foreach ($comparison['not_matched'] as $notMatched) {
                    if (isset($notMatched['merchant']) && $notMatched['merchant']->id == $merchantId) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($date) {
            $comparisons = array_filter($comparisons, function($comparison) use ($date) {
                // Check if any matched or not_matched has this date
                foreach ($comparison['matched'] as $matched) {
                    if ($matched['click_history']->clicked_at->format('Y-m-d') == $date) {
                        return true;
                    }
                }
                foreach ($comparison['not_matched'] as $notMatched) {
                    if ($notMatched['click_history']->clicked_at->format('Y-m-d') == $date) {
                        return true;
                    }
                }
                return false;
            });
        }

        // Re-index array after filtering
        $comparisons = array_values($comparisons);

        // Get all merchants for filter dropdown
        $merchants = Merchant::orderBy('nama_merchant')->get();

        return view('click-history.not-matched-detail', [
            'comparisons' => $comparisons,
            'merchants' => $merchants,
            'filters' => [
                'search' => $searchKeyword,
                'merchant_id' => $merchantId,
                'date' => $date
            ]
        ]);
    }

    /**
     * Get or create unique device ID using cookie
     */
    protected function getClientID(Request $request)
    {
        $cookie_name = 'device_id_uniq';
        
        // Check if cookie already exists
        if ($request->hasCookie($cookie_name)) {
            return $request->cookie($cookie_name);
        }
        
        // Generate new device ID
        $cookie_value = uniqid() . '_' . time() . '_' . substr(md5($request->header('User-Agent')), 0, 10);
        
        // Set cookie (will be sent with response)
        cookie()->queue($cookie_name, $cookie_value, 86400 * 60); // 60 days
        
        return $cookie_value;
    }
}
