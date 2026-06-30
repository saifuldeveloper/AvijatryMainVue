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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('address')->index();
            $table->string('mobile_no');
            $table->string('mobile_no_two')->nullable();
            $table->string('image')->nullable();
            $table->double('limit')->nullable();
            $table->double('salary')->nullable();
            $table->date('join_date')->nullable();
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
        Schema::dropIfExists('employees');
    }
};
