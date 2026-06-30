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
        Schema::create('shoes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->bigInteger('factory_id')->default(0)->index();
            $table->bigInteger('category_id')->index();
            $table->bigInteger('color_id')->index();
            $table->double('purchase_price')->default(0);
            $table->double('retail_price');
            $table->bigInteger('box_id')->index();
            $table->bigInteger('bag_id')->index();
            $table->string('image')->index();
            $table->integer('initial_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoes');
    }
};
