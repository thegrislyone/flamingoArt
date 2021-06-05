<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('channel');
            // $table->integer('users');
            $table->integer('user_first');
            $table->integer('user_second');
        });

        // Schema::create('chat_users', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        //     $table->integer('user_first');
        //     $table->integer('user_second');
        // });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('message_text');
            $table->text('chat');
            $table->integer('from');
            $table->integer('to');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_tables');
    }
}
