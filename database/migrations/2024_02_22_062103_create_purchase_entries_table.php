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
        Schema::create('purchase_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id')->index();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->double('purchase_price')->default(0.0);
            $table->double('retail_price')->default(0.0);
            $table->integer('count');
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
        Schema::dropIfExists('purchase_entries');
    }
};
