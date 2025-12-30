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
                $q->where('click_history.ip_address', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('click_history.device_id', 'like', '%' . $searchKeyword . '%')
                  ->orWhere('click_history.keyword_id', 'like', '%' . $searchKeyword . '%')
                  // Search MSISDN dari matched/not matched redemption
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

        // Apply sorting - All sorting done at query level for all data
        // Note: Join hanya dilakukan saat sorting merchant untuk menghindari konflik dengan filter
        if ($sortBy === 'merchant') {
            // Join dengan merchants hanya untuk sorting
            $query->leftJoin('merchants', 'click_history.merchant_id', '=', 'merchants.id')
                  ->select('click_history.*', 'merchants.nama_merchant') // Include nama_merchant in SELECT
                  ->orderBy('merchants.nama_merchant', $sortDir)
                  ->groupBy('click_history.id'); // Group by untuk menghindari duplicate
        } elseif ($sortBy === 'clicked_at') {
            $query->orderBy('click_history.clicked_at', $sortDir);
        } elseif ($sortBy === 'status') {
            // Sort by status: hanya Matched dan Unmatched yang di-sort
            // Not Matched tetap ditampilkan tapi tidak ikut sorting
            // Matched = has redeem with matching keyword_id and created_date > clicked_at
            // Unmatched = tidak ada redeem
            $query->selectRaw('click_history.*, 
                CASE WHEN EXISTS (
                    SELECT 1 FROM tokodigi_tselpoin_redeem as tr 
                    WHERE tr.coupon = click_history.keyword_id 
                    AND tr.program = "BLANJAPOIN"
                    AND tr.created_date > click_history.clicked_at
                ) THEN 2 ELSE 0 END as status_order')
            ->orderBy('status_order', $sortDir)
            ->orderBy('click_history.clicked_at', 'desc'); // Secondary sort
        } else {
            $query->orderBy('click_history.clicked_at', 'desc');
        }
        
        // Calculate matched, unmatched, and not matched counts directly from DB - JANGAN LOOPING!
        // Status counts berdasarkan ada tidaknya match dengan merchant_id yang sama
        $statsResult = DB::table('click_history as ch')
            ->leftJoin('tokodigi_tselpoin_redeem as tr', function($join) {
                $join->on('tr.coupon', '=', 'ch.keyword_id')
                     ->where('tr.program', '=', 'BLANJAPOIN')
                     ->whereColumn('tr.created_date', '>', 'ch.clicked_at')
                     ->where('tr.merchant_id', DB::raw('ch.merchant_id')); // Match merchant_id!
            })
            // Apply same filters
            ->when($searchKeyword, function($q) use ($searchKeyword) {
                return $q->where(function($subQ) use ($searchKeyword) {
                    $subQ->where('ch.ip_address', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('ch.device_id', 'like', '%' . $searchKeyword . '%')
                          ->orWhere('ch.keyword_id', 'like', '%' . $searchKeyword . '%')
                          ->orWhereExists(function($subQuery) use ($searchKeyword) {
                              $subQuery->select(DB::raw(1))
                                  ->from('tokodigi_tselpoin_redeem as tr2')
                                  ->whereColumn('tr2.coupon', 'ch.keyword_id')
                                  ->where('tr2.program', 'BLANJAPOIN')
                                  ->whereColumn('tr2.created_date', '>', 'ch.clicked_at')
                                  ->where('tr2.msisdn', 'like', '%' . $searchKeyword . '%');
                          });
                });
            })
            ->when($merchantId, function($q) use ($merchantId) {
                return $q->where('ch.merchant_id', $merchantId);
            })
            ->when($keywordId, function($q) use ($keywordId) {
                return $q->where('ch.keyword_id', $keywordId);
            })
            ->when($date, function($q) use ($date) {
                return $q->whereDate('ch.clicked_at', $date);
            })
            ->selectRaw('
                SUM(CASE WHEN tr.merchant_id IS NOT NULL AND tr.merchant_id = ch.merchant_id THEN 1 ELSE 0 END) as total_matched,
                SUM(CASE WHEN tr.merchant_id IS NOT NULL AND tr.merchant_id != ch.merchant_id THEN 1 ELSE 0 END) as total_not_matched,
                SUM(CASE WHEN tr.merchant_id IS NULL THEN 1 ELSE 0 END) as total_unmatched
            ')
            ->first();
        
        $totalMatched = $statsResult->total_matched ?? 0;
        $totalNotMatched = $statsResult->total_not_matched ?? 0;
        $totalUnmatched = $statsResult->total_unmatched ?? 0;

        // Paginate - sorting sudah dilakukan di query level untuk semua data
        // Remove distinct() as it's not needed and causes issues with ORDER BY
        $clickHistories = $query->paginate(20)->appends($request->query());

        // Ambil status Matched/Not Matched/Unmatched LANGSUNG DARI DATABASE - jangan looping PHP!
        // Status ditentukan dari merchant_id di tokodigi_tselpoin_redeem (sudah di-fill oleh trigger)
        $clickHistoryIds = $clickHistories->pluck('id')->toArray();
        
        $statusData = DB::table('click_history as ch')
            ->leftJoin('tokodigi_tselpoin_redeem as tr', function($join) {
                $join->on('tr.coupon', '=', 'ch.keyword_id')
                     ->where('tr.program', '=', 'BLANJAPOIN')
                     ->whereColumn('tr.created_date', '>', 'ch.clicked_at')
                     ->where('tr.merchant_id', DB::raw('ch.merchant_id')); // ← Match merchant_id!
            })
            ->select(
                'ch.id',
                'tr.created_date',
                'tr.msisdn',
                'tr.keyword_desc',
                'tr.coupon',
                'tr.poin_redeem',
                'tr.merchant_id',
                DB::raw("TIMESTAMPDIFF(SECOND, ch.clicked_at, tr.created_date) as time_diff_seconds"),
                DB::raw("IF(tr.coupon IS NOT NULL AND tr.merchant_id = ch.merchant_id, 2, IF(tr.coupon IS NOT NULL, 1, 0)) as status_order")
            )
            ->whereIn('ch.id', $clickHistoryIds)
            ->orderBy('ch.id')
            ->orderBy('time_diff_seconds', 'asc') // Get the closest redeem
            ->distinct()
            ->get()
            ->groupBy('id');
        
        // Map status data ke click histories
        foreach ($clickHistories as $clickHistory) {
            $redeemData = $statusData->get($clickHistory->id)?->first();
            
            if ($redeemData) {
                if ($redeemData->status_order == 2) {
                    // MATCHED - merchant_id cocok dan ada redemption
                    $clickHistory->status_order = 2;
                    $clickHistory->matched_redeem = $redeemData;
                    $clickHistory->not_matched_redeem = null;
                } elseif ($redeemData->status_order == 1) {
                    // NOT MATCHED - ada redemption tapi merchant_id berbeda
                    $clickHistory->status_order = 1;
                    $clickHistory->matched_redeem = null;
                    $clickHistory->not_matched_redeem = $redeemData;
                } else {
                    // UNMATCHED - tidak ada redemption sama sekali
                    $clickHistory->status_order = 0;
                    $clickHistory->matched_redeem = null;
                    $clickHistory->not_matched_redeem = null;
                }
            } else {
                // UNMATCHED - tidak ada redemption
                $clickHistory->status_order = 0;
                $clickHistory->matched_redeem = null;
                $clickHistory->not_matched_redeem = null;
            }
            
            // Set time_diff_human dan confidence jika ada redeemData
            if ($redeemData) {
                $redeemData->time_diff_human = $this->secondsToHuman($redeemData->time_diff_seconds ?? 0);
                $timeDiff = $redeemData->time_diff_seconds ?? 0;
                $redeemData->confidence = match(true) {
                    $timeDiff <= 300 => 'high',
                    $timeDiff <= 900 => 'medium',
                    default => 'low'
                };
            }
        }
        
        // Untuk sorting status: jika sort by status, re-sort collection
        // Hanya Matched (status_order = 2) dan Unmatched (status_order = 0) yang di-sort
        // Not Matched (status_order = 1) tetap ditampilkan tapi tidak ikut sorting (selalu di akhir)
        if ($sortBy === 'status') {
            $sortedItems = $clickHistories->getCollection()->sort(function ($a, $b) use ($sortDir) {
                // Not Matched (status_order = 1) selalu di akhir, tidak ikut sorting
                if ($a->status_order == 1 && $b->status_order != 1) {
                    return 1; // Not Matched selalu di bawah
                }
                if ($a->status_order != 1 && $b->status_order == 1) {
                    return -1; // Not Matched selalu di bawah
                }
                if ($a->status_order == 1 && $b->status_order == 1) {
                    // Jika keduanya Not Matched, sort by clicked_at desc
                    return $b->clicked_at <=> $a->clicked_at;
                }
                // Matched dan Unmatched di-sort berdasarkan status_order
                if ($sortDir === 'asc') {
                    return $a->status_order <=> $b->status_order;
                } else {
                    return $b->status_order <=> $a->status_order;
                }
            });
            
            // Replace collection dengan yang sudah di-sort
            $clickHistories->setCollection($sortedItems->values());
        }

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
        // 3. Selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
        // 4. Merchant ID dari tokodigi_tselpoin_redeem harus sama dengan click history merchant_id (BARU!)
        // 5. Ambil yang selisih waktunya paling kecil
        
        $redeem = DB::table('tokodigi_tselpoin_redeem as tr')
            ->where('tr.coupon', $clickHistory->keyword_id)
            ->where('tr.program', 'BLANJAPOIN')
            ->where('tr.created_date', '>', $clickHistory->clicked_at)
            ->where('tr.merchant_id', $clickHistory->merchant_id) // ← NEW: Check merchant match!
            ->whereRaw("TIMESTAMPDIFF(SECOND, '{$clickHistory->clicked_at}', tr.created_date) > 3") // Hanya selisih > 3 detik yang dianggap match
            ->select(
                'tr.created_date',
                'tr.msisdn',
                'tr.keyword_desc',
                'tr.coupon as keyword_id',
                'tr.poin_redeem',
                'tr.merchant_id',
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
     * Cek apakah click history ini benar-benar Matched (time diff terkecil untuk MSISDN + keyword)
     * Logika: Untuk MSISDN + keyword yang sama, bandingkan time diff dari SEMUA click history
     * yang match dengan redemption yang sama. Yang time diff terkecil = Matched.
     * Tidak peduli merchant-nya sama atau berbeda, yang penting time diff terkecil.
     */
    private function isActuallyMatched($clickHistory, $matchedRedeem)
    {
        if (!$clickHistory->keyword_id || !$matchedRedeem->msisdn) {
            return true; // Default true jika tidak bisa dicek
        }

        // Cari semua click history dengan keyword yang sama
        $allClicks = ClickHistory::where('keyword_id', $clickHistory->keyword_id)->get();
        
        // Untuk setiap click, cari redemption dengan MSISDN yang sama
        // Kumpulkan semua click yang match dengan MSISDN + keyword yang sama
        // Gunakan time_diff_microseconds untuk presisi lebih tinggi
        $allClickTimeDiffs = [];
        foreach ($allClicks as $ch) {
            // Skip jika ini click history yang sedang dicek
            if ($ch->id == $clickHistory->id) {
                continue;
            }
            
            $redeem = $this->findMatchingRedeem($ch);
            if ($redeem && $redeem->msisdn == $matchedRedeem->msisdn) {
                // Bandingkan SEMUA click, tidak peduli merchant-nya sama atau berbeda
                // Gunakan microsecond untuk presisi lebih tinggi
                $timeDiffMicroseconds = $redeem->time_diff_microseconds ?? ($redeem->time_diff_seconds ?? 0) * 1000000;
                $allClickTimeDiffs[] = [
                    'click_history_id' => $ch->id,
                    'merchant_id' => $ch->merchant_id,
                    'time_diff_seconds' => $redeem->time_diff_seconds ?? 0,
                    'time_diff_microseconds' => $timeDiffMicroseconds
                ];
            }
        }

        // Jika tidak ada click lain dengan MSISDN + keyword yang sama, maka ini pasti Matched
        if (count($allClickTimeDiffs) == 0) {
            return true;
        }

        // Cari time diff terkecil dari semua click lain menggunakan microsecond
        $minTimeDiffMicroseconds = min(array_column($allClickTimeDiffs, 'time_diff_microseconds'));

        // Bandingkan time diff dari click history ini dengan yang terkecil dari click lain
        // Gunakan microsecond untuk presisi lebih tinggi
        $currentTimeDiffMicroseconds = $matchedRedeem->time_diff_microseconds ?? (($matchedRedeem->time_diff_seconds ?? 0) * 1000000);
        
        // Jika time diff dari click history ini lebih kecil (dengan presisi microsecond), 
        // maka ini benar-benar Matched
        // Jika ada yang lebih kecil, berarti ini Not Matched
        // Dengan microsecond precision, kemungkinan sama sangat kecil, jadi gunakan < saja
        return $currentTimeDiffMicroseconds < $minTimeDiffMicroseconds;
    }

    /**
     * Cari click yang benar-benar matched (time diff terkecil) untuk redemption ini
     */
    private function findActualMatchedClick($redeem)
    {
        if (!$redeem->keyword_id) {
            return null;
        }

        // Cari semua click dengan keyword yang sama sebelum redeem
        // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
        $allClicks = DB::table('click_history')
            ->where('keyword_id', $redeem->keyword_id)
            ->where('clicked_at', '<', $redeem->created_date)
            ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
            ->select(
                'id',
                'merchant_id',
                'clicked_at',
                DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') as time_diff_seconds"),
                DB::raw("TIMESTAMPDIFF(MICROSECOND, clicked_at, '{$redeem->created_date}') as time_diff_microseconds")
            )
            ->get();

        // Cari click dengan time diff terkecil menggunakan microsecond untuk presisi lebih tinggi
        $minTimeDiffMicroseconds = null;
        $matchedClick = null;
        foreach ($allClicks as $click) {
            $timeDiffMicroseconds = $click->time_diff_microseconds ?? ($click->time_diff_seconds ?? 0) * 1000000;
            if ($minTimeDiffMicroseconds === null || $timeDiffMicroseconds < $minTimeDiffMicroseconds) {
                $minTimeDiffMicroseconds = $timeDiffMicroseconds;
                $matchedClick = $click;
            }
        }

        return $matchedClick;
    }

    /**
     * Cari click history yang benar-benar matched untuk redemption ini
     */
    private function findActualMatchedClickHistory($redeem)
    {
        if (!$redeem->keyword_id) {
            return null;
        }

        // Cari click dengan time diff terkecil
        $matchedClick = $this->findActualMatchedClick($redeem);
        
        if ($matchedClick) {
            return ClickHistory::with(['merchant', 'keyword'])
                ->where('id', $matchedClick->id)
                ->first();
        }

        return null;
    }

    /**
     * Cari redemption dengan time diff terbesar (Not Matched) untuk MSISDN yang sama
     * Digunakan untuk menampilkan redemption yang tidak match karena time diff terlalu lama
     * dari merchant yang berbeda
     * @deprecated - Digunakan logika baru di isActuallyMatched
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
            // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $redeem->keyword_id)
                ->where('clicked_at', '<', $redeem->created_date)
                ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
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
            // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $notMatchedRedeem->keyword_id)
                ->where('clicked_at', '<', $notMatchedRedeem->created_date)
                ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$notMatchedRedeem->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
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
        // 3. Selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
        // 4. Ambil yang selisih waktunya paling kecil
        
        $click = DB::table('click_history')
            ->where('keyword_id', $redemption->keyword_id)
            ->where('clicked_at', '<', $redemption->created_date)
            ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
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
                'latitude' => $request->input('lat') ?? $request->input('latitude'),
                'longitude' => $request->input('long') ?? $request->input('longitude') ?? $request->input('lng'),
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
                'latitude' => $request->input('lat') ?? $request->input('latitude'),
                'longitude' => $request->input('long') ?? $request->input('longitude') ?? $request->input('lng'),
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
        try {
            // Get filter parameters
            $searchKeyword = $request->get('search');
            $merchantId = $request->get('merchant_id');
            $date = $request->get('date');

            // Get all click histories yang memiliki matched_redeem dan not_matched_redeem
            // Gunakan chunk untuk menghindari memory issue jika data banyak
            $clickHistories = ClickHistory::with(['merchant', 'keyword'])
                ->select('click_history.*')
                ->get();

                // Process untuk mendapatkan data komparasi dengan logika yang benar
            // Group by MSISDN + keyword_id, lalu tentukan matched dan not matched
            $comparisons = [];
            $processedKeys = [];
            
            foreach ($clickHistories as $clickHistory) {
            $matchedRedeem = $this->findMatchingRedeem($clickHistory);
            
            if ($matchedRedeem) {
                $key = $matchedRedeem->msisdn . '_' . $clickHistory->keyword_id;
                
                // Skip jika sudah diproses
                if (in_array($key, $processedKeys)) {
                    continue;
                }
                
                // Cari semua click history dengan MSISDN + keyword yang sama
                $allClicksForRedeem = ClickHistory::with(['merchant', 'keyword'])
                    ->where('keyword_id', $clickHistory->keyword_id)
                    ->get();
                
                // Untuk setiap click, cari redemption dan time diff
                // Gunakan microsecond untuk presisi lebih tinggi
                $clickRedeemPairs = [];
                foreach ($allClicksForRedeem as $ch) {
                    $redeem = $this->findMatchingRedeem($ch);
                    if ($redeem && $redeem->msisdn == $matchedRedeem->msisdn) {
                        $timeDiffMicroseconds = $redeem->time_diff_microseconds ?? (($redeem->time_diff_seconds ?? 0) * 1000000);
                        $clickRedeemPairs[] = [
                            'click_history' => $ch,
                            'redeem' => $redeem,
                            'time_diff_seconds' => $redeem->time_diff_seconds ?? 0,
                            'time_diff_microseconds' => $timeDiffMicroseconds
                        ];
                    }
                }
                
                // Jika ada lebih dari 1 click dengan MSISDN + keyword yang sama
                if (count($clickRedeemPairs) > 1) {
                    // Sort by time diff microsecond (terkecil ke terbesar) untuk presisi lebih tinggi
                    usort($clickRedeemPairs, function($a, $b) {
                        return $a['time_diff_microseconds'] <=> $b['time_diff_microseconds'];
                    });
                    
                    // Yang pertama (time diff terkecil) = Matched
                    // Yang lainnya (time diff lebih lama) = Not Matched
                    $matched = $clickRedeemPairs[0];
                    $notMatchedList = array_slice($clickRedeemPairs, 1);
                    
                    $comparisons[$key] = [
                        'msisdn' => $matchedRedeem->msisdn ?? '',
                        'keyword_id' => $clickHistory->keyword_id ?? '',
                        'keyword_desc' => $matchedRedeem->keyword_desc ?? $clickHistory->keyword_id ?? '',
                        'matched' => [[
                            'click_history' => $matched['click_history'],
                            'redeem' => $matched['redeem'],
                            'merchant' => $matched['click_history']->merchant ?? null
                        ]],
                        'not_matched' => []
                    ];
                    
                    // Add not matched
                    foreach ($notMatchedList as $notMatched) {
                        $actualMatchedClick = $this->findActualMatchedClick($notMatched['redeem']);
                        $merchant = null;
                        if ($actualMatchedClick) {
                            $merchant = DB::table('merchants')->where('id', $actualMatchedClick->merchant_id)->first();
                        }
                        
                        // Pastikan time_diff_human dan confidence sudah di-set
                        if (!isset($notMatched['redeem']->time_diff_human)) {
                            $notMatched['redeem']->time_diff_human = $this->secondsToHuman($notMatched['redeem']->time_diff_seconds ?? 0);
                        }
                        if (!isset($notMatched['redeem']->confidence)) {
                            $timeDiff = $notMatched['redeem']->time_diff_seconds ?? 0;
                            if ($timeDiff <= 300) {
                                $notMatched['redeem']->confidence = 'high';
                            } elseif ($timeDiff <= 900) {
                                $notMatched['redeem']->confidence = 'medium';
                            } else {
                                $notMatched['redeem']->confidence = 'low';
                            }
                        }
                        
                        $comparisons[$key]['not_matched'][] = [
                            'click_history' => $notMatched['click_history'],
                            'redeem' => $notMatched['redeem'],
                            'merchant' => $merchant
                        ];
                    }
                    
                    $processedKeys[] = $key;
                }
            }
            }

            // Apply filters to comparisons
            if ($searchKeyword) {
                $comparisons = array_filter($comparisons, function($comparison) use ($searchKeyword) {
                    return stripos($comparison['msisdn'] ?? '', $searchKeyword) !== false 
                        || stripos($comparison['keyword_id'] ?? '', $searchKeyword) !== false
                        || stripos($comparison['keyword_desc'] ?? '', $searchKeyword) !== false;
                });
            }

            if ($merchantId) {
                $comparisons = array_filter($comparisons, function($comparison) use ($merchantId) {
                    // Check if any matched or not_matched has this merchant_id
                    foreach ($comparison['matched'] ?? [] as $matched) {
                        if (isset($matched['click_history']) && $matched['click_history']->merchant_id == $merchantId) {
                            return true;
                        }
                    }
                    foreach ($comparison['not_matched'] ?? [] as $notMatched) {
                        if (isset($notMatched['merchant']) && $notMatched['merchant'] && $notMatched['merchant']->id == $merchantId) {
                            return true;
                        }
                    }
                    return false;
                });
            }

            if ($date) {
                $comparisons = array_filter($comparisons, function($comparison) use ($date) {
                    // Check if any matched or not_matched has this date
                    foreach ($comparison['matched'] ?? [] as $matched) {
                        if (isset($matched['click_history']) && $matched['click_history']->clicked_at && $matched['click_history']->clicked_at->format('Y-m-d') == $date) {
                            return true;
                        }
                    }
                    foreach ($comparison['not_matched'] ?? [] as $notMatched) {
                        if (isset($notMatched['click_history']) && $notMatched['click_history']->clicked_at && $notMatched['click_history']->clicked_at->format('Y-m-d') == $date) {
                            return true;
                        }
                    }
                    return false;
                });
            }

            // Re-index array after filtering (filter diterapkan pada SEMUA data sebelum pagination)
            $comparisons = array_values($comparisons);

            // Calculate totals from all filtered comparisons (before pagination)
            // Ini memastikan total dihitung dari semua data yang sudah di-filter, bukan hanya halaman saat ini
            $totalMatched = 0;
            $totalNotMatched = 0;
            foreach ($comparisons as $comparison) {
                $totalMatched += count($comparison['matched'] ?? []);
                $totalNotMatched += count($comparison['not_matched'] ?? []);
            }

            // Pagination: 5 MSISDN (comparisons) per page
            // Filter/search sudah diterapkan pada semua data di atas, jadi pagination hanya membagi hasil filter
            $perPage = 5;
            $currentPage = $request->get('page', 1);
            $total = count($comparisons); // Total dari semua data yang sudah di-filter
            $offset = ($currentPage - 1) * $perPage;
            $paginatedComparisons = array_slice($comparisons, $offset, $perPage);

            // Create paginator manually
            $comparisonsPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedComparisons,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query() // Preserve filter parameters in pagination links
                ]
            );

            // Get all merchants for filter dropdown
            $merchants = Merchant::orderBy('nama_merchant')->get();

            return view('click-history.not-matched-detail', [
                'comparisons' => $comparisonsPaginator,
                'merchants' => $merchants,
                'totalMatched' => $totalMatched,
                'totalNotMatched' => $totalNotMatched,
                'filters' => [
                    'search' => $searchKeyword,
                    'merchant_id' => $merchantId,
                    'date' => $date
                ]
            ]);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in notMatchedDetail: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return error page atau redirect dengan error message
            return redirect()->route('click.history.index')
                ->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
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

    public function blockedIpsIndex(Request $request)
    {
        $search = $request->get('search');
        $today = now()->format('Y-m-d');

        // Base query for aggregation
        $query = ClickHistory::select('ip_address', DB::raw('MAX(device_id) as device_id'), DB::raw('count(*) as total_clicks'))
            ->whereDate('clicked_at', $today);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%");
            });
        }

        // Get ALL grouped results first to avoid pagination count issues with GroupBy
        $allBlockedIps = $query->groupBy('ip_address')
            ->having('total_clicks', '>', 10) 
            ->orderBy('total_clicks', 'desc')
            ->get();

        // Manual Pagination
        $page = $request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Slice items for current page
        $currentPageItems = $allBlockedIps->slice($offset, $perPage)->values();

        // Enrich ONLY the current page items (Performance Optimization)
        foreach ($currentPageItems as $ipData) {
            // Get latest click for this IP today to get merchant info
            $latestClick = ClickHistory::with('merchant')
                ->where('ip_address', $ipData->ip_address)
                ->whereDate('clicked_at', $today)
                ->orderBy('clicked_at', 'desc')
                ->first();
            
            // Get all distinct merchants visited today
            $visitedMerchants = ClickHistory::join('merchants', 'click_history.merchant_id', '=', 'merchants.id')
                ->where('click_history.ip_address', $ipData->ip_address)
                ->whereDate('click_history.clicked_at', $today)
                ->distinct()
                ->pluck('merchants.nama_merchant')
                ->toArray();

            $ipData->latest_merchant = $latestClick ? $latestClick->merchant : null;
            $ipData->merchant_count = count($visitedMerchants);
            $ipData->visited_merchants = $visitedMerchants;
            
            // Determine status
            if ($ipData->total_clicks > 20) {
                $ipData->status = 'blocked';
                $ipData->status_label = 'Blocked';
                $ipData->status_color = 'red';
            } else {
                $ipData->status = 'suspicious';
                $ipData->status_label = 'Suspicious';
                $ipData->status_color = 'yellow';
            }
        }

        // Create Paginator
        $blockedIps = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $allBlockedIps->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('click-history.blocked-ips', [
            'blockedIps' => $blockedIps,
            'search' => $search
        ]);
    }

    public function unlockIp(Request $request)
    {
        try {
            $ip = $request->input('ip_address');
            if (!$ip) {
                return redirect()->route('click.history.blocked')->with('error', 'IP Address diperlukan');
            }

            $today = now()->format('Y-m-d');
            
            // Delete history for this IP today to reset the counter
            DB::beginTransaction();
            
            $deleted = ClickHistory::where('ip_address', $ip)
                ->whereDate('clicked_at', $today)
                ->delete();
                
            DB::commit();
            
            return redirect()->route('click.history.blocked')->with('success', "IP $ip berhasil di-unlock. $deleted history dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unlocking IP: ' . $e->getMessage());
            return redirect()->route('click.history.blocked')->with('error', 'Gagal unlock IP: ' . $e->getMessage());
        }
    }
}
