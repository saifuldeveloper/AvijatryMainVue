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
        Schema::create('commitments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_book_id')->nullable()->index();
            $table->date('commitment_date');
            $table->double('amount')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('attechment_id')->nullable()->index();
            $table->string('attechment_type')->nullable();
            $table->string('attechment_name')->nullable();
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable();
            $table->string('status')->nullable()->index();
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
        Schema::dropIfExists('commitments');
    }
};
