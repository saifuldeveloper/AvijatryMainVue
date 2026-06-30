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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('account_book_id')->index();
            $table->double('commission')->default(0);
            $table->double('transport')->default(0);
            $table->double('discount')->default(0);
            $table->integer('is_discount_product_sale')->default(0);
            $table->enum('retail_store_status', ['None', 'Pending', 'Approved', 'Rejected'])->default('None');
            $table->longText('retail_store_remarks')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
