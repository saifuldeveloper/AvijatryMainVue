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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->longText('supplier_message')->nullable();
            $table->longText('retailer_message')->nullable();
            $table->longText('gift_supplier_message')->nullable();
            $table->longText('collection_message')->nullable();
            $table->boolean('supplier_status')->default(false);
            $table->boolean('retailer_status')->default(false);
            $table->boolean('gift_supplier_status')->default(false);
            $table->boolean('collection_status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
