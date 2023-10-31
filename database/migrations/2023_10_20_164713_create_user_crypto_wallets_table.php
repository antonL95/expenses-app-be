<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_crypto_wallets', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->string('walletAddress');
            $table->string('chainType');
            $table->decimal('balanceInUsd', 20, 6)->nullable();
            $table->json('erc20Token')->nullable();
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_crypto_wallets');
    }
};
