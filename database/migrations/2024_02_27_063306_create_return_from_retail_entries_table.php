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
        Schema::create('return_from_retail_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('account_book_id')->index();
            $table->unsignedBigInteger('return_id')->nullable()->index();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->double('retail_price')->default(0.0); 
            $table->integer('count');
            $table->double('commission');
            $table->string('status')->default('pending')->index();
            $table->integer('factory_return')->default(0);
            $table->integer('waste')->default(0);
            $table->integer('inventory')->default(0);
            $table->integer('invoice_id')->default(0)->index();
            $table->integer('extra_shoe')->default(0);
            $table->unsignedBigInteger('factory_return_entry_id')->default(0)->index();  //For Factory Return Entry ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_from_retail_entries');
    }
};
