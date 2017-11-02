<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
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
                'id' => 10000,
                'name' => 'manager.accounts',
                'label' => '权限管理',
                'cid' => 0,
                'icon' => 'fa-id-card',
            ],
            [
                'id' => 10001,
                'name' => 'manager.accounts.index',
                'label' => '子账号列表',
                'cid' => 10000,
                'icon' => '&#xe60a;',
            ],
            [
                'id' => 10002,
                'name' => 'manager.accounts.group.index',
                'label' => '权限组列表',
                'cid' => 10000,
                'icon' => '&#xe630;',
            ],
            [
                'id' => 10003,
                'name' => 'manager.accounts.add',
                'label' => '新增子账号',
                'cid' => 10001,
                'icon' => '',
            ],
            [
                'id' => 10004,
                'name' => 'manager.accounts.edit',
                'label' => '编辑子账号',
                'cid' => 10001,
                'icon' => '',
            ],
            [
                'id' => 10005,
                'name' => 'manager.accounts.addGroup',
                'label' => '新增权限组',
                'cid' => 10002,
                'icon' => '',
            ],
            [
                'id' => 10006,
                'name' => 'manager.accounts.editGroup',
                'label' => '编辑权限组',
                'cid' => 10002,
                'icon' => '',
            ],
            [
                'id' => 10007,
                'name' => 'manager.accounts.modifyLimit',
                'label' => '权限组授权',
                'cid' => 10002,
                'icon' => '',
            ],
        ]);
    }
}
