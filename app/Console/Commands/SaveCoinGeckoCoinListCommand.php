<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use function Safe\json_encode;

class SaveCoinGeckoCoinListCommand extends Command
{
    protected $signature = 'app:save-coingecko-coin-list';

    protected $description = 'Command description';

    public function handle(): void
    {
        $coinsInfo = (array) Http::get(
            'https://api.coingecko.com/api/v3/coins/list?include_platform=true',
        )->json();

        $rows = [];

        foreach ($coinsInfo as $coinInfo) {
            $coinInfo = (array) $coinInfo;
            $platforms = $coinInfo['platforms'] ?? null;

            $rows[] = [
                'coinId' => $coinInfo['id'],
                'tokenName' => $coinInfo['name'],
                'tokenTicker' => $coinInfo['symbol'],
                'platforms' => $platforms ? json_encode($platforms) : null,
            ];

            if (\count($rows) >= 20000) {
                DB::table('coin_gecko_coin_lists')->insertOrIgnore($rows);

                $rows = [];
            }
        }

        DB::table('coin_gecko_coin_lists')->insertOrIgnore($rows);
    }
}
