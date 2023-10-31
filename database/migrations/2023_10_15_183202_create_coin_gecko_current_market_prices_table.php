<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_gecko_current_market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('coinId')->unique();
            $table->decimal('price', 30, 8);
            $table->timestamps();
            $table->foreign('coinId')->references('coinId')->on('coin_gecko_coin_lists');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_gecko_current_market_prices');
    }
};
