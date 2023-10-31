<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_bank_accounts', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->string('accountName');
            $table->string('bankName')->nullable();
            $table->string('accountNumber')->nullable();
            $table->string('bankCode')->nullable();
            $table->string('accountApiToken');
            $table->decimal('accountBalance', 20, 6)->nullable();
            $table->string('accountCurrency')->nullable();
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bank_accounts');
    }
};
