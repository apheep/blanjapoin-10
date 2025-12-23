<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Merchant;
use App\Models\Keyword;

class RecalculateMerchantDiamond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant:recalculate-diamond';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate diamond untuk semua merchant (Total Subsidi - Total Withdraw Approved)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai recalculate diamond untuk semua merchant...');
        $this->info('Diamond = Total Subsidi - Total Withdraw Approved');
        $this->newLine();
        
        // Ambil semua merchant
        $merchants = Merchant::all();
        
        $totalMerchants = $merchants->count();
        $this->info("Total merchant: {$totalMerchants}");
        
        $bar = $this->output->createProgressBar($totalMerchants);
        $bar->start();
        
        $updatedCount = 0;
        $totalDiamond = 0;
        
        foreach ($merchants as $merchant) {
            // Hitung total subsidi dari transaksi (jumlah transaksi × subsidy_amount)
            $transactionData = \DB::table('tokodigi_tselpoin_redeem as tr')
                ->join('keywords as k', 'tr.coupon', '=', 'k.keyword_id')
                ->where('k.merchant_key', $merchant->id)
                ->where('tr.program', 'BLANJAPOIN')
                ->whereNotNull('k.subsidy_amount')
                ->where('k.subsidy_amount', '>', 0)
                ->select('k.keyword_id', 'k.subsidy_amount', \DB::raw('COUNT(*) as trx_count'))
                ->groupBy('k.keyword_id', 'k.subsidy_amount')
                ->get();
            
            $totalSubsidi = 0;
            foreach ($transactionData as $data) {
                $totalSubsidi += $data->trx_count * $data->subsidy_amount;
            }
            
            // Hitung total withdraw yang sudah approved
            $totalWithdrawn = \App\Models\WithdrawRequest::where('merchant_id', $merchant->id)
                ->where('status', 'approved')
                ->sum('jumlah');
            
            // Diamond = Total Subsidi - Total Withdraw Approved
            $diamondBalance = max(0, $totalSubsidi - $totalWithdrawn);
            
            // Update merchant diamond
            $merchant->diamond = $diamondBalance;
            $merchant->save();
            
            if ($diamondBalance > 0) {
                $updatedCount++;
                $totalDiamond += $diamondBalance;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info('Recalculate diamond selesai!');
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Merchant dengan diamond: {$updatedCount}");
        $this->info("- Total diamond semua merchant: " . number_format($totalDiamond, 0, ',', '.'));
        
        return Command::SUCCESS;
    }
}
