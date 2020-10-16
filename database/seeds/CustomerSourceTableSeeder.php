<?php

use Illuminate\Database\Seeder;

class CustomerSourceTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customer_source')->delete();
        
        \DB::table('customer_source')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '销售录入',
                'states' => 1,
                'created_at' => '2019-12-22 19:02:12',
                'updated_at' => '2019-12-22 19:02:12',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '原有客户',
                'states' => 1,
                'created_at' => '2019-12-22 19:02:31',
                'updated_at' => '2019-12-22 19:02:31',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '公司分配',
                'states' => 1,
                'created_at' => '2019-12-22 19:02:31',
                'updated_at' => '2019-12-22 19:02:31',
            ),
        ));
        
        
    }
}