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
            $table->id();
            $table->char('name', 50);
            $table->float('price', 10, 2);
            $table->timestamps();
            $table->text('description');
            $table->text('thumbnail');
            $table->text('tags');
            $table->integer('likes');
            $table->integer('author');
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name');
            $table->text('background_color')->nullable();
            $table->text('background_img')->nullable();
            $table->integer('popularity');
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
