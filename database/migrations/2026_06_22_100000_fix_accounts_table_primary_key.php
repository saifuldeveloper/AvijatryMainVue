<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove AUTO_INCREMENT and primary key constraint from id, and add composite primary key (id, type)
        DB::statement('ALTER TABLE accounts MODIFY id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE accounts DROP PRIMARY KEY');
        DB::statement('ALTER TABLE accounts ADD PRIMARY KEY (id, type)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to primary key on id (requires making it auto_increment again)
        DB::statement('ALTER TABLE accounts DROP PRIMARY KEY');
        DB::statement('ALTER TABLE accounts MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
    }
};
