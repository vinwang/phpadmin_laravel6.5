<?php

use App\Admin;
use Spatie\Permission\Models\Role;
use App\Models\Permissions;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '超级管理员',
                'guard_name' => 'web',
                'desc' => NULL,
                'status' => 1,
                'created_at' => '2019-11-06 17:38:29',
                'updated_at' => '2019-11-06 17:38:29',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '销售部',
                'guard_name' => 'web',
                'desc' => NULL,
                'status' => 1,
                'created_at' => '2019-11-06 17:39:30',
                'updated_at' => '2019-11-06 17:39:30',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '文案部',
                'guard_name' => 'web',
                'desc' => NULL,
                'status' => 1,
                'created_at' => '2019-11-14 16:00:30',
                'updated_at' => '2019-11-14 16:00:30',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '技术部',
                'guard_name' => 'web',
                'desc' => '技术部门',
                'status' => 1,
                'created_at' => '2019-11-14 16:01:55',
                'updated_at' => '2019-11-18 10:17:44',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '财务部',
                'guard_name' => 'web',
                'desc' => NULL,
                'status' => 1,
                'created_at' => '2019-11-20 13:39:25',
                'updated_at' => '2019-11-20 13:39:25',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '研发部',
                'guard_name' => 'web',
                'desc' => NULL,
                'status' => 1,
                'created_at' => '2019-11-20 13:42:48',
                'updated_at' => '2019-11-20 13:42:48',
            ),
        ));
        
        //超级管理员给全部权限
        Role::find(1)->givePermissionTo(Permissions::all());

        Admin::find(1)->assignRole(1);
    }
}