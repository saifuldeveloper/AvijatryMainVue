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
        Schema::create('factory_account_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_id')->index();
            $table->string('entry_type')->index();
            $table->bigInteger('account_book_id')->index();
            $table->bigInteger('purchase_id')->nullable()->index();
            $table->integer('count')->default(0);
            $table->double('purchase_price')->nullable();
            $table->double('retail_price')->nullable();
            $table->bigInteger('return_id')->nullable()->index();
            $table->decimal('return_count')->nullable();
            $table->double('return_amount')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('account_id')->nullable()->index();
            $table->string('account_name')->nullable();
            $table->double('total_amount')->default(0);
            $table->string('status')->default(1)->index();
            $table->string('closing_id')->nullable()->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
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
        Schema::dropIfExists('factory_account_entries');
    }
};
