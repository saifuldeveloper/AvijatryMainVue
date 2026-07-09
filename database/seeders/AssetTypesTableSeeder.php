<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AssetTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        if (\Schema::hasTable('asset_types')) {
            \DB::table('asset_types')->delete();
        }
        
        
        
    }
}