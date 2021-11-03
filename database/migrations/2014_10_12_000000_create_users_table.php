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
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('user_name')->unique();
            $table->string('password');
            $table->string('website')->nullable();
            $table->string('bio')->nullable();
            $table->string('gender')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('is_private')->nullable();
            $table->tinyInteger('status_activity')->nullable();
            $table->tinyInteger('status_story')->nullable();
            $table->string('status_notification')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
