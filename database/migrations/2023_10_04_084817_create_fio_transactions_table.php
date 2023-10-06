<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fio_transactions', static function (Blueprint $table) {
            $table->id();
            $table->string('fio_id')->unique();
            $table->date('transaction_timestamp');
            $table->decimal('amount', 10);
            $table->string('note')->nullable();
            $table->string('reference')->nullable();
            $table->string('comment')->nullable();
            $table->string('note_for_recipient')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fio_transactions');
    }
};
