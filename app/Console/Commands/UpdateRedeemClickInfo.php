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
        
        // Get redeems yang belum punya merchant_id (NULL)
        $redeems = \DB::table('tokodigi_tselpoin_redeem')
            ->where('program', 'BLANJAPOIN')
            ->whereNull('merchant_id')
            ->orderBy('created_date', 'desc')
            ->limit($limit)
            ->get();
        
        $total = $redeems->count();
        $this->info("Found {$total} redeems to process");
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        foreach ($redeems as $redeem) {
            // Cari click history yang matching:
            // 1. Keyword ID sama (coupon = keyword_id)
            // 2. Click terjadi SEBELUM redeem (clicked_at < created_date)
            // 3. Ambil yang selisih waktunya paling kecil
            
            $matchingClick = \DB::table('click_history')
                ->where('keyword_id', $redeem->coupon)
                ->where('clicked_at', '<', $redeem->created_date)
                ->selectRaw('
                    id,
                    merchant_id,
                    keyword_id,
                    clicked_at,
                    TIMESTAMPDIFF(SECOND, clicked_at, ?) as diff_seconds
                ', [$redeem->created_date])
                ->orderBy('diff_seconds', 'asc')
                ->first();
            
            if ($matchingClick) {
                // Update redeem dengan info dari matching click
                \DB::table('tokodigi_tselpoin_redeem')
                    ->where('id', $redeem->id)
                    ->update([
                        'merchant_id' => $matchingClick->merchant_id,
                        'click_date' => $matchingClick->clicked_at,
                        'diff_click' => $matchingClick->diff_seconds,
                    ]);
                
                $updated++;
            } else {
                $skipped++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Update completed!");
        $this->info("Updated: {$updated}");
        $this->info("Skipped (no matching click): {$skipped}");
        
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
