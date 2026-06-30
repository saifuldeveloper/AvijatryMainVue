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
        Schema::create('return_to_factories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id')->index();
            $table->bigInteger('account_book_id')->index();
            $table->string('status')->default('pending')->index();
            $table->unsignedBigInteger('retail_store_return_entry_id')->default(0)->index();  // For RetailStore Return Entry ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_to_factories');
    }
};
