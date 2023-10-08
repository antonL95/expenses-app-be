<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kraken_key_pair_current_prices', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('key_pair_id');
            $table->decimal('last_trade_closed', 20, 6);
            $table->decimal('ask', 20, 6);
            $table->decimal('bid', 20, 6);
            $table->decimal('low', 20, 6);
            $table->decimal('high', 20, 6);
            $table->timestamps();
            $table->foreign('key_pair_id')->references('id')->on('kraken_trading_pairs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kraken_key_pair_current_prices');
    }
};
