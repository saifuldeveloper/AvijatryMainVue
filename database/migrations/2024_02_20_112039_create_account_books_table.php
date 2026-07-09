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
        Schema::create('account_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->index();
            $table->string('account_type')->index();
            $table->double('previous_balance')->default(0);
            $table->boolean('open')->default(true);
            $table->double('commission')->default(0);
            $table->double('commission_percentage')->default(0);
            $table->double('staff')->default(0);
            $table->double('staff_percentage')->default(0);
            $table->double('staff_shoe_amount')->default(0);
            $table->double('discount')->default(0);
            $table->double('due')->default(0);
            $table->date('deadline')->nullable();
            $table->tinyInteger('balance_carry_forward')->nullable();
            $table->double('closing_balance')->default(0);
            $table->string('closing_type')->nullable()->default(null);
            $table->date('closing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_books');
    }
};
