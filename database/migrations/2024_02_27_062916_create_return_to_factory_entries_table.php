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
        Schema::create('return_to_factory_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('return_id')->index();
            $table->bigInteger('account_book_id')->index();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->double('purchase_price')->default(0.0); 
            $table->integer('count');
            $table->string('status')->default('pending')->index();
            $table->integer('factory_return')->default(0);
            $table->integer('waste')->default(0);
            $table->unsignedBigInteger('retail_store_return_entry_id')->default(0)->index(); 
            $table->integer('ret_pnd_factory_ret')->default(0);
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
        Schema::dropIfExists('return_to_factory_entries');
    }
};
