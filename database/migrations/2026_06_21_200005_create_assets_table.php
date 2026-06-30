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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->integer('entry_type')->index();  // 0 for buy/entry, 1 for sale/return/write-off
            $table->foreignId('account_book_id')->constrained('account_books')->cascadeOnDelete();
            $table->double('asset_cost', 12, 2);
            $table->boolean('depreciation')->default(false);
            $table->string('depreciation_type')->nullable(); // monthly, yearly
            $table->double('salvage_value', 12, 2)->nullable();
            $table->double('useful_life', 5, 2)->nullable();
            $table->double('daily_depreciation', 12, 2)->nullable();
            $table->longText('note')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->string('type')->nullable()->index(); // e.g., normal, etc.
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('no action');
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['account_book_id', 'entry_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
