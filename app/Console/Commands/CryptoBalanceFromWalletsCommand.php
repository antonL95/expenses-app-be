<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enum\ChainType;
use App\Models\CoinGeckoCoinList;
use App\Models\CoinGeckoCurrentMarketPrices;
use Carbon\Carbon;
use Etherscan\Api\Account;
use Etherscan\Client as EthClient;
use Etherscan\Helper\UtilityHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Polygonscan\Client as MaticClient;

class CryptoBalanceFromWalletsCommand extends Command
{
    private const SATOSHI = 1_000_000_00;

    protected $signature = 'app:balance-from-wallets';

    protected $description = 'Fetch and calculate the balance of crypto wallets';

    public function handle(): void
    {
        $this->info('Starting balance update');

        $ethWallets = [
            '0x9DE1524637807e0E5B6e53cA92fd029eFB8aeEA6',
            '0x471D0Fcb8af4A74791be96e6dDCd29Be87DF9095',
        ];

        $btcWallets = [
            'bc1quwy0refcerz2z5rwzfh0yqxg7ln60v80qlkug6',
        ];

        $ethClient = $this->setUpClient(ChainType::ETH);
        $polygonClient = $this->setUpClient(ChainType::MATIC);

        $this->info('Updating ETH balances');

        /** @var Account $ethAccountApi */
        $ethAccountApi = $ethClient->api('account');
        /** @var Account $polygonAccountApi */
        $polygonAccountApi = $polygonClient->api('account');

        $ethResults = $ethAccountApi->balanceMulti(
            implode(',', $ethWallets),
        )['result'];

        $tokenBalancesInUsd = [];

        foreach ($ethResults as $result) {
            $balance = UtilityHelper::convertEtherAmount($result['balance']);

            $this->calculatePriceInUsdForToken($balance, 'tokenTicker', '=', 'eth', $tokenBalancesInUsd, true);
        }

        $polygonResults = $polygonAccountApi->balanceMulti(
            implode(',', $ethWallets),
        )['result'];

        foreach ($polygonResults as $result) {
            $balance = UtilityHelper::convertEtherAmount($result['balance']);

            $this->calculatePriceInUsdForToken($balance, 'tokenTicker', '=', 'matic', $tokenBalancesInUsd);
        }

        $btcResults = (array) Http::get(
            config('app.bitcoin.api_url')
            .'balance?active='
            .implode('|', $btcWallets),
        )->json();

        foreach ($btcResults as $result) {
            $result = (array) $result;

            if (!isset($result['final_balance'])) {
                continue;
            }

            $finalBalance = $result['final_balance'];

            if (!is_numeric($finalBalance)) {
                continue;
            }

            $balance = $finalBalance / self::SATOSHI;

            $this->calculatePriceInUsdForToken($balance, 'tokenTicker', '=', 'btc', $tokenBalancesInUsd);
        }

        $tokenBalances = [];

        foreach ($ethWallets as $wallet) {
            $ehtTransferList = $ethAccountApi->tokenERC20TransferListByAddress(
                $wallet,
            );

            $polygonTransferList = $polygonAccountApi->tokenERC20TransferListByAddress(
                $wallet,
            );

            $this->getErc20TokenBalances($ehtTransferList['result'], $tokenBalances, $wallet);
            $this->getErc20TokenBalances($polygonTransferList['result'], $tokenBalances, $wallet);
        }

        foreach ($tokenBalances as $contractAddress => $balance) {
            $this->calculatePriceInUsdForToken($balance, 'platforms', 'LIKE', "%$contractAddress%", $tokenBalancesInUsd);
        }

        foreach ($tokenBalancesInUsd as $tokenName => $balance) {
            $balance = round($balance, 6);

            if ($balance <= 0) {
                continue;
            }

            $this->info(sprintf('%s: %s', $tokenName, $balance));
        }
    }

    /**
     * @throws \Exception
     */
    private function setUpClient(ChainType $chainType): EthClient|MaticClient
    {
        return match ($chainType) {
            ChainType::ETH => new EthClient(config('app.etherscan.api_key')),
            ChainType::MATIC => new MaticClient(config('app.polygonscan.api_key')),
            default => throw new \Exception('Chain type not supported'),
        };
    }

    /**
     * @param  array<string, string|float|int>  $transferList
     * @param  array<string, float>  $tokenBalances
     */
    private function getErc20TokenBalances(
        array $transferList,
        array &$tokenBalances,
        string $wallet,
    ): void {
        foreach ($transferList as $transfer) {

            if (isset($transfer['tokenSymbol'], $transfer['contractAddress'])) {
                $contractAddress = Str::lower($transfer['contractAddress']);

                if ($transfer['to'] === Str::lower($wallet)) {
                    if (!isset($tokenBalances[$contractAddress])) {
                        $tokenBalances[$contractAddress] = UtilityHelper::convertEtherAmount($transfer['value']);
                    } else {
                        $tokenBalances[$contractAddress] += UtilityHelper::convertEtherAmount($transfer['value']);
                    }
                }

                if ($transfer['from'] === Str::lower($wallet)) {
                    if (!isset($tokenBalances[$contractAddress])) {
                        $tokenBalances[$contractAddress] = -UtilityHelper::convertEtherAmount($transfer['value']);
                    } else {
                        $tokenBalances[$contractAddress] -= UtilityHelper::convertEtherAmount($transfer['value']);
                    }
                }
            }
        }
    }

    private function fetchAndSavePriceForCoinId(CoinGeckoCoinList $coinInfo): CoinGeckoCurrentMarketPrices
    {
        $url = sprintf(
            'https://api.coingecko.com/api/v3/simple/price?ids=%s&vs_currencies=usd',
            $coinInfo->coinId,
        );

        $price = (array) Http::get($url)->json();

        if (!\is_array($price[$coinInfo->coinId])
            || !isset($price[$coinInfo->coinId]['usd'])) {
            throw new \UnexpectedValueException('Invalid response from CoinGecko');
        }

        return CoinGeckoCurrentMarketPrices::updateOrCreate(
            [
                'coinId' => $coinInfo->coinId,
                'price' => $price[$coinInfo->coinId]['usd'],
                'created_at' => Carbon::now(),
            ],
        );
    }

    /**
     * @param  array<string, float|int>  $tokenBalances
     */
    private function calculatePriceInUsdForToken(
        float $balance,
        string $identifier,
        string $operator,
        string $value,
        array &$tokenBalances,
        bool $addJsonLength = false,
    ): void {
        $coinInfoQuery = CoinGeckoCoinList::query()
            ->where($identifier, $operator, $value);

        if ($addJsonLength) {
            $coinInfoQuery->whereJsonLength('platforms', '=', 0);
        }

        $coinInfo = $coinInfoQuery->first();

        if ($coinInfo === null) {
            return;
        }

        $priceInUsd = $coinInfo->marketPrices()->first() ?? $this->fetchAndSavePriceForCoinId($coinInfo);

        $balanceInUsd = $balance * $priceInUsd->price;

        if (isset($tokenBalances[$coinInfo->tokenName])) {
            $tokenBalances[$coinInfo->tokenName] += $balanceInUsd;

            return;
        }

        $tokenBalances[$coinInfo->tokenName] = $balanceInUsd;
    }
}
