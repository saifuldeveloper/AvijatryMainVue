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
        Schema::create('retail_store_shoes', function (Blueprint $table) {
            $table->id();
            $table->string('retail_store_id')->index();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->boolean('is_approved')->default(false);
            $table->integer('quantity')->default(0);
            $table->longText('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_store_shoes');
    }
};
