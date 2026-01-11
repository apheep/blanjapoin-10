<?php

/**
 * ROUTES UNTUK REDEEM ANALYTICS
 * 
 * Tambahkan routes ini ke routes/api.php atau routes/web.php sesuai kebutuhan
 * 
 * PREFIX: /api/redeem atau /redeem
 */

use App\Http\Controllers\RedeemAnalyticsController;
use Illuminate\Support\Facades\Route;

// ===========================================
// REDEEM ANALYTICS ROUTES
// ===========================================

Route::prefix('redeem')->group(function () {
    
    // Dashboard - Overview statistics
    Route::get('dashboard', [RedeemAnalyticsController::class, 'dashboard'])
        ->name('redeem.dashboard');
    
    // Redemptions by Merchant
    Route::get('merchants', [RedeemAnalyticsController::class, 'redemptionsByMerchant'])
        ->name('redeem.merchants');
    
    // Redemption detail untuk specific merchant
    Route::get('merchants/{merchantId}', [RedeemAnalyticsController::class, 'merchantRedemptions'])
        ->name('redeem.merchant-detail');
    
    // Keyword Performance
    Route::get('keywords/{keywordId}', [RedeemAnalyticsController::class, 'keywordPerformance'])
        ->name('redeem.keyword-performance');
    
    // Unmatched Redemptions
    Route::get('unmatched', [RedeemAnalyticsController::class, 'unmatchedRedemptions'])
        ->name('redeem.unmatched');
    
    // Time Distribution Analytics
    Route::get('analytics/time-distribution', [RedeemAnalyticsController::class, 'timeDistributionAnalytics'])
        ->name('redeem.time-distribution');
    
    // Export Merchant Redemptions
    Route::get('export/merchants/{merchantId}', [RedeemAnalyticsController::class, 'exportMerchantRedemptions'])
        ->name('redeem.export-merchant');
});


// ===========================================
// EXAMPLE USAGE
// ===========================================

/**
 * 1. GET Dashboard Overview
 * 
 * GET /api/redeem/dashboard
 * 
 * Response:
 * {
 *     "total_redemptions": 50000,
 *     "matched_redemptions": 45230,
 *     "unmatched_redemptions": 4770,
 *     "match_percentage": 90.46,
 *     "avg_click_to_redeem_seconds": 125.5,
 *     "distribution": {
 *         "3-10 sec": 5000,
 *         "11-30 sec": 15000,
 *         "31-60 sec": 18000,
 *         "1-5 min": 7000,
 *         "> 5 min": 230
 *     }
 * }
 */


/**
 * 2. GET Redemptions by Merchant
 * 
 * GET /api/redeem/merchants?limit=10
 * 
 * Response:
 * {
 *     "total_merchants": 25,
 *     "merchants": [
 *         {
 *             "id": 1,
 *             "name": "Starbucks",
 *             "total_redemptions": 5500,
 *             "unique_users": 4200,
 *             "avg_click_to_redeem_seconds": 120.5
 *         },
 *         {
 *             "id": 2,
 *             "name": "Coffee Bean",
 *             "total_redemptions": 4200,
 *             "unique_users": 3100,
 *             "avg_click_to_redeem_seconds": 135.2
 *         }
 *     ]
 * }
 */


/**
 * 3. GET Merchant Redemption Details
 * 
 * GET /api/redeem/merchants/1?start_date=2025-12-01&end_date=2025-12-28&limit=50
 * 
 * Response:
 * {
 *     "merchant": {
 *         "id": 1,
 *         "name": "Starbucks"
 *     },
 *     "period": {
 *         "start_date": "2025-12-01",
 *         "end_date": "2025-12-28"
 *     },
 *     "total_count": 50,
 *     "redemptions": [
 *         {
 *             "id": 123,
 *             "msisdn": "081234567890",
 *             "keyword": "PROMO100",
 *             "redeemed_at": "2025-12-28 10:02:30",
 *             "clicked_at": "2025-12-28 10:00:00",
 *             "click_to_redeem_seconds": 150,
 *             "click_to_redeem_readable": "2m 30s",
 *             "poin": 1000,
 *             "matched": true
 *         }
 *     ]
 * }
 */


/**
 * 4. GET Keyword Performance
 * 
 * GET /api/redeem/keywords/PROMO100?limit=10
 * 
 * Response:
 * {
 *     "keyword": "PROMO100",
 *     "total_redemptions": 1500,
 *     "matched_redemptions": 1450,
 *     "match_percentage": 96.67,
 *     "merchants": [
 *         {
 *             "id": 1,
 *             "name": "Starbucks",
 *             "redemption_count": 700,
 *             "avg_click_to_redeem_seconds": 118.5
 *         },
 *         {
 *             "id": 2,
 *             "name": "Coffee Bean",
 *             "redemption_count": 750,
 *             "avg_click_to_redeem_seconds": 132.0
 *         }
 *     ]
 * }
 */


/**
 * 5. GET Unmatched Redemptions
 * 
 * GET /api/redeem/unmatched?days=7&limit=50
 * 
 * Response:
 * {
 *     "period_days": 7,
 *     "start_date": "2025-12-21",
 *     "total_unmatched": 25,
 *     "unmatched": [
 *         {
 *             "id": 456,
 *             "msisdn": "081234567890",
 *             "keyword": "SPECIAL50",
 *             "redeemed_at": "2025-12-25 14:30:00",
 *             "days_since_redeem": 3,
 *             "matched": false,
 *             "reason": "No matching click found"
 *         }
 *     ]
 * }
 */


/**
 * 6. GET Time Distribution Analytics
 * 
 * GET /api/redeem/analytics/time-distribution
 * 
 * Response:
 * {
 *     "total_matched": 45230,
 *     "distribution": {
 *         "3-10 sec": {
 *             "count": 5000,
 *             "percentage": 11.05
 *         },
 *         "11-30 sec": {
 *             "count": 15000,
 *             "percentage": 33.15
 *         },
 *         "31-60 sec": {
 *             "count": 18000,
 *             "percentage": 39.80
 *         },
 *         "1-5 min": {
 *             "count": 7000,
 *             "percentage": 15.47
 *         },
 *         "> 5 min": {
 *             "count": 230,
 *             "percentage": 0.51
 *         }
 *     }
 * }
 */


/**
 * 7. GET Export Merchant Redemptions
 * 
 * GET /api/redeem/export/merchants/1?start_date=2025-12-01&end_date=2025-12-28
 * 
 * Response:
 * {
 *     "merchant_id": 1,
 *     "period": "2025-12-01 to 2025-12-28",
 *     "total_rows": 500,
 *     "data": [
 *         {
 *             "ID": 123,
 *             "MSISDN": "081234567890",
 *             "Keyword": "PROMO100",
 *             "Redeemed At": "2025-12-28 10:02:30",
 *             "Clicked At": "2025-12-28 10:00:00",
 *             "Click-to-Redeem (sec)": 150,
 *             "Poin": 1000
 *         }
 *     ]
 * }
 */
