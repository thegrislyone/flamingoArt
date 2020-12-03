<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstDbKit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function(Blueprint $table) {
            $table->increments('id_item');
            $table->char('name', 50);
            $table->float('price', 10, 2);
            $table->text('description');
            $table->binary('thumbnail');
            $table->text('tags');
        });

        Schema::create('custom_users', function(Blueprint $table) {
            $table->increments('id_user');
            $table->char('name', 50);
            $table->integer('role');
            $table->char('nickname', 50);
            $table->binary('avatar');
        });

        Schema::create('roles', function(Blueprint $table) {
            $table->increments('id_role');
            $table->char('role_name', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('custom_users');
        Schema::dropIfExists('roles');
    }
}
