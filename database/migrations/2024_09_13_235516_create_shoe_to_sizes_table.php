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
        Schema::create('shoe_to_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->string('size_id')->index();
            $table->integer('quantity')->default(0);
            $table->json('sorts')->nullable();
            $table->enum('type', ['purchase', 'sale', 'factory_return', 'retail_return']);
            $table->enum('operation', ['+', '-']);
            $table->string('reference_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoe_to_sizes');
    }
};
