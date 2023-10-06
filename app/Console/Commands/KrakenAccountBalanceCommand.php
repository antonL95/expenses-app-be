<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\KrakenKeyPairCurrentPrice;
use App\Models\KrakenTradingPairs;
use App\Services\KrakenMessageSigner;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class KrakenAccountBalanceCommand extends Command
{
    protected $signature = 'kraken:account-balance';

    protected $description = 'Get account balance from Kraken';


    public function handle(): int
    {
        $this->newLine();
        $this->info('Getting account balance from Kraken');
        $this->newLine();

        $krakenApiUrl = (string) config('app.kraken.apiUrl');

        $nonce = Carbon::now()->timestamp;
        $path = '/0/private/Balance';
        $request = [
            'nonce' => $nonce,
        ];

        $signedMessage = KrakenMessageSigner::signMessage($path, $request, $nonce);

        $json = Http::send(
            'POST',
            $krakenApiUrl . $path,
            [
                'headers' => [
                    'API-Key' => config('app.kraken.apiKey'),
                    'API-Sign' => $signedMessage,
                ],
                'form_params' => $request,
            ],
        )->json();

        \assert(\is_array($json));

        if ($json['error'] !== []) {
            throw new \DomainException($json['error'][0]);
        }

        $result = $json['result'];
        $totalInUsd = 0;

        foreach ($result as $asset => $balance) {
            $balance = (double) $balance;

            if ($balance <= 0) {
                continue;
            }

            $balance = number_format($balance, 6, '.', '');

            $krakenTradingPairs = KrakenTradingPairs::where('crypto', '=', $asset)
                ->where('fiat', '=', 'usd')
                ->first();

            if ($krakenTradingPairs === null) {
                $krakenTradingPairs = KrakenTradingPairs::where(
                    'crypto',
                    '=',
                    Str::replace('x', '', Str::lower($asset)),
                )->where('fiat', 'usd')->first();
            }

            if ($krakenTradingPairs === null) {
                $krakenTradingPairs = KrakenTradingPairs::where(
                    'key_pair',
                    'LIKE',
                    \sprintf('%%%s%%', $asset),
                )->where(
                    'fiat',
                    '=',
                    'usd',
                )->first();
            }

            if ($krakenTradingPairs === null) {
                $this->info(
                    \sprintf('Asset [%s] doest not exist in database', $asset),
                );
            } else {
                $krakenCurrentPrice = $krakenTradingPairs->currentPrice()->latest()->first();

                if (!$krakenCurrentPrice instanceof KrakenKeyPairCurrentPrice) {
                    continue;
                }

                $priceInUsd = $krakenCurrentPrice->last_trade_closed * $balance;

                if ($priceInUsd < 1) {
                    continue;
                }

                $totalInUsd += $priceInUsd;

                $this->info(
                    \sprintf(
                        'Asset [%s] balance: %s and in usd: %s',
                        $asset,
                        $balance,
                        $krakenCurrentPrice->last_trade_closed * $balance,
                    ),
                );
            }
        }

        $this->newLine();

        $this->info(\sprintf('Total in usd: %s', number_format($totalInUsd, 2, '.', '')));

        return 0;
    }
}
