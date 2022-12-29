<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();
            $table->foreign('role_id')->on('roles')->references('id');
            $table->foreign('permission_id')->on('permissions')->references('id');
//            $table->foreignId('role_id')->on((new \App\Models\Role)->getTable())->references('id');
//            $table->foreignId('permission')->on((new \App\Models\Role)->getTable())->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permission');
    }
};
