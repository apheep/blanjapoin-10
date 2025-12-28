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
        $matchStatus = $request->get('match_status');
        $sortBy = $request->get('sort', 'clicked_at');
        $sortDir = $request->get('dir', 'desc');
        
        // OPTIMIZED: Calculate statistics using aggregate queries
        $statsBase = ClickHistory::query();
        
        // Apply filters to stats query
        $this->applyFilters($statsBase, $searchKeyword, $merchantId, $keywordId, $date);
        
        // Count Matched and Unmatched
        $totalMatched = $statsBase->clone()
            ->whereExists(function($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('tokodigi_tselpoin_redeem as tr')
                    ->whereColumn('tr.coupon', 'click_history.keyword_id')
                    ->where('tr.program', 'BLANJAPOIN')
                    ->whereColumn('tr.created_date', '>', 'click_history.clicked_at');
            })
            ->count();
        
        $totalUnmatched = $statsBase->clone()
            ->whereNotExists(function($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('tokodigi_tselpoin_redeem as tr')
                    ->whereColumn('tr.coupon', 'click_history.keyword_id')
                    ->where('tr.program', 'BLANJAPOIN')
                    ->whereColumn('tr.created_date', '>', 'click_history.clicked_at');
            })
            ->count();
        
        $totalNotMatched = 0;
        
        // OPTIMIZED: Use subquery to get matching redeem with smallest diff_click
        // Priority: Use redeem where merchant_id matches click_history.merchant_id AND diff_click is smallest
        $query = ClickHistory::with(['merchant', 'keyword'])
            ->select(
                'click_history.*',
                DB::raw('(SELECT tr.msisdn FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_msisdn'),
                DB::raw('(SELECT tr.created_date FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_redeem_date'),
                DB::raw('(SELECT tr.keyword_desc FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_keyword_desc'),
                DB::raw('(SELECT tr.poin_redeem FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_poin_redeem'),
                DB::raw('(SELECT tr.merchant_id FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_redeem_merchant_id'),
                DB::raw('(SELECT tr.click_date FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_click_date'),
                DB::raw('(SELECT tr.diff_click FROM tokodigi_tselpoin_redeem tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                    AND (tr.merchant_id = click_history.merchant_id OR tr.merchant_id IS NULL)
                    ORDER BY 
                        CASE WHEN tr.merchant_id = click_history.merchant_id AND tr.diff_click IS NOT NULL THEN 0 ELSE 1 END,
                        CASE WHEN tr.diff_click IS NOT NULL THEN tr.diff_click ELSE TIMESTAMPDIFF(SECOND, click_history.clicked_at, tr.created_date) END ASC
                    LIMIT 1
                ) as matched_diff_click')
            );

        // Apply filters
        $this->applyFilters($query, $searchKeyword, $merchantId, $keywordId, $date);

        // Apply sorting
        if ($sortBy === 'merchant') {
            $query->leftJoin('merchants', 'click_history.merchant_id', '=', 'merchants.id')
                  ->addSelect('merchants.nama_merchant')
                  ->orderBy('merchants.nama_merchant', $sortDir)
                  ->groupBy('click_history.id');
        } elseif ($sortBy === 'clicked_at') {
            $query->orderBy('click_history.clicked_at', $sortDir);
        } elseif ($sortBy === 'status') {
            $query->orderByRaw('CASE WHEN matched_redeem.id IS NOT NULL THEN 2 ELSE 0 END ' . $sortDir)
                ->orderBy('click_history.clicked_at', 'desc');
        } else {
            $query->orderBy('click_history.clicked_at', 'desc');
        }

        // Paginate
        $clickHistories = $query->paginate(20)->appends($request->query());

        // Post-process: create matched_redeem object
        foreach ($clickHistories as $clickHistory) {
            if ($clickHistory->matched_msisdn) {
                // Use diff_click from column if available, otherwise calculate
                $diffSeconds = $clickHistory->matched_diff_click ?? $clickHistory->matched_diff_seconds;
                
                $clickHistory->matched_redeem = (object)[
                    'msisdn' => $clickHistory->matched_msisdn,
                    'created_date' => $clickHistory->matched_redeem_date,
                    'keyword_desc' => $clickHistory->matched_keyword_desc,
                    'keyword_id' => $clickHistory->keyword_id,
                    'poin_redeem' => $clickHistory->matched_poin_redeem,
                    'time_diff_seconds' => $diffSeconds,
                    'time_diff_human' => $this->secondsToHuman($diffSeconds),
                    'confidence' => $this->getConfidenceLevel($diffSeconds),
                    // Add info if data from new columns (for debugging)
                    'from_column' => $clickHistory->matched_diff_click ? true : false,
                ];
                $clickHistory->status_order = 2; // Matched
            } else {
                $clickHistory->matched_redeem = null;
                $clickHistory->status_order = 0; // Unmatched
            }
            
            $clickHistory->not_matched_redeem = null;
        }

        // Get all merchants and keywords for filter dropdowns
        $merchants = Merchant::orderBy('nama_merchant')->get();
        $keywords = Keyword::orderBy('keyword_id')->get();

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
     * Apply filters to query
     */
    private function applyFilters($query, $searchKeyword, $merchantId, $keywordId, $date)
    {
        if ($searchKeyword) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('click_history.ip_address', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('click_history.device_id', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('click_history.keyword_id', 'like', '%' . $searchKeyword . '%')
                  ->orWhereExists(function($subQuery) use ($searchKeyword) {
                      $subQuery->select(DB::raw(1))
                          ->from('tokodigi_tselpoin_redeem as tr')
                          ->whereColumn('tr.coupon', 'click_history.keyword_id')
                          ->where('tr.program', 'BLANJAPOIN')
                          ->whereColumn('tr.created_date', '>', 'click_history.clicked_at')
                          ->where('tr.msisdn', 'like', '%' . $searchKeyword . '%');
                  });
            });
        }

        if ($merchantId) {
            $query->where('click_history.merchant_id', $merchantId);
        }

        if ($keywordId) {
            $query->where('click_history.keyword_id', $keywordId);
        }

        if ($date) {
            $query->whereDate('click_history.clicked_at', $date);
        }
    }

    /**
     * Get confidence level based on time difference
     */
    private function getConfidenceLevel($seconds)
    {
        if (!$seconds) return 'low';
        
        if ($seconds <= 300) {
            return 'high'; // ≤5 menit
        } elseif ($seconds <= 900) {
            return 'medium'; // ≤15 menit
        } else {
            return 'low'; // >15 menit
        }
    }

    /**
     * Convert seconds to human readable format
     */
    private function secondsToHuman($seconds)
    {
        if (!$seconds) return '0 detik';
        
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
     * Cari redeem yang paling cocok dengan click history
     * Konsep: Klik dulu → Redeem kemudian
     * Cocokkan keyword, ambil selisih waktu paling dekat
     */
    private function findMatchingRedeem($clickHistory)
    {
        if (!$clickHistory->keyword_id) {
            return null;
        }

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
            ->orderBy('time_diff_microseconds', 'asc')
            ->first();

        if ($redeem) {
            $redeem->time_diff_human = $this->secondsToHuman($redeem->time_diff_seconds);
            $redeem->confidence = $this->getConfidenceLevel($redeem->time_diff_seconds);
        }

        return $redeem;
    }

    /**
     * Cari redemption dengan time diff terbesar (Not Matched) untuk MSISDN yang sama
     */
    private function findNotMatchedRedeem($clickHistory, $msisdn)
    {
        if (!$clickHistory->keyword_id || !$msisdn) {
            return null;
        }

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
            ->orderBy('time_diff_seconds', 'desc')
            ->get();

        $notMatchedRedeem = null;
        foreach ($allRedeems as $redeem) {
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $redeem->keyword_id)
                ->where('clicked_at', '<', $redeem->created_date)
                ->select(
                    'merchant_id',
                    DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') as time_diff_seconds")
                )
                ->orderBy('time_diff_seconds', 'asc')
                ->first();
            
            if ($matchingClick && $matchingClick->merchant_id != $clickHistory->merchant_id) {
                $notMatchedRedeem = $redeem;
                break;
            }
        }

        if ($notMatchedRedeem) {
            $notMatchedRedeem->time_diff_human = $this->secondsToHuman($notMatchedRedeem->time_diff_seconds);
            $notMatchedRedeem->confidence = $this->getConfidenceLevel($notMatchedRedeem->time_diff_seconds);
            
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
     * Match redemption dengan click history untuk determine correct merchant_id
     */
    private function findMatchingClick($redemption)
    {
        if (!$redemption->keyword_id) {
            return null;
        }

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
            $merchant = DB::table('merchants')->where('id', $click->merchant_id)->first();
            $click->merchant = $merchant;
            $click->time_diff_human = $this->secondsToHuman($click->time_diff_seconds);
            $click->confidence = $this->getConfidenceLevel($click->time_diff_seconds);
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

        $totalClicks = ClickHistory::whereBetween('clicked_at', [$startDate, $endDate])->count();
        
        $totalRedeems = DB::table('tokodigi_tselpoin_redeem')
            ->where('program', 'BLANJAPOIN')
            ->whereBetween('created_date', [$startDate, $endDate])
            ->count();

        $clicksByMerchant = ClickHistory::with('merchant')
            ->select('merchant_id', DB::raw('COUNT(*) as total_clicks'))
            ->whereBetween('clicked_at', [$startDate, $endDate])
            ->groupBy('merchant_id')
            ->orderBy('total_clicks', 'desc')
            ->limit(10)
            ->get();

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
     */
    public function anonymousRedeems(Request $request)
    {
        $searchKeyword = $request->get('search');
        $keywordId = $request->get('keyword_id');
        $date = $request->get('date');
        $sortBy = $request->get('sort', 'created_date');
        $sortDir = $request->get('dir', 'desc');

        $query = DB::table('tokodigi_tselpoin_redeem as tr')
            ->where('tr.program', 'BLANJAPOIN');

        $query->whereNotExists(function($subquery) {
            $subquery->select(DB::raw(1))
                ->from('click_history')
                ->whereColumn('click_history.keyword_id', 'tr.coupon')
                ->whereColumn('click_history.clicked_at', '<', 'tr.created_date');
        });

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
     * Track click dari user
     */
    public function trackClick(Request $request)
    {
        try {
            $merchantId = $request->input('merchant_id');
            $keywordId = $request->input('keyword_id');

            if (!$merchantId) {
                return response()->json(['error' => 'Merchant ID required'], 400);
            }

            $ipAddress = $this->getClientIP($request);
            $deviceId = $this->getClientID($request);

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
        $ip = $request->ip();
        
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
     * Show detail komparasi Not Matched
     */
    public function notMatchedDetail(Request $request)
    {
        $searchKeyword = $request->get('search');
        $merchantId = $request->get('merchant_id');
        $date = $request->get('date');

        $clickHistories = ClickHistory::with(['merchant', 'keyword'])
            ->select('click_history.*')
            ->get();

        $comparisons = [];
        
        foreach ($clickHistories as $clickHistory) {
            $matchedRedeem = $this->findMatchingRedeem($clickHistory);
            
            if ($matchedRedeem) {
                $notMatchedRedeem = $this->findNotMatchedRedeem($clickHistory, $matchedRedeem->msisdn);
                
                if ($notMatchedRedeem) {
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
                    
                    $comparisons[$key]['matched'][] = [
                        'click_history' => $clickHistory,
                        'redeem' => $matchedRedeem,
                        'merchant' => $clickHistory->merchant
                    ];
                    
                    $comparisons[$key]['not_matched'][] = [
                        'click_history' => $clickHistory,
                        'redeem' => $notMatchedRedeem,
                        'merchant' => $notMatchedRedeem->matched_merchant ?? null
                    ];
                }
            }
        }

        if ($searchKeyword) {
            $comparisons = array_filter($comparisons, function($comparison) use ($searchKeyword) {
                return stripos($comparison['msisdn'], $searchKeyword) !== false 
                    || stripos($comparison['keyword_id'], $searchKeyword) !== false
                    || stripos($comparison['keyword_desc'], $searchKeyword) !== false;
            });
        }

        if ($merchantId) {
            $comparisons = array_filter($comparisons, function($comparison) use ($merchantId) {
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

        $comparisons = array_values($comparisons);

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
        
        if ($request->hasCookie($cookie_name)) {
            return $request->cookie($cookie_name);
        }
        
        $cookie_value = uniqid() . '_' . time() . '_' . substr(md5($request->header('User-Agent')), 0, 10);
        
        cookie()->queue($cookie_name, $cookie_value, 86400 * 60);
        
        return $cookie_value;
    }
}

