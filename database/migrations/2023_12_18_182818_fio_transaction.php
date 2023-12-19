<?php

declare(strict_types=1);

use App\Models\UserBankAccounts;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fio_transactions', static function (Blueprint $table) {
            $table->foreignIdFor(UserBankAccounts::class, 'bank_id')->after('fio_id');
            $table->foreign('bank_id', 'FK_fio_transactions_bank_id')->references('id')->on('user_bank_accounts');
        });
    }

    public function down(): void
    {
        Schema::table('fio_transactions', static function (Blueprint $blueprint) {
            $blueprint->dropForeign('FK_fio_transactions_bank_id');
            $blueprint->dropColumn(['bank_id']);
        });
    }
};
