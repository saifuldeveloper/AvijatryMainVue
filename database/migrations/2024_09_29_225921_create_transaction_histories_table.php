<?php

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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable();
            $table->bigInteger('model_id')->nullable()->index();
            $table->bigInteger('transaction_id_one')->nullable()->index();
            $table->bigInteger('transaction_id_two')->nullable()->index();
            $table->string('entry_bank_model')->nullable();
            $table->bigInteger('entry_bank_id')->nullable()->index();
            $table->string('entry_khata_model')->nullable();
            $table->bigInteger('entry_khata_model_id')->nullable()->index();
            $table->string('entry_model_cheque')->nullable();
            $table->bigInteger('entry_model_cheque_id')->nullable()->index();
            $table->string('type')->nullable()->index();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};
