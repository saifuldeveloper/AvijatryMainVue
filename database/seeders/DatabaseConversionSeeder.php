<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class DatabaseConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting database conversion process...');

        // 1. Idempotency Check: Skip if shoes table has 'code' column AND 'id' is integer/bigint.
        if (Schema::hasColumn('shoes', 'code')) {
            try {
                $type = Schema::getColumnType('shoes', 'id');
                if ($type !== 'string' && $type !== 'varchar') {
                    $this->command->info('Database is already in the new format (id is integer, code column exists). Skipping conversion.');
                    return;
                }
            } catch (\Exception $e) {
                // If getColumnType fails (e.g. driver mismatch), proceed safely
            }
        }

        // Run the conversion queries directly (MySQL does not support transactional DDL, so each runs and commits immediately)
        // 2. Drop view if exists
        $this->command->info('Dropping view "invoice_items"...');
        DB::statement("DROP VIEW IF EXISTS invoice_items");

        // 3. Add 'code' column to shoes table
        if (!Schema::hasColumn('shoes', 'code')) {
            $this->command->info('Adding "code" column to "shoes" table...');
            Schema::table('shoes', function (Blueprint $table) {
                $table->string('code')->nullable()->after('id');
            });
        }

        // Copy old string 'id' to 'code'
        $this->command->info('Copying old string "id" to "code" column...');
        DB::statement("UPDATE shoes SET code = id");

        // Add a temporary index on code to make JOIN updates fast
        $this->command->info('Adding temporary index on "shoes.code"...');
        Schema::table('shoes', function (Blueprint $table) {
            $table->index('code');
        });

        // 4. Add temporary 'new_id' column to shoes table
        if (!Schema::hasColumn('shoes', 'new_id')) {
            $this->command->info('Adding temporary "new_id" column to "shoes" table...');
            Schema::table('shoes', function (Blueprint $table) {
                $table->unsignedBigInteger('new_id')->nullable()->after('id');
            });
        }

        // Populate 'new_id' sequentially (1, 2, 3...)
        $this->command->info('Populating "new_id" sequentially...');
        DB::statement("SET @r := 0");
        DB::statement("UPDATE shoes SET new_id = (@r := @r + 1) ORDER BY code ASC");

        // 5. Update child tables
        $childTables = [
            'purchase_entries',
            'invoice_entries',
            'waste_entries',
            'return_to_factory_entries',
            'return_from_retail_entries',
            'inventory_check_entries',
            'adjustment_entries',
            'shoe_to_sizes',
            'retail_store_shoes',
        ];

        foreach ($childTables as $table) {
            if (!Schema::hasTable($table)) {
                $this->command->warn("Table '{$table}' does not exist, skipping.");
                continue;
            }

            $this->command->info("Converting references in table '{$table}'...");

            // Map old string 'shoe_id' to 'new_id', setting unmatched/orphans to '0' in a single pass
            DB::statement("
                UPDATE {$table} 
                LEFT JOIN shoes ON {$table}.shoe_id = shoes.code 
                SET {$table}.shoe_id = IFNULL(CAST(shoes.new_id AS CHAR), '0')
            ");

            // Modify the column type to BIGINT UNSIGNED
            DB::statement("ALTER TABLE {$table} MODIFY shoe_id BIGINT UNSIGNED NOT NULL");
        }

        // 6. Convert inventories table
        if (Schema::hasTable('inventories')) {
            if (Schema::hasColumn('inventories', 'id') && !Schema::hasColumn('inventories', 'shoe_id')) {
                $this->command->info('Converting "inventories" table structure...');

                // Drop primary key constraint
                DB::statement("ALTER TABLE inventories DROP PRIMARY KEY");

                // Add shoe_id temporarily as varchar
                Schema::table('inventories', function (Blueprint $table) {
                    $table->string('shoe_id')->nullable()->after('id');
                });

                // Copy old 'id' (which has old shoe string code) to 'shoe_id'
                DB::statement("UPDATE inventories SET shoe_id = id");

                // Map shoe_id to new integer ID, setting unmatched/orphans to '0' in a single pass
                DB::statement("
                    UPDATE inventories 
                    LEFT JOIN shoes ON inventories.shoe_id = shoes.code 
                    SET inventories.shoe_id = IFNULL(CAST(shoes.new_id AS CHAR), '0')
                ");

                // Delete orphans where shoe_id is 0
                DB::table('inventories')->where('shoe_id', '0')->delete();

                // Drop old id column
                Schema::table('inventories', function (Blueprint $table) {
                    $table->dropColumn('id');
                });

                // Add new auto-incrementing id as primary key
                DB::statement("ALTER TABLE inventories ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");

                // Modify shoe_id type to BIGINT UNSIGNED and add unique key
                DB::statement("ALTER TABLE inventories MODIFY shoe_id BIGINT UNSIGNED NOT NULL");
                Schema::table('inventories', function (Blueprint $table) {
                    $table->unique('shoe_id');
                });
            } else {
                $this->command->info('Inventories table is already converted.');
            }
        }

        // 7. Finalize shoes table structure
        $this->command->info('Finalizing "shoes" table structure...');
        // Drop old primary key (if exists) and drop column
        try {
            DB::statement("ALTER TABLE shoes DROP PRIMARY KEY");
        } catch (\Exception $e) {
            // Ignore if no primary key exists
        }
        DB::statement("ALTER TABLE shoes DROP COLUMN id");

        // Rename new_id to id
        Schema::table('shoes', function (Blueprint $table) {
            $table->renameColumn('new_id', 'id');
        });

        // Set id as primary key and auto_increment
        DB::statement("ALTER TABLE shoes ADD PRIMARY KEY (id)");
        DB::statement("ALTER TABLE shoes MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");

        // Make code column not null and unique
        DB::statement("ALTER TABLE shoes MODIFY code VARCHAR(255) NOT NULL");
        Schema::table('shoes', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->unique('code');
        });

        // 8. Recreate view invoice_items
        $this->command->info('Recreating "invoice_items" view...');
        DB::statement("CREATE OR REPLACE VIEW invoice_items AS
            SELECT invoice_entries.invoice_id, parents.id parent_id, CONCAT_WS('-', parents.name, GROUP_CONCAT(DISTINCT categories.name SEPARATOR '+')) description, shoes.retail_price, sum(invoice_entries.count) count, sum(invoice_entries.retail_store_received_count) retail_store_received_count, (shoes.retail_price * sum(invoice_entries.count)) total_price
            FROM invoice_entries
            INNER JOIN shoes ON invoice_entries.shoe_id = shoes.id
            INNER JOIN categories ON shoes.category_id = categories.id
            INNER JOIN categories parents ON categories.parent_id = parents.id
            GROUP BY invoice_entries.invoice_id, parents.id, parents.name, shoes.retail_price
            ORDER BY invoice_entries.invoice_id ASC, parents.id ASC, shoes.retail_price DESC");
        // End of conversion queries

        $this->command->info('Database conversion completed successfully!');
    }
}
