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
        Schema::create('zakats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_year_id')->nullable()->index(); // closing_periods table relation
            $table->decimal('system_assets', 15, 2); // netcapital_value
            $table->json('custom_assets')->nullable(); // dynamic custom fields
            $table->decimal('total_assets', 15, 2);
            $table->decimal('zakat_payable', 15, 2);
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakats');
    }
};
