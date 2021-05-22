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
            $table->text('description')->nullable();
            $table->text('thumbnail_items-list');
            $table->text('thumbnail_item-page');
            $table->text('thumbnail_original');
            $table->text('tags')->nullable();;
            $table->integer('favorited');
            $table->integer('author');
            $table->integer('views');
            $table->integer('transitions');
            $table->integer('banned')->nullable();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name');
            $table->text('background_color')->nullable();
            $table->text('background_img')->nullable();
            $table->integer('popularity');
        });

        Schema::create('userTags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('item_id');
            $table->integer('user_id');
            $table->integer('tag_id');
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
