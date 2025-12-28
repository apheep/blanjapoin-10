<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRedeemClickInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redeem:update-click-info {--limit=100 : Number of records to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update merchant_id, click_date, and diff_click in tokodigi_tselpoin_redeem table based on matching click_history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update redeem click info...');
        
        $limit = $this->option('limit');
        $updated = 0;
        $skipped = 0;
        $noClick = 0;
        
        // Get redeems yang belum punya merchant_id (NULL)
        $redeems = \DB::table('tokodigi_tselpoin_redeem')
            ->where('program', 'BLANJAPOIN')
            ->whereNull('merchant_id')
            ->orderBy('created_date', 'desc')
            ->limit($limit)
            ->get();
        
        $total = $redeems->count();
        $this->info("Found {$total} redeems to process");
        
        if ($total == 0) {
            $this->info("No records to process");
            return 0;
        }
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        foreach ($redeems as $redeem) {
            try {
                // Cari click history yang matching:
                // 1. Keyword ID sama (coupon = keyword_id)
                // 2. Click terjadi SEBELUM redeem (clicked_at < created_date)
                // 3. Ambil yang selisih waktunya paling kecil
                
                // Debug: Cek apakah ada click untuk keyword ini
                $clickCount = \DB::table('click_history')
                    ->where('keyword_id', $redeem->coupon)
                    ->count();
                
                if ($clickCount == 0) {
                    $noClick++;
                    $bar->advance();
                    continue;
                }
                
                // Cari click yang sebelum redeem
                $matchingClick = \DB::table('click_history')
                    ->where('keyword_id', $redeem->coupon)
                    ->where('clicked_at', '<', $redeem->created_date)
                    ->select(
                        'id',
                        'merchant_id',
                        'keyword_id',
                        'clicked_at',
                        \DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') as diff_seconds")
                    )
                    ->orderByRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redeem->created_date}') ASC")
                    ->first();
                
                if ($matchingClick && $matchingClick->diff_seconds >= 0) {
                    // Update redeem dengan info dari matching click
                    // Update by coupon, msisdn, and created_date (composite key)
                    \DB::table('tokodigi_tselpoin_redeem')
                        ->where('program', 'BLANJAPOIN')
                        ->where('coupon', $redeem->coupon)
                        ->where('msisdn', $redeem->msisdn)
                        ->where('created_date', $redeem->created_date)
                        ->update([
                            'merchant_id' => $matchingClick->merchant_id,
                            'click_date' => $matchingClick->clicked_at,
                            'diff_click' => $matchingClick->diff_seconds,
                        ]);
                    
                    $updated++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $this->error("Error processing redeem ID {$redeem->id}: " . $e->getMessage());
                $skipped++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Update completed!");
        $this->info("Updated: {$updated}");
        $this->info("Skipped (click after redeem or invalid): {$skipped}");
        $this->info("No click history found: {$noClick}");
        
        // Show summary of remaining NULL records
        $remaining = \DB::table('tokodigi_tselpoin_redeem')
            ->where('program', 'BLANJAPOIN')
            ->whereNull('merchant_id')
            ->count();
        
        $this->info("Remaining records with NULL merchant_id: {$remaining}");
        
        if ($remaining > 0) {
            $this->warn("Run this command again with --limit={$limit} to process more records");
        }
        
        return 0;
    }
}
