<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerPermissionRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_permission_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('role_id')->comment('角色id');
            $table->integer('permission_id')->comment('权限id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_permission_role');
    }
}
