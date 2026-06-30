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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_number')->nullable();
            $table->integer('account_book_id')->index();
            $table->double('amount')->default(0);
            $table->date('due_date')->nullable();
            $table->string('attachment_type')->nullable();
            $table->integer('attachment_id')->default(0)->index();
            $table->integer('closing_id')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
