<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\FioTransaction;
use App\Models\UserBankAccounts;
use Carbon\Carbon;
use DomainException;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Safe\DateTimeImmutable;

class GetBankTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-bank-transactions {from?} {to?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download transactions from bank';

    public function handle(): int
    {
        if ($this->argument('from') !== null) {
            $from = Carbon::createFromFormat('Y-m-d', $this->argument('from'));
        } else {
            $from = Carbon::now()
                ->setTime(0, 0)
                ->subDays();
        }

        if ($this->argument('to') !== null) {
            $to = Carbon::createFromFormat('Y-m-d', $this->argument('to'));
        } else {
            $to = Carbon::now()
                ->setTime(0, 0);
        }

        if (!$from || !$to) {
            throw new InvalidArgumentException('Invalid date');
        }

        $bankAccounts = UserBankAccounts::all();

        $url = Config::get('app.fio.transactionsUrl');
        if (!\is_string($url)) {
            throw new InvalidArgumentException('Invalid URL');
        }

        foreach ($bankAccounts as $bankAccount) {

            $url = sprintf(
                $url,
                $bankAccount->accountApiToken,
                $from->format('Y-m-d'),
                $to->format('Y-m-d'),
            );

            $json = Http::get($url)->json();

            if (!\is_array($json)) {
                continue;
            }

            if (isset($json['accountStatement']['transactionList']['transaction'])) {
                $transactionList = (array) $json['accountStatement']['transactionList']['transaction'];
            } else {
                continue;
            }

            foreach ($transactionList as $transaction) {
                try {

                    $keysAndValues = array_merge(
                        $this->sanitizeJsonFromApi((array) $transaction),
                        ['bank_id' => $bankAccount->id],
                    );

                    FioTransaction::updateOrCreate(
                        $keysAndValues,
                    );
                } catch (QueryException|Exception $e) {
                    $errorCode = $e->errorInfo[1] ?? null;
                    if ($errorCode === 1062) {
                        continue;
                    }

                    throw $e;
                }
            }
        }

        return 0;
    }

    /**
     * @param  array<string,array<string,string|int|float|null>>  $json
     * @return array<string,string|int|float|null|DateTimeImmutable>
     *
     * @throws DomainException|InvalidArgumentException|Exception
     */
    private function sanitizeJsonFromApi(array $json): array
    {
        $returnArray = [
            'fio_id' => '',
            'transaction_timestamp' => '',
            'amount' => 0.0,
            'note' => $json['column7']['value'] ?? null,
            'reference' => $json['column27']['value'] ?? null,
            'comment' => $json['column25']['value'] ?? null,
            'note_for_recipient' => $json['column16']['value'] ?? null,
        ];

        if (!isset($json['column22']['value'], $json['column0']['value'], $json['column1']['value'])) {
            throw new DomainException('Invalid JSON from API');
        }

        $fioId = $json['column22']['value'];
        $transactionTimestamp = $json['column0']['value'];
        $amount = $json['column1']['value'];

        if (!\is_string($transactionTimestamp)) {
            throw new InvalidArgumentException('Invalid transactionTimestamp from API');
        }

        if (!\is_int($amount) && !\is_float($amount)) {
            throw new InvalidArgumentException('Invalid amount from API');
        }

        $returnArray['fio_id'] = (string) $fioId;
        $returnArray['transaction_timestamp'] = new DateTimeImmutable($transactionTimestamp);
        $returnArray['amount'] = round($amount, 2);

        return $returnArray;
    }
}
