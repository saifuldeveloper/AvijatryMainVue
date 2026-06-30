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
        Schema::create('waste_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->integer('count');
            $table->string('description')->nullable();
            $table->bigInteger('entries_id')->index();
            $table->string('entries_type')->index();
            $table->bigInteger('account_book_id')->nullable()->index();
            $table->bigInteger('account_id')->nullable()->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->string('inventory_count')->nullable();
            $table->unsignedBigInteger('retail_store_return_entry_id')->default(0)->index();  // For RetailStore Return Entry ID
            $table->integer('pending_return_waste')->default(0);  // For pending return waste entry
            $table->softDeletes();
            $table->timestamps();

            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_entries');
    }
};
