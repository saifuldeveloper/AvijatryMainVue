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
        Schema::create('liabilities_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('entry_id')->nullable()->index();
            $table->integer('entry_type')->index();  // 0 for entry and 1 for payment
            $table->bigInteger('account_book_id')->index();
            $table->string('description')->nullable();
            $table->double('total_amount');
            $table->string('type')->nullable()->index();
            $table->text('attachments')->nullable(); 
            $table->boolean('status')->default(true)->index();
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
        Schema::dropIfExists('liabilities_entries');
    }
};
