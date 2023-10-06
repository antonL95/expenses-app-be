<?php

declare(strict_types=1);

use App\Models\FioTransaction;

test('should download transactions from Fio bank', function () {
    $this->artisan('app:download-fio-bank-transactions 2023-10-03 2023-10-04')
        ->assertExitCode(0);

    expect(
        FioTransaction::query()->where(
            'created_at',
            '>',
            '2023-10-03',
        )->count(),
    )->toBe(2);
});
