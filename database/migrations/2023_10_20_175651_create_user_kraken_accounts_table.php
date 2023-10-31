<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_kraken_accounts', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->decimal('accountBalanceInUsd', 20, 6)->nullable();
            $table->string('apiKey');
            $table->string('privateKey');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_kraken_accounts');
    }
};
