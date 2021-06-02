<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('is_admin')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('email_verify_token')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('vkontakte')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('login');
            $table->text('avatar')->nullable();
            $table->text('banner')->nullable();
            $table->integer('views');
            $table->integer('likes');
            $table->integer('banned')->nullable();
            $table->timestamp('email_changed_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('login_changed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
