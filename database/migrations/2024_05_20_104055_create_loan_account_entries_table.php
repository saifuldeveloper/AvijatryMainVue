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
        Schema::create('loan_account_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('entry_id')->index();
            $table->integer('entry_type')->index();
            $table->bigInteger('account_book_id')->index();
            $table->string('account_name');
            $table->bigInteger('account_id')->index();
            $table->string('account_type')->index();
            $table->string('description')->nullable();
            $table->double('total_amount');
            $table->string('type')->index();
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
        Schema::dropIfExists('loan_account_entries');
    }
};
