<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateRedeemMerchantData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redeem:populate-merchant-data {--limit=10000 : Jumlah records per batch} {--from-date= : Filter redemption mulai tanggal ini (YYYY-MM-DD)}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Populate merchant_id, clicked_date, dan diff_click ke tabel tokodigi_tselpoin_redeem dari click_history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $fromDate = $this->option('from-date');

        if ($fromDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            $this->error('Format --from-date harus YYYY-MM-DD');
            return self::FAILURE;
        }
        
        $this->info('🔄 Mulai populate data redemption dengan merchant matching...');
        $this->info("📊 Batch size: {$limit} records");
        if ($fromDate) {
            $this->info("📅 Filter mulai tanggal: {$fromDate}");
        }
        $this->newLine();
        
        $totalUpdated = 0;
        $totalBatches = 0;
        
        while (true) {
            // Cek ada berapa records yang belum ter-fill
            $pendingQuery = DB::table('tokodigi_tselpoin_redeem');

            if ($fromDate) {
                $pendingQuery->whereDate('created_date', '>=', $fromDate);
            }

            $pendingCount = $pendingQuery
                ->where(function ($query) {
                    $query->whereNull('merchant_id')
                        ->orWhereNull('clicked_date')
                        ->orWhereNull('diff_click');
                })
                ->count();
            
            if ($pendingCount == 0) {
                break;
            }
            
            $totalBatches++;
            $this->info("⏳ Batch #{$totalBatches}: Processing {$pendingCount} pending records...");
            
            // Get list of pending redemptions (use msisdn + coupon + created_date as unique key)
            $redemptionsQuery = DB::table('tokodigi_tselpoin_redeem');

            if ($fromDate) {
                $redemptionsQuery->whereDate('created_date', '>=', $fromDate);
            }

            $redemptions = $redemptionsQuery
                ->where(function ($query) {
                    $query->whereNull('merchant_id')
                        ->orWhereNull('clicked_date')
                        ->orWhereNull('diff_click');
                })
                ->limit($limit)
                ->get(['coupon', 'created_date', 'msisdn']);
            
            $updated = 0;
            
            foreach ($redemptions as $redemption) {
                // Find matching click
                $matchingClick = DB::table('click_history')
                    ->where('keyword_id', $redemption->coupon)
                    ->where('clicked_at', '<', $redemption->created_date)
                    ->selectRaw('merchant_id, clicked_at, TIMESTAMPDIFF(SECOND, clicked_at, ?) as time_diff', 
                        [$redemption->created_date])
                    ->whereRaw('TIMESTAMPDIFF(SECOND, clicked_at, ?) > 3', [$redemption->created_date])
                    ->orderBy('time_diff', 'asc')
                    ->first();
                
                if ($matchingClick) {
                    // Update menggunakan msisdn + coupon + created_date sebagai unique key (table tidak punya id)
                    DB::table('tokodigi_tselpoin_redeem')
                        ->where('msisdn', $redemption->msisdn)
                        ->where('coupon', $redemption->coupon)
                        ->where('created_date', $redemption->created_date)
                        ->update([
                            'merchant_id' => $matchingClick->merchant_id,
                            'clicked_date' => $matchingClick->clicked_at,
                            'diff_click' => $matchingClick->time_diff,
                        ]);
                    
                    $updated++;
                }
            }
            
            $totalUpdated += $updated;
            
            $this->line("✅ Batch #{$totalBatches}: {$updated} records updated");
            
            // Prevent memory issues
            if ($pendingCount <= $limit) {
                break;
            }
        }
        
        $this->newLine();
        $this->info("✨ Selesai!");
        $this->info("📈 Total: {$totalUpdated} records updated dalam {$totalBatches} batch");
        
        // Summary stats
        $totalWithMerchantQuery = DB::table('tokodigi_tselpoin_redeem')
            ->whereNotNull('merchant_id');

        if ($fromDate) {
            $totalWithMerchantQuery->whereDate('created_date', '>=', $fromDate);
        }

        $totalWithMerchant = $totalWithMerchantQuery->count();
        
        $totalRedeemsQuery = DB::table('tokodigi_tselpoin_redeem');

        if ($fromDate) {
            $totalRedeemsQuery->whereDate('created_date', '>=', $fromDate);
        }

        $totalRedeems = $totalRedeemsQuery->count();
        
        $matchPercentage = $totalRedeems > 0 ? round(($totalWithMerchant / $totalRedeems) * 100, 2) : 0;
        
        $this->newLine();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Redemptions', $totalRedeems],
                ['Redemptions dengan Merchant Match', $totalWithMerchant],
                ['Match Percentage', "{$matchPercentage}%"],
            ]
        );

        return self::SUCCESS;
    }
}
