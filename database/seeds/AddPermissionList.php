<?php

use Illuminate\Database\Seeder;

class AddPermissionList extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manager_permissions')->insert([
            [
                'id' => 10008,
                'name' => 'manager.permission.index',
                'label' => '权限列表',
                'cid' => 10000,
                'icon' => '&#xe60a;',
            ],
            [
                'id' => 10009,
                'name' => 'manager.permission.add',
                'label' => '添加权限',
                'cid' => 10008,
                'icon' => '',
            ],
            [
                'id' => 10010,
                'name' => 'manager.permission.edit',
                'label' => '编辑权限',
                'cid' => 10008,
                'icon' => '',
            ],
            [
                'id' => 10011,
                'name' => 'manager.permission.del',
                'label' => '删除权限',
                'cid' => 10008,
                'icon' => '',
            ],
        ]);
    }
}
