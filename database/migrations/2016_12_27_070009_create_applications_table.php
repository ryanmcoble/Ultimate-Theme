<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create the apps table
        Schema::create('apps', function(Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('key')->index();
            $t->timestamps();
        });

        // create the apps permissions table
        Schema::create('app_permissions', function(Blueprint $t) {
            $t->increments('id');
            $t->integer('app_id')->unsigned();
            $t->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
            $t->string('value')->index();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop the apps tables
        Schema::dropTable('app_permissions');
        Schema::dropTable('apps');
    }
}
