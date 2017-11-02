<?php

use Illuminate\Database\Seeder;

class ManagerRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manager_roles')->insert([
            'name' => '超级管理员',
        ]);
    }
}
