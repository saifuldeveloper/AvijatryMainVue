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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_book_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('salary', 10, 2)->default(0);
            $table->decimal('limit', 10, 2)->default(0);
            $table->decimal('previous_debt', 10, 2)->default(0);
            $table->decimal('previous_creadit', 10, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->date('salary_start_date')->nullable();
            $table->decimal('previous_salary', 10, 2)->nullable();
            $table->decimal('salary_change', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
