<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kraken_key_pair_current_prices', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keyPairId');
            $table->decimal('lastTradeClosed');
            $table->decimal('ask');
            $table->decimal('bid');
            $table->decimal('low');
            $table->decimal('high');
            $table->timestamps();
            $table->foreign('keyPairId')->references('id')->on('kraken_trading_pairs');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('kraken_key_pair_current_prices');
    }
};
