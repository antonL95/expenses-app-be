<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\UserBankAccounts;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class GetCurrentBankAccountBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-current-bank-account-balance-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current account balance';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $to = Carbon::now()
            ->setTime(0, 0);

        $url = Config::get('app.fio.transactionsUrl');
        if (!\is_string($url)) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $bankAccounts = UserBankAccounts::all();

        foreach ($bankAccounts as $bankAccount) {
            $url = sprintf(
                $url,
                $bankAccount->accountApiToken,
                $to->format('Y-m-d'),
                $to->format('Y-m-d'),
            );

            $json = Http::get($url)->json();

            if (!\is_array($json)) {
                return 255;
            }

            if (!isset($json['accountStatement']['info']['closingBalance'])) {
                continue;
            }

            $bankAccount->accountBalance = (float) $json['accountStatement']['info']['closingBalance'];
            $bankAccount->save();
        }

        return 0;
    }
}
