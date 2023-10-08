<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\KrakenKeyPairCurrentPrice;
use App\Models\KrakenTradingPairs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class KrakenAssetsPairDownloadCommand extends Command
{
    protected $signature = 'kraken:assets-pair-download {tickerPair?}';

    protected $description = 'Download tradable ticker pairs';

    public function handle(): void
    {
        $url = Config::get('app.kraken.publicEndpoint');

        if (!\is_string($url)) {
            throw new \DomainException('Url should be string');
        }

        if ($this->argument('tickerPair') !== null) {
            $url .= '?pair='.$this->argument('tickerPair');
        }

        $json = Http::get($url)->json();

        \assert(\is_array($json));

        if ($json['error'] !== []) {
            throw new \DomainException($json['error']);
        }

        $pairs = $json['result'];
        $results = [];

        foreach ($pairs as $pair => $tradeValues) {
            $fiatTicker = Str::lower(Str::substr((string) $pair, -3, 3));
            if (!\in_array($fiatTicker, ['usd', 'eur'])) {
                continue;
            }
            $results[$pair] = $tradeValues;

            $keyPair = KrakenTradingPairs::where('key_pair', '=', $pair)->first();

            if ($keyPair === null) {
                $keyPair = KrakenTradingPairs::create([
                    'key_pair' => $pair,
                    'crypto' => Str::upper(Str::replace($fiatTicker, '', Str::lower((string) $pair))),
                    'fiat' => Str::upper($fiatTicker),
                ]);

                $this->info('Pair '.$pair.' added');
            }

            KrakenKeyPairCurrentPrice::create([
                'key_pair_id' => $keyPair->id,
                'ask' => $tradeValues['a'][0],
                'bid' => $tradeValues['b'][0],
                'last_trade_closed' => $tradeValues['c'][0],
                'low' => $tradeValues['l'][0],
                'high' => $tradeValues['h'][0],
            ]);
        }

        $this->info('Downloaded '.\count($results).' pairs');
    }
}
