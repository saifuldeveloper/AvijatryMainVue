<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_account_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_id')->index();
            $table->integer('entry_type')->default(0);
            $table->integer('account_book_id')->index();
            $table->string('account_name')->nullable();
            $table->integer('account_id')->index();
            $table->string('account_type')->index();
            $table->string('description')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('closing_id')->nullable()->index();
            $table->double('total_amount');
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
        Schema::dropIfExists('employee_account_entries');
    }
};
