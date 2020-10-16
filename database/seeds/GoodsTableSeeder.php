<?php

use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('goods')->delete();
        
        \DB::table('goods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'IDC',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-13 11:49:49',
                'updated_at' => '2020-01-13 11:49:49',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'IRCS',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-13 11:49:54',
                'updated_at' => '2020-01-13 11:49:54',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'CDN',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-13 11:49:58',
                'updated_at' => '2020-01-13 11:49:58',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'ISP',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-14 14:22:56',
                'updated_at' => '2020-01-14 14:22:56',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '固网',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'VPN',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'SP',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'ICP',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'EDI',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '呼叫中心',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => '国内多方通信',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => '106码号',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => '95码号',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => '固定网数据传送',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => '网络文化经营许可证',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => '三级等保',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => '其他',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => '新公司股权转让业务',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => '许可证续期业务',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => '许可证注销业务',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => '网络托管',
                'description' => NULL,
                'uid' => 1,
                'status' => 1,
                'created_at' => '2020-01-16 16:05:10',
                'updated_at' => '2020-01-16 16:05:10',
            ),
        ));
        
        
    }
}