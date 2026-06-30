<?php

use App\Enums\ReturnToRetailerStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_to_retailers', function (Blueprint $table) {
            $table->id();
            $table->string('return_no')->unique()->index();
            $table->integer('account_book_id')->index();
            $table->integer('invoice_id')->default(0)->index();
            $table->enum('status', ReturnToRetailerStatus::values())->default(ReturnToRetailerStatus::PENDING->value)->index();
            $table->date('return_date');
            $table->text('note')->nullable();
            // Audit Columns
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_to_retailers');
    }
};
