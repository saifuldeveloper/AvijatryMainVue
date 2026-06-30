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
        Schema::create('invoice_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->index();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->integer('count');
            $table->double('retail_price')->default(0.0);
            $table->string('size_name')->nullable();
            $table->integer('retail_store_received_count')->default(0);
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
        Schema::dropIfExists('invoice_entries');
    }
};
