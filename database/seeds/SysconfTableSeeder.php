<?php

use Illuminate\Database\Seeder;

class SysconfTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sysconf')->delete();
        
        \DB::table('sysconf')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'cycle',
                'value' => '20',
            ),
        ));
        
        
    }
}