<?php

use Illuminate\Database\Seeder;

class UserGradeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_grade')->delete();
        
        \DB::table('user_grade')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '主管',
                'created_at' => '2019-12-21 10:27:49',
                'updated_at' => '2019-12-21 10:27:49',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '普通员工',
                'created_at' => '2019-12-21 10:28:05',
                'updated_at' => '2019-12-21 10:28:05',
            ),
        ));
        
        
    }
}