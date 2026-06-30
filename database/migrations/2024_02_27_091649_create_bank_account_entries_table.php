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
        Schema::create('bank_account_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id')->index();
            $table->integer('entry_type')->index();
            $table->bigInteger('account_book_id')->index();
            $table->string('account_name')->nullable();
            $table->bigInteger('account_id')->index();
            $table->string('account_type')->index();
            $table->string('description')->nullable();
            $table->bigInteger('to_account_book_id')->nullable()->index();
            $table->double('total_amount');
            $table->string('type')->nullable()->index();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account_entries');
    }
};
