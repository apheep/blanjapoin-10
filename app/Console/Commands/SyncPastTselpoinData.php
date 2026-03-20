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
    protected $signature = 'sync:past-tselpoin {--coupon=A3EVOINDORAFI2026003 : Coupon yang akan disync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync past data from IGX_TSELPOIN ke tokodigi_tselpoin_redeem sejak 6 Maret 2026, bisa difilter per coupon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $db = DB::connection()->getDatabaseName();
        $coupon = (string) $this->option('coupon');

        $this->info('Starting data sync...');
        $this->info("Coupon filter: {$coupon}");

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
            AND i.coupon = ?
            AND t.coupon IS NULL
        ";

        $inserted = DB::affectingStatement($query, [$coupon]);

        $this->info("Data from March 6, 2026 synced successfully! Inserted: {$inserted}");
    }
}
