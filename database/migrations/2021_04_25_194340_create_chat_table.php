<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTable extends Migration
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
            $table->text('room_id');
            $table->integer('interlocutors');
        });

        Schema::create('chat_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_first');
            $table->integer('user_second');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('message_text');
            $table->integer('interlocutors');
            $table->text('chat');
            $table->integer('checked_by_first')->nullable();
            $table->integer('checked_by_second')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat');
    }
}
