<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPastTselpoinData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:past-tselpoin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync past data from IGX_TSELPOIN to tokodigi_tselpoin_redeem since March 6, 2026';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $db = DB::connection()->getDatabaseName();
        $this->info("Starting data sync...");

        $query = "
            INSERT INTO {$db}.tokodigi_tselpoin_redeem (
                program, 
                coupon, 
                msisdn, 
                keyword_desc, 
                poin_redeem, 
                created_date
            )
            SELECT 
                i.program, 
                i.coupon, 
                i.msisdn, 
                i.keyword_desc, 
                i.poin_redeem, 
                i.created_date
            FROM tokodigi_app.IGX_TSELPOIN i
            LEFT JOIN {$db}.tokodigi_tselpoin_redeem t 
                ON t.coupon = i.coupon AND t.program = i.program
            WHERE i.created_date >= '2026-03-06 00:00:00'
            AND t.coupon IS NULL
        ";

        DB::unprepared($query);

        $this->info('Data from March 6, 2026 synced successfully!');
    }
}
