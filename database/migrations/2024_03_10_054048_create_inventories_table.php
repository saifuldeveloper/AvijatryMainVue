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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shoe_id')->unique()->index();
            $table->string('factory')->nullable()->index();
            $table->string('category')->nullable()->index();
            $table->string('color')->nullable()->index();
            $table->decimal('purchase_price', 12, 2)->nullable()->index();
            $table->decimal('retail_price', 12, 2)->nullable()->index();
            $table->decimal('count')->default(0)->nullable()->index();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();


            // Composite indexes for faster filtering queries
            $table->index(['factory', 'category']); // e.g., all shoes of category X in factory Y
            $table->index(['factory', 'color']);    // e.g., all shoes of color Z in factory Y
            $table->index(['created_at']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
