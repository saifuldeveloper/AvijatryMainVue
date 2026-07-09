<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PasswordResetsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        if (\Schema::hasTable('password_resets')) {
            \DB::table('password_resets')->delete();
        } elseif (\Schema::hasTable('password_reset_tokens')) {
            \DB::table('password_reset_tokens')->delete();
        }
        
        
        
    }
}