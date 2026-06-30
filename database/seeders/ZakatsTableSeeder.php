<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ZakatsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('zakats')->delete();
        
        
        
    }
}