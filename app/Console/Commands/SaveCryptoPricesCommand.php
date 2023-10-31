<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CoinGeckoCoinList;
use App\Models\CoinGeckoCurrentMarketPrices;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SaveCryptoPricesCommand extends Command
{
    protected $signature = 'app:save-crypto-prices';

    protected $description = 'Save crypto prices';

    public function handle(): void
    {
        $ids = CoinGeckoCoinList::query()
            ->select('coin_gecko_coin_lists.coinId')
            ->leftJoin(
                'coin_gecko_current_market_prices',
                'coin_gecko_coin_lists.coinId',
                '=',
                'coin_gecko_current_market_prices.coinId',
            )->where(
                'coin_gecko_current_market_prices.coinId',
                '=',
                null,
            )
            ->limit(50)
            ->implode('coin_gecko_coin_lists.coinId', ',');

        if ($ids === '') {
            return;
        }

        CoinGeckoCurrentMarketPrices::query()
            ->whereIn('coinId', explode(',', $ids))
            ->where('created_at', '<', Carbon::now()->subDay())
            ->delete();

        $url = sprintf(
            'https://api.coingecko.com/api/v3/simple/price?%s',
            http_build_query([
                'ids' => $ids,
                'vs_currencies' => 'usd',
            ]),
        );

        $prices = (array) Http::get($url)->json();

        $rows = [];

        foreach ($prices as $coinId => $price) {
            $price = (array) $price;

            if (!isset($price['usd'])) {
                continue;
            }

            $rows[] = [
                'coinId' => $coinId,
                'price' => $price['usd'],
                'created_at' => Carbon::now(),
            ];
        }

        if ($rows === []) {
            return;
        }

        try {
            DB::table('coin_gecko_current_market_prices')->insertOrIgnore($rows);
            DB::table('coin_gecko_historical_market_prices')->insert($rows);
        } catch (UniqueConstraintViolationException) {
        }
    }
}
