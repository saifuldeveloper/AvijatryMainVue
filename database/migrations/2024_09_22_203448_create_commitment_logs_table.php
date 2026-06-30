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
        Schema::create('commitment_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('commitment_id')->index();
            $table->double('paid_amount')->nullable();
            $table->string('status')->nullable()->index();
            $table->date(column: 'commitment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commitment_logs');
    }
};
