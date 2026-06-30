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
        Schema::create('adjustment_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shoe_id')->index();
            $table->integer('count');
            $table->string('description')->nullable();
            $table->string('type')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_entries');
    }
};
