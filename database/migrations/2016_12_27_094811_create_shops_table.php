<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // shops table
        Schema::create('shops', function(Blueprint $t) {
            $t->increments('id');
            $t->string('shopify_id');
            $t->string('shop_owner_email');
            $t->string('public_domain'); // actual store's domain
            $t->string('permanent_domain'); // .myshopify.com domain
            $t->string('access_token');
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
        // drop shops table
        Schema::dropTable('shops');
    }
}
