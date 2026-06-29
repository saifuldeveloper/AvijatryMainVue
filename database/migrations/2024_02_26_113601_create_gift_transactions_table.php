<?php

use Illuminate\Database\Eloquent\SoftDeletes;
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
        Schema::create('gift_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->integer('gift_id')->index();
            $table->integer('count');
            $table->integer('retail_store_received_count')->default(0);
            $table->double('unit_price')->default(0);
            $table->integer('attachment_id')->nullable()->index();
            $table->string('attachment_type')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('description')->nullable();
            $table->softDeletes();
             $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_transactions');
    }
};
