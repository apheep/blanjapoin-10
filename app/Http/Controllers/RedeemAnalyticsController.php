<?php

namespace App\Http\Controllers;

use App\Models\TselepoinRedeem;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controller untuk Analytics & Reporting dari Redeem Data
 * Menggunakan kolom merchant_id, clicked_date, diff_click
 * 
 * Contoh usage dari TselepoinRedeem Model dengan Merchant Matching
 */
class RedeemAnalyticsController extends Controller
{
    /**
     * Dashboard - Overview Redeem Statistics
     */
    public function dashboard()
    {
        // Total redemptions
        $totalRedeems = TselepoinRedeem::blanjapoin()->count();
        
        // Matched redemptions
        $matchedRedeems = TselepoinRedeem::blanjapoin()->matched()->count();
        
        // Unmatched redemptions
        $unmatchedRedeems = TselepoinRedeem::blanjapoin()->unmatched()->count();
        
        // Match percentage
        $matchPercentage = $totalRedeems > 0 
            ? round(($matchedRedeems / $totalRedeems) * 100, 2) 
            : 0;

        // Average click-to-redeem time
        $avgClickToRedeem = TselepoinRedeem::blanjapoin()
            ->matched()
            ->avg('diff_click');

        // Click-to-redeem distribution
        $distribution = TselepoinRedeem::getClickToRedeemDistribution();

        return response()->json([
            'total_redemptions' => $totalRedeems,
            'matched_redemptions' => $matchedRedeems,
            'unmatched_redemptions' => $unmatchedRedeems,
            'match_percentage' => $matchPercentage,
            'avg_click_to_redeem_seconds' => round($avgClickToRedeem ?? 0, 2),
            'distribution' => $distribution,
        ]);
    }

    /**
     * Redemptions by Merchant - dengan match data
     */
    public function redemptionsByMerchant(Request $request)
    {
        $limit = $request->get('limit', 50);

        $merchants = Merchant::selectRaw('
            merchants.id,
            merchants.nama_merchant,
            COUNT(tr.coupon) as total_redemptions,
            COUNT(DISTINCT tr.msisdn) as unique_users,
            AVG(tr.diff_click) as avg_click_to_redeem_sec
        ')
            ->leftJoin('tokodigi_tselpoin_redeem as tr', function ($join) {
                $join->on('merchants.id', '=', 'tr.merchant_id')
                    ->where('tr.program', 'BLANJAPOIN');
            })
            ->groupBy('merchants.id', 'merchants.nama_merchant')
            ->orderByDesc('total_redemptions')
            ->limit($limit)
            ->get()
            ->map(function ($merchant) {
                return [
                    'id' => $merchant->id,
                    'name' => $merchant->nama_merchant,
                    'total_redemptions' => (int)$merchant->total_redemptions,
                    'unique_users' => (int)$merchant->unique_users,
                    'avg_click_to_redeem_seconds' => round($merchant->avg_click_to_redeem_sec ?? 0, 2),
                ];
            });

        return response()->json([
            'total_merchants' => Merchant::count(),
            'merchants' => $merchants,
        ]);
    }

    /**
     * Redemption detail untuk specific merchant & period
     */
    public function merchantRedemptions(int $merchantId, Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->subDays(30));
        $endDate = $request->get('end_date', Carbon::today());
        $limit = $request->get('limit', 100);

        $merchant = Merchant::find($merchantId);
        if (!$merchant) {
            return response()->json(['error' => 'Merchant not found'], 404);
        }

        $redemptions = TselepoinRedeem::blanjapoin()
            ->forMerchant($merchantId)
            ->betweenDates($startDate, $endDate)
            ->latest('created_date')
            ->limit($limit)
            ->get()
            ->map(function ($redeem) {
                return [
                    'id' => $redeem->id,
                    'msisdn' => $redeem->msisdn,
                    'keyword' => $redeem->coupon,
                    'redeemed_at' => $redeem->created_date->format('Y-m-d H:i:s'),
                    'clicked_at' => $redeem->clicked_date?->format('Y-m-d H:i:s'),
                    'click_to_redeem_seconds' => $redeem->diff_click,
                    'click_to_redeem_readable' => $redeem->getClickToRedeemDurationShort(),
                    'poin' => $redeem->poin_redeem,
                    'matched' => $redeem->isMatched(),
                ];
            });

        return response()->json([
            'merchant' => [
                'id' => $merchant->id,
                'name' => $merchant->nama_merchant,
            ],
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'total_count' => count($redemptions),
            'redemptions' => $redemptions,
        ]);
    }

    /**
     * Keyword Performance - matched redemptions per merchant
     */
    public function keywordPerformance(string $keywordId, Request $request)
    {
        $limit = $request->get('limit', 50);

        // Get all merchants yang punya redemption untuk keyword ini
        $merchants = Merchant::selectRaw('
            merchants.id,
            merchants.nama_merchant,
            COUNT(tr.coupon) as redemption_count,
            AVG(tr.diff_click) as avg_click_to_redeem
        ')
            ->leftJoin('tokodigi_tselpoin_redeem as tr', function ($join) use ($keywordId) {
                $join->on('merchants.id', '=', 'tr.merchant_id')
                    ->where('tr.program', 'BLANJAPOIN')
                    ->where('tr.coupon', $keywordId);
            })
            ->groupBy('merchants.id', 'merchants.nama_merchant')
            ->having('redemption_count', '>', 0)
            ->orderByDesc('redemption_count')
            ->limit($limit)
            ->get();

        if ($merchants->isEmpty()) {
            return response()->json(['error' => 'No redemptions for this keyword'], 404);
        }

        // Total match percentage
        $totalRedeems = TselepoinRedeem::blanjapoin()
            ->forKeyword($keywordId)
            ->count();
        
        $matchedRedeems = TselepoinRedeem::blanjapoin()
            ->forKeyword($keywordId)
            ->matched()
            ->count();

        return response()->json([
            'keyword' => $keywordId,
            'total_redemptions' => $totalRedeems,
            'matched_redemptions' => $matchedRedeems,
            'match_percentage' => $totalRedeems > 0 
                ? round(($matchedRedeems / $totalRedeems) * 100, 2)
                : 0,
            'merchants' => $merchants->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->nama_merchant,
                    'redemption_count' => (int)$m->redemption_count,
                    'avg_click_to_redeem_seconds' => round($m->avg_click_to_redeem ?? 0, 2),
                ];
            }),
        ]);
    }

    /**
     * Unmatched Redemptions - untuk audit & investigation
     */
    public function unmatchedRedemptions(Request $request)
    {
        $days = $request->get('days', 7);
        $limit = $request->get('limit', 100);

        $startDate = Carbon::now()->subDays($days);

        $unmatched = TselepoinRedeem::blanjapoin()
            ->unmatched()
            ->where('created_date', '>=', $startDate)
            ->latest('created_date')
            ->limit($limit)
            ->get()
            ->map(function ($redeem) {
                return [
                    'id' => $redeem->id,
                    'msisdn' => $redeem->msisdn,
                    'keyword' => $redeem->coupon,
                    'redeemed_at' => $redeem->created_date->format('Y-m-d H:i:s'),
                    'days_since_redeem' => $redeem->created_date->diffInDays(now()),
                    'matched' => $redeem->isMatched(),
                    'reason' => $this->getUnmatchedReason($redeem),
                ];
            });

        return response()->json([
            'period_days' => $days,
            'start_date' => $startDate->format('Y-m-d'),
            'total_unmatched' => count($unmatched),
            'unmatched' => $unmatched,
        ]);
    }

    /**
     * Time Distribution Analytics
     */
    public function timeDistributionAnalytics()
    {
        $distribution = TselepoinRedeem::blanjapoin()
            ->matched()
            ->selectRaw('
                CASE 
                    WHEN diff_click BETWEEN 3 AND 10 THEN "3-10 sec"
                    WHEN diff_click BETWEEN 11 AND 30 THEN "11-30 sec"
                    WHEN diff_click BETWEEN 31 AND 60 THEN "31-60 sec"
                    WHEN diff_click BETWEEN 61 AND 300 THEN "1-5 min"
                    WHEN diff_click > 300 THEN "> 5 min"
                END as time_range,
                COUNT(*) as count
            ')
            ->groupBy('time_range')
            ->get()
            ->mapWithKeys(function ($item) {
                $total = TselepoinRedeem::blanjapoin()->matched()->count();
                return [
                    $item->time_range => [
                        'count' => (int)$item->count,
                        'percentage' => round(($item->count / $total) * 100, 2),
                    ]
                ];
            });

        return response()->json([
            'total_matched' => TselepoinRedeem::blanjapoin()->matched()->count(),
            'distribution' => $distribution,
        ]);
    }

    /**
     * Helper - Determine unmatched reason
     */
    private function getUnmatchedReason(TselepoinRedeem $redeem): string
    {
        if ($redeem->merchant_id === null) {
            return 'No matching click found';
        }
        if ($redeem->clicked_date === null) {
            return 'Clicked date missing';
        }
        if ($redeem->diff_click === null) {
            return 'Time diff missing';
        }
        return 'Unknown';
    }

    /**
     * Export - Redemptions data untuk specific merchant
     */
    public function exportMerchantRedemptions(int $merchantId, Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->subDays(30));
        $endDate = $request->get('end_date', Carbon::today());

        $redemptions = TselepoinRedeem::blanjapoin()
            ->forMerchant($merchantId)
            ->betweenDates($startDate, $endDate)
            ->get([
                'id', 'msisdn', 'coupon', 'created_date', 
                'clicked_date', 'diff_click', 'poin_redeem'
            ])
            ->map(function ($r) {
                return [
                    'ID' => $r->id,
                    'MSISDN' => $r->msisdn,
                    'Keyword' => $r->coupon,
                    'Redeemed At' => $r->created_date->format('Y-m-d H:i:s'),
                    'Clicked At' => $r->clicked_date?->format('Y-m-d H:i:s') ?? '-',
                    'Click-to-Redeem (sec)' => $r->diff_click ?? '-',
                    'Poin' => $r->poin_redeem,
                ];
            });

        // Return CSV (bisa juga JSON tergantung kebutuhan)
        return response()->json([
            'merchant_id' => $merchantId,
            'period' => "{$startDate} to {$endDate}",
            'total_rows' => count($redemptions),
            'data' => $redemptions,
        ]);
    }
}
