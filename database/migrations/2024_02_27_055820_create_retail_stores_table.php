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
        Schema::create('retail_stores', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->index();
            $table->string('address')->index();
            $table->string('mobile_no')->nullable();
            $table->string('mobile_no_two')->nullable();
            $table->boolean('onetime_buyer')->default(false);
            $table->integer('commission')->default(0);
            $table->integer('company_share')->default(0);
            $table->string('secret_key')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_stores');
    }
};
