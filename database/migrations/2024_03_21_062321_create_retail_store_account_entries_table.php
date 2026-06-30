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
        Schema::create('retail_store_account_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_id')->nullable()->index();
            $table->unsignedTinyInteger('entry_type')->nullable()->index();
            $table->bigInteger('account_book_id')->nullable()->index();
            $table->integer('invoice_id')->nullable()->index();
            $table->decimal('count', 15, 2)->nullable();
            $table->double('total_retail_price', 15, 2)->nullable();
            $table->bigInteger('return_id')->nullable()->index();
            $table->decimal('return_count')->nullable();
            $table->double('return_amount')->nullable();
            $table->double('return_amount_without_commission')->nullable();
            $table->mediumText('expense_description')->nullable();
            $table->bigInteger('expense_id')->nullable()->index();
            $table->double('expense_amount')->nullable();
            $table->double('total_commission')->nullable();
            $table->double('commission_amount')->nullable();
            $table->double('transport')->nullable();
            $table->double('discount')->nullable();
            $table->double('amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->string('description')->nullable();
            $table->mediumText('account_name')->nullable();
            $table->string('closing_id')->nullable()->index();
            $table->integer('is_discount_product_sale')->default(0);
            $table->string('status')->nullable()->index();
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
        Schema::dropIfExists('retail_store_account_entries');
    }
};
