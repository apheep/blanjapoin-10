<?php

namespace App\Console\Commands;

use App\Models\Keyword;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncKeywordTrxFromMarchSix extends Command
{
    protected $signature = 'trx:sync-from-2026-03-06';

    protected $description = 'Sync trx keyword dari redeem matched mulai 2026-03-06 tanpa filter program';

    public function handle()
    {
        $fromDate = '2026-03-06';

        $this->info("Sync trx keyword mulai {$fromDate}...");

        $keywords = Keyword::query()
            ->whereNotNull('keyword_id')
            ->get(['id', 'merchant_key', 'keyword_id', 'trx']);

        if ($keywords->isEmpty()) {
            $this->warn('Tidak ada keyword yang bisa diproses.');
            return self::SUCCESS;
        }

        $trxCounts = DB::table('tokodigi_tselpoin_redeem as tr')
            ->select('tr.coupon', 'tr.merchant_id', DB::raw('COUNT(DISTINCT tr.msisdn) as trx_count'))
            ->whereNotNull('tr.merchant_id')
            ->whereNotNull('tr.clicked_date')
            ->whereDate('tr.created_date', '>=', $fromDate)
            ->groupBy('tr.coupon', 'tr.merchant_id')
            ->get()
            ->keyBy(function ($row) {
                return $row->coupon . '::' . $row->merchant_id;
            });

        $updated = 0;

        foreach ($keywords as $keyword) {
            $key = $keyword->keyword_id . '::' . $keyword->merchant_key;
            $trx = (string) ((int) optional($trxCounts->get($key))->trx_count);

            if ((string) $keyword->trx === $trx) {
                continue;
            }

            $keyword->trx = $trx;
            $keyword->save();
            $updated++;
        }

        $this->info("Selesai. {$updated} keyword diupdate.");

        $this->table(
            ['Filter', 'Value'],
            [
                ['Tanggal mulai', $fromDate],
                ['Filter program', 'Tidak digunakan'],
                ['Keyword diupdate', $updated],
            ]
        );

        return self::SUCCESS;
    }
}