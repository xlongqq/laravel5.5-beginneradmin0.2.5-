<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_permissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->comment('权限名');
            $table->string('label')->comment('权限解释名称(菜单)');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('description')->nullable()->comment('描述与备注');
            $table->integer('cid')->comment('上级权限id');
            $table->integer('sort')->nullable()->default(0)->comment('排序，由大到小');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_permissions');
    }
}
